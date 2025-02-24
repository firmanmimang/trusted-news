<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use Carbon\Carbon;
use DOMDocument;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHukum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:hukum {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news hukum from several news portals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $results = [];
        // Instantiate Guzzle HTTP client directly
        $client = new GuzzleClient();
        $dom = new DOMDocument();

        $source_array = [
            'Kompas',
            'Kompas',
            'Kompas',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
        ];

        $url_sitemap_array = [
            'https://www.kompas.com/konsultasihukum/konsultasi/news/sitemap.xml',
            'https://www.kompas.com/konsultasihukum/analisis/news/sitemap.xml',
            'https://www.kompas.com/konsultasihukum/database-peraturan/news/sitemap.xml',
            'https://news.detik.com/hukum/sitemap_news.xml',
            'https://inet.detik.com/law-amp-policy/sitemap_news.xml',
            // 'https://news.detik.com/x/crimestory/sitemap_news.xml',
            // 'https://news.detik.com/x/investigasi/sitemap_news.xml',
            'https://www.detik.com/jateng/hukum-dan-kriminal/sitemap_news.xml',
            'https://www.detik.com/jatim/hukum-dan-kriminal/sitemap_news.xml',
            'https://www.detik.com/jabar/hukum-dan-kriminal/sitemap_news.xml',
            'https://www.detik.com/sulsel/hukum-dan-kriminal/sitemap_news.xml',
            'https://www.detik.com/sumut/hukum-dan-kriminal/sitemap_news.xml',
            'https://www.detik.com/bali/hukum-dan-kriminal/sitemap_news.xml',
        ];

        $this->info('Crawling sitemap.xml news portals start...');
        $bar1 = $this->output->createProgressBar(count($url_sitemap_array));
        $bar1->start();
        foreach ($url_sitemap_array as $index => $url_sitemap_value) {
            try {
                $source = $source_array[$index];
                try {
                    @$dom->load($url_sitemap_value);
                    $urlNodes = $dom->getElementsByTagName('url');
                } catch (\Throwable $th) {
                    $this->info("\n$url_sitemap_value tidak ditemukan");
                    continue;
                }
                $newsNodes = $dom->getElementsByTagName('news');
                $i = 1;
                foreach ($urlNodes as $key => $u) {
                    $url_artikel = trim($u->childNodes->item(1)->nodeValue);
                    $n = $newsNodes[$key];
                    $name = $n->childNodes->item(1)->childNodes->item(1)->nodeValue;
                    $date = $n->childNodes->item(3)->nodeValue;
                    $title = $n->childNodes->item(5)->nodeValue;

                    $object = new stdClass();
                    $object->url = $url_artikel;
                    $object->source = $source;
                    $object->title = $title;
                    $object->date = $date;
                    $results[] = $object;

                    if ($i++ == $this->option('count')) break;
                }
            } catch (\Throwable $th) {
                $this->info("\nSomething went wrong when crawling sitemap.xml...");
                continue;
            }
            $bar1->advance();
        }
        $bar1->finish();
        $this->info("\nCrawl sitemap.xml success...");

        $this->info('Crawling news detail and inserting/updating to database start...');
        $bar2 = $this->output->createProgressBar(count($results));
        $bar2->start();
        $countInsert = 0;
        foreach ($results as $result) {
            $countInsert++;

            try {
                // Use Guzzle to make GET request
                if ($result->source == "Detik") {
                    $response = $client->request('GET', $result->url . '?single=1', [
                        'verify' => false,
                    ]);
                }
                if ($result->source == "Kompas") {
                    $response = $client->request('GET', $result->url . '?page=all', [
                        'verify' => false,
                    ]);
                }
                // Get HTML content and create a new Crawler instance
                $html = $response->getBody()->getContents();
                $crawler = new Crawler($html);
            } catch (\Throwable $th) {
                $this->info("\nRequest failed on #$countInsert: $result->url; error: $th");
                continue;
            }

            // Crawl author
            $author = null;
            if ($result->source == "Detik") {
                try {
                    $authorText = $crawler->filter('.detail__author')->text();
                    $parts = explode('-', $authorText);
                    $author = count($parts) > 0 ? trim($parts[0]) : null;
                } catch (\Throwable $th) {
                    $author = null;
                }
            }
            if ($result->source == "Kompas") {
                try {
                    $author = $crawler->filter('.read__credit__item')->text();
                } catch (\Throwable $th) {
                    $author = null;
                }
            }

            // Crawl image with conditional selectors
            $img = null;
            if ($result->source == "Detik") {
                try {
                    $img = $crawler->filter('.detail__media-image img')->eq(0)->extract(['src', 'alt']);
                } catch (\Throwable $th) {
                    $img = null;
                }
            }
            if ($result->source == "Kompas") {
                try {
                    // Check for .photo__wrap first, then fallback to .photo if needed
                    if ($crawler->filter('.photo__wrap img')->count() > 0) {
                        $img = $crawler->filter('.photo__wrap img')->eq(0)->extract(['src', 'alt']);
                    } elseif ($crawler->filter('.photo img')->count() > 0) {
                        $img = $crawler->filter('.photo img')->eq(0)->extract(['src', 'alt']);
                    } else {
                        $img = null;
                    }
                } catch (\Throwable $th) {
                    $img = null;
                }
            }

            // Crawl body
            $body = [];
            if ($result->source == "Detik") {
                $body = $crawler->filter('.detail__body-text')->each(function ($node) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $node->html());
                });
            }
            if ($result->source == "Kompas") {
                $body = $crawler->filter('.read__content')->each(function ($node) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $node->html());
                });
            }

            // Prepare data for updateOrCreate
            try {
                $newsScrapeExists = News::where([
                    ['title', trim(News::generateExcerpt($result->title, 200))],
                    ['slug', Str::slug($result->title)],
                ])->first();

                DB::beginTransaction();
                if (!$newsScrapeExists) {
                    $newsEntry = News::create([
                        'category_id' => Category::where('name', 'Hukum')->first()->id,
                        'category_crawl' => 'Hukum',
                        'is_crawl' => 'true',
                        'author_crawl' => trim($author),
                        'source_crawl' => trim($result->source),
                        'title' => News::generateExcerpt($result->title, 200),
                        'slug' => (new News())->uniqueSlug($result->title),
                        'image' => $img ? trim($img[0][0]) : null,
                        'image_description' => $img ? trim($img[0][1]) : null,
                        'excerpt' => News::generateExcerpt($body[0] ?? '', 200),
                        'is_highlight' => 'true',
                        'publish_status' => 'true',
                        'comment_status' => 'true',
                        'published_at' => Carbon::parse($result->date)->format('Y-m-d H:i:s'),
                    ]);
                    $newsEntry->body = trim($body[0] ?? '');
                    $newsEntry->save();
                } else {
                    $newsScrapeExists->update([
                        'category_id' => Category::where('name', 'Hukum')->first()->id,
                        'category_crawl' => 'Hukum',
                        'is_crawl' => 'true',
                        'author_crawl' => trim($author),
                        'source_crawl' => trim($result->source),
                        'title' => News::generateExcerpt($result->title, 200),
                        'slug' => (new News())->uniqueSlug($result->title),
                        'image' => $img ? trim($img[0][0]) : null,
                        'image_description' => $img ? trim($img[0][1]) : null,
                        'excerpt' => News::generateExcerpt($body[0] ?? '', 200),
                        'is_highlight' => 'true',
                        'publish_status' => 'true',
                        'comment_status' => 'true',
                        'published_at' => Carbon::parse($result->date)->format('Y-m-d H:i:s'),
                    ]);
                    $newsScrapeExists->body = trim($body[0] ?? '');
                    $newsScrapeExists->save();
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->info("\nCrawling news detail and inserting failed on #$countInsert");
                continue;
            }
            $bar2->advance();
        }
        $bar2->finish();
        $this->info("\nCrawling news detail and inserting/updating to database success...");
        return 1;
    }
}
