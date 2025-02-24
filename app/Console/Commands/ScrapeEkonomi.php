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

class ScrapeEkonomi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:ekonomi {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news ekonomi from several news portal';

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
        ];

        $url_sitemap_array = [
            'https://www.kompas.com/properti/investasi-bisnis/news/sitemap.xml',

            'https://money.kompas.com/whats-new/news/sitemap.xml',
            'https://money.kompas.com/work-smart/news/sitemap.xml',
            'https://money.kompas.com/spend-smart/news/sitemap.xml',
            'https://money.kompas.com/earn-smart/news/sitemap.xml',
            'https://money.kompas.com/smartpreneur/news/sitemap.xml',
            'https://kilasfintech.kompas.com/transaksi-aman-dan-nyaman/news/sitemap.xml',
            'https://kilasfintech.kompas.com/pintar-bersama/news/sitemap.xml',
            'https://kilasfintech.kompas.com/solusi-digital-umkm/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/sustainable-business/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/berkarya-untuk-negeri/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/kawasan-industri-weda-bay/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/pertumbuhan-berkelanjutan/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/net-zero/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/bermitra-bersama-shell/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/rebuilding-strength-and-sustainability/news/sitemap.xml',
            'https://kilaskorporasi.kompas.com/kreasi-nusantara/news/sitemap.xml',
            'https://kilasperbankan.kompas.com/bri/news/sitemap.xml',
            'https://kilasperbankan.kompas.com/bank-mandiri/news/sitemap.xml',

            'https://finance.detik.com/energi/sitemap_news.xml',
            'https://finance.detik.com/ekonomi-bisnis/sitemap_news.xml',
            'https://finance.detik.com/fintech/sitemap_news.xml',
            'https://finance.detik.com/finansial/sitemap_news.xml',
            'https://inet.detik.com/business/sitemap_news.xml',
            'https://finance.detik.com/perencanaan-keuangan/sitemap_news.xml',
            'https://finance.detik.com/solusiukm/sitemap_news.xml',
            'https://finance.detik.com/properti/sitemap_news.xml',
            'https://finance.detik.com/industri/sitemap_news.xml',
            'https://finance.detik.com/dpreneur/sitemap_news.xml',
            'https://finance.detik.com/infrastruktur/sitemap_news.xml',
            'https://finance.detik.com/konsultasi/sitemap_news.xml',
            'https://finance.detik.com/bursa-valas/sitemap_news.xml',
            'https://finance.detik.com/moneter/sitemap_news.xml',
            'https://finance.detik.com/market-research/sitemap_news.xml',
            'https://finance.detik.com/lowongan-pekerjaan/sitemap_news.xml',
            'https://www.detik.com/jateng/bisnis/sitemap_news.xml',
            'https://www.detik.com/jatim/bisnis/sitemap_news.xml',
            'https://www.detik.com/jabar/bisnis/sitemap_news.xml',
            'https://www.detik.com/sulsel/bisnis/sitemap_news.xml',
            'https://www.detik.com/sumut/bisnis/sitemap_news.xml',
            'https://www.detik.com/bali/bisnis/sitemap_news.xml',
            'https://www.detik.com/sumbagsel/bisnis/sitemap_news.xml',
            'https://www.detik.com/jogja/bisnis/sitemap_news.xml',
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

        $this->info('Crawling news detail and inserting/updating database start...');
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
                            // Do nothing on error
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

            try {
                $newsScrapeExists = News::where([
                    ['title', trim(News::generateExcerpt($result->title, 200))],
                    ['slug', Str::slug($result->title)],
                ])->first();

                DB::beginTransaction();
                if (!$newsScrapeExists) {
                    $newsEntry = News::create([
                        'category_id' => Category::where('name', 'Ekonomi')->first()->id,
                        'category_crawl' => 'Ekonomi',
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
                        'category_id' => Category::where('name', 'Ekonomi')->first()->id,
                        'category_crawl' => 'Ekonomi',
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
