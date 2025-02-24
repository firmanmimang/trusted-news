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

class ScrapeHiburan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:hiburan {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news hiburan from several news portals';

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
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
            'Kompas',
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
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
            'Detik',
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
            'https://www.kompas.com/hype/musik/news/sitemap.xml',
            'https://www.kompas.com/hype/film/news/sitemap.xml',
            'https://www.kompas.com/hype/seleb/news/sitemap.xml',
            'https://www.kompas.com/hype/hits/news/sitemap.xml',

            'https://travel.kompas.com/jalan-jalan/news/sitemap.xml',
            'https://travel.kompas.com/itinerary/news/sitemap.xml',
            'https://travel.kompas.com/travel-tips/news/sitemap.xml',
            'https://travel.kompas.com/travel-promo/news/sitemap.xml',
            'https://travel.kompas.com/jepang-terkini/news/sitemap.xml',
            'https://entertainment.kompas.com/news/sitemap.xml',
            'https://travel.kompas.com/travel-update/news/sitemap.xml',
            'https://tekno.kompas.com/game/news/sitemap.xml',

            'https://inet.detik.com/gamesnews/sitemap_news.xml',
            'https://inet.detik.com/features/sitemap_news.xml',
            'https://hot.detik.com/celebs/sitemap_news.xml',
            'https://hot.detik.com/music/sitemap_news.xml',
            'https://hot.detik.com/movie/sitemap_news.xml',
            'https://hot.detik.com/culture/sitemap_news.xml',
            'https://hot.detik.com/kpop/sitemap_news.xml',
            'https://wolipop.detik.com/fashion/sitemap_news.xml',
            'https://wolipop.detik.com/beauty/sitemap_news.xml',
            'https://wolipop.detik.com/relationship/sitemap_news.xml',
            'https://wolipop.detik.com/sale-and-shop/sitemap_news.xml',
            'https://wolipop.detik.com/wedding/sitemap_news.xml',
            'https://wolipop.detik.com/entertainment/sitemap_news.xml',
            'https://wolipop.detik.com/work-and-money/sitemap_news.xml',
            'https://wolipop.detik.com/living/sitemap_news.xml',
            'https://wolipop.detik.com/hijab/sitemap_news.xml',
            'https://wolipop.detik.com/horoscope/sitemap_news.xml',
            'https://travel.detik.com/travel-news/sitemap_news.xml',
            'https://travel.detik.com/destination/sitemap_news.xml',
            'https://travel.detik.com/domestic-destination/sitemap_news.xml',
            'https://travel.detik.com/international-destination/sitemap_news.xml',
            'https://travel.detik.com/cerita-perjalanan/sitemap_news.xml',
            'https://www.detik.com/jateng/wisata/sitemap_news.xml',
            'https://www.detik.com/jatim/wisata/sitemap_news.xml',
            'https://www.detik.com/jabar/wisata/sitemap_news.xml',
            'https://www.detik.com/sulsel/wisata/sitemap_news.xml',
            'https://www.detik.com/sumut/wisata/sitemap_news.xml',
            'https://www.detik.com/bali/wisata/sitemap_news.xml',
            'https://www.detik.com/sumbagsel/wisata/sitemap_news.xml',
            'https://www.detik.com/jogja/plesir/sitemap_news.xml',
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

            // Crawl author
            $author = null;
            if ($result->source == "Detik") {
                try {
                    $authorText = $page->filter('.detail__author')->text();
                    $parts = explode('-', $authorText);
                    $author = count($parts) > 0 ? trim($parts[0]) : null;
                } catch (\Throwable $th) {
                    $author = null;
                }
            }
            if ($result->source == "Viva") {
                try {
                    $author = $page->filter('.main-content-author ul')->text();
                } catch (\Throwable $th) {
                    $author = null;
                }
            }
            if ($result->source == "Kompas") {
                try {
                    $author = $page->filter('.read__credit__item')->text();
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
            }
            if ($result->source == "Viva") {
                try {
                    $img = $page->filter('.main-content-image .mci-frame img')->eq(0)->extract(['src', 'alt']);
                } catch (\Throwable $th) {
                    $img = null;
                }
            }
            if ($result->source == "Kompas") {
                try {
                    if ($page->filter('.photo img')->count() > 0) {
                        $img = $page->filter('.photo img')->eq(0)->extract(['src', 'alt']);
                    } elseif ($page->filter('.photo__wrapper img')->count() > 0) {
                        $img = $page->filter('.photo__wrapper img')->eq(0)->extract(['src', 'alt']);
                    }
                } catch (\Throwable $th) {
                    $img = null;
                }
            }

            // Crawl body
            $body = [];
            if ($result->source == "Detik") {
                $body = $page->filter('.detail__body-text')->each(function ($item) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
                });
            }
            if ($result->source == "Viva") {
                $body = $page->filter('.main-content-detail')->each(function ($item) {
                    $src = $item->filter('img')->extract(['data-original']);
                    $item->filter('img')->each(function ($img, $i) use ($src) {
                        try {
                            $img->getNode(0)->setAttribute('src', $src[$i] ?? '');
                        } catch (\Throwable $th) {
                            // Ignore errors
                        }
                    });
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
                });
            }
            if ($result->source == "Kompas") {
                $body = $page->filter('.read__content')->each(function ($item) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
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
                        'category_id' => Category::where('name', 'Hiburan')->first()->id,
                        'category_crawl' => 'Hiburan',
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
                        'category_id' => Category::where('name', 'Hiburan')->first()->id,
                        'category_crawl' => 'Hiburan',
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
        $this->info("\nCrawling news detail and inserting/updating to database finished...");
        return 1;
    }
}
