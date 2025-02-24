<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use Carbon\Carbon;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ScrapePolitik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:politik {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news politik from several news portals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $results = [];
        $client = new Client();
        $dom = new DOMDocument();

        $source_array = [
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Viva',
            'Viva',
            'Detik',
        ];

        $url_sitemap_array = [
            'https://sorotpolitik.kompas.com/pdiperjuangan-untuk-indonesia-raya/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/pdiperjuangan-jawa-timur/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/bismillah-melayani/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/partai-gelora/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/f-pkb-rumah-rakyat/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/partai-solidaritas-indonesia-psi/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/partai-keadilan-sejahtera-pks/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/golkar-membangun-indonesia/news/sitemap.xml',
            'https://sorotpolitik.kompas.com/memilih-pemimpin-negeri/news/sitemap.xml',
            'https://kilasparlemen.kompas.com/dpr/news/sitemap.xml',
            'https://kilasparlemen.kompas.com/mpr/news/sitemap.xml',
            'https://www.viva.co.id/sitemap/news/pilkada.xml',
            'https://www.viva.co.id/sitemap/news/militer.xml',
            'https://news.detik.com/pemilu/sitemap_news.xml',
        ];

        $this->info('Crawling sitemap.xml news portals start...');
        $bar1 = $this->output->createProgressBar(count($url_sitemap_array));
        $bar1->start();
        foreach ($url_sitemap_array as $index => $url_sitemap_value) {
            try {
                $source = $source_array[$index];
                try {
                    // Load the sitemap using DOMDocument
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

        $this->info('Crawling news detail and inserting to database start...');
        $bar2 = $this->output->createProgressBar(count($results));
        $bar2->start();
        $countInsert = 0;
        foreach ($results as $result) {
            $countInsert++;
            try {
                // Use Guzzle to fetch the page
                if ($result->source == "Detik") {
                    $response = $client->request('GET', $result->url . '?single=1', [
                        'verify' => false,
                    ]);
                } elseif ($result->source == "Viva") {
                    $response = $client->request('GET', $result->url . '?page=all', [
                        'verify' => false,
                    ]);
                } elseif ($result->source == "Kompas") {
                    $response = $client->request('GET', $result->url . '?page=all', [
                        'verify' => false,
                    ]);
                }
                // Convert the response to a Symfony DomCrawler instance
                $html = $response->getBody()->getContents();
                $page = new Crawler($html);
            } catch (\Throwable $th) {
                $this->info("\nRequest failed on #$countInsert: $result->url; error: $th");
                continue;
            }

            // Crawl author based on source
            $author = null;
            if ($result->source == "Detik") {
                try {
                    $authorText = $page->filter('.detail__author')->text();
                    $parts = explode('-', $authorText);
                    $author = count($parts) > 0 ? trim($parts[0]) : null;
                } catch (\Throwable $th) {
                    $author = null;
                }
            } elseif ($result->source == "Viva") {
                try {
                    $author = $page->filter('.main-content-author ul')->text();
                } catch (\Throwable $th) {
                    $author = null;
                }
            } elseif ($result->source == "Kompas") {
                try {
                    $author = $page->filter('.credit-title-name')->text();
                } catch (\Throwable $th) {
                    $author = null;
                }
            }

            // Crawl image
            $img = null;
            if ($result->source == "Detik") {
                try {
                    $img = $page->filter('.detail__media-image img')->eq(0)->extract(['src', 'alt']);
                } catch (\Throwable $th) {
                    $img = null;
                }
            } elseif ($result->source == "Viva") {
                try {
                    $img = $page->filter('.main-content-image .mci-frame img')->eq(0)->extract(['src', 'alt']);
                } catch (\Throwable $th) {
                    $img = null;
                }
            } elseif ($result->source == "Kompas") {
                try {
                    if ($page->filter('.photo img')->count() > 0) {
                        $img = $page->filter('.photo img')->eq(0)->extract(['src', 'alt']);
                    } elseif ($page->filter('.photo__wrapper img')->count() > 0) {
                        $img = $page->filter('.photo__wrapper img')->eq(0)->extract(['src', 'alt']);
                    }
                } catch (\Throwable $th) {
                    throw $th;
                    $img = null;
                }
            }

            // Crawl body
            $body = [];
            if ($result->source == "Detik") {
                $body = $page->filter('.detail__body-text')->each(function (Crawler $node) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $node->html());
                });
            } elseif ($result->source == "Viva") {
                $body = $page->filter('.main-content-detail')->each(function (Crawler $node) {
                    // Replace lazy-loaded images if needed
                    $src = $node->filter('img')->extract(['data-original']);
                    $node->filter('img')->each(function (Crawler $imgNode, $i) use ($src) {
                        try {
                            $imgNode->getNode(0)->setAttribute('src', $src[$i] ?? '');
                        } catch (\Throwable $th) {
                            // Do nothing if error occurs
                        }
                    });
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $node->html());
                });
            } elseif ($result->source == "Kompas") {
                $body = $page->filter('.read__content')->each(function (Crawler $node) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $node->html());
                });
            }

            try {
                $newsScrapeExists = News::where([
                    ['title', trim(News::generateExcerpt($result->title, 200))],
                    ['slug', Str::slug($result->title)],
                ])->first();

                DB::beginTransaction();
                if (!$newsScrapeExists) {
                    $newsEntry = News::create([
                        'category_id' => Category::where('name', 'Politik')->first()->id,
                        'category_crawl' => 'Politik',
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
                        'category_id' => Category::where('name', 'Politik')->first()->id,
                        'category_crawl' => 'Politik',
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
        $this->info("\nCrawling news detail and inserting to database success...");
        return 1;
    }
}
