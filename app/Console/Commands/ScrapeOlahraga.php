<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\News;
use Carbon\Carbon;
use DOMDocument;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Str;

class ScrapeOlahraga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:olahraga {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news olahraga from several news portal';

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

            'Viva',
            'Viva',

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
            'https://www.kompas.com/sports/news/sitemap.xml',
            'https://www.kompas.com/motogp/news/sitemap.xml',
            'https://www.kompas.com/badminton/news/sitemap.xml',

            'https://bola.kompas.com/liga-indonesia/news/sitemap.xml',
            'https://bola.kompas.com/liga-inggris/news/sitemap.xml',
            'https://bola.kompas.com/liga-italia/news/sitemap.xml',
            'https://bola.kompas.com/liga-spanyol/news/sitemap.xml',
            'https://bola.kompas.com/bundesliga/news/sitemap.xml',
            'https://bola.kompas.com/liga-champions/news/sitemap.xml',
            'https://bola.kompas.com/internasional/news/sitemap.xml',
            'https://bola.kompas.com/liga-lain/news/sitemap.xml',
            'https://bola.kompas.com/corner/news/sitemap.xml',
            'https://bola.kompas.com/olahraga/news/sitemap.xml',
            'https://bola.kompas.com/jadwal-dan-klasemen/news/sitemap.xml',
            'https://bola.kompas.com/asal-usul/news/sitemap.xml',
            'https://bola.kompas.com/timnas-indonesia/news/sitemap.xml',

            'https://www.viva.co.id/sitemap/news/sport.xml',
            'https://www.viva.co.id/sitemap/news/bola.xml',

            'https://sport.detik.com/raket/sitemap_news.xml',
            'https://sport.detik.com/f1/sitemap_news.xml',
            'https://sport.detik.com/moto-gp/sitemap_news.xml',
            'https://sport.detik.com/sepakbola/sitemap_news.xml',
            'https://sport.detik.com/sport-lain/sitemap_news.xml',
            'https://www.detik.com/jatim/sepakbola/sitemap_news.xml',
            'https://www.detik.com/jabar/sepakbola/sitemap_news.xml',
            'https://www.detik.com/sulsel/sepakbola/sitemap_news.xml',
            'https://www.detik.com/sumut/sepakbola/sitemap_news.xml',
            'https://www.detik.com/bali/sepakbola/sitemap_news.xml',
            'https://www.detik.com/sumbagsel/sepakbola/sitemap_news.xml',
            'https://www.detik.com/jogja/sepakbola/sitemap_news.xml',
        ];

        $this->info('crawl sitemap.xml news portal start...');
        $bar1 = $this->output->createProgressBar(count($url_sitemap_array));
        $bar1->start();
        foreach ($url_sitemap_array as $index => $url_sitemap_value) {
            try {
                $source = $source_array[$index];
                try {
                    $dom->load($url_sitemap_value);
                    $url = $dom->getElementsByTagName('url');
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->info("\n$url_sitemap_value tidak ditemukan");
                }
                $news = $dom->getElementsByTagName('news');
                $i = 1;
                foreach ($url as $key => $u) {

                    $url_artikel =  trim($u->childNodes->item(1)->nodeValue);

                    $n = $news[$key];

                    $name = $n->childNodes->item(1)->childNodes->item(1)->nodeValue;
                    $date =  $n->childNodes->item(3)->nodeValue;
                    $title =  $n->childNodes->item(5)->nodeValue;

                    $object = new stdClass();
                    $object->url = $url_artikel;
                    $object->source = $source;
                    $object->title = $title;
                    $object->date = $date;

                    $results[] = $object;

                    if ($i++ == $this->option('count')) break;
                }
            } catch (\Throwable $th) {
                throw $th;
                $this->info("\nsomething went wrong when crawling sitemap.xml...");
                // return "sitemap tidak ada";
            }

            // try {
            //     $categoryScrapeExists = Category::where([
            //         ['name', $source_array[$index]],
            //         ['slug', Str::slug($source_array[$index])],
            //     ])->exists();

            //     if (!$categoryScrapeExists) {
            //         DB::beginTransaction();
            //         Category::create([
            //             'name' => $source_array[$index],
            //             'slug' => Str::slug($source_array[$index]),
            //         ]);
            //         DB::commit();
            //     }
            // } catch (\Throwable $th) {
            //     DB::rollBack();
            //     throw $th;
            //     $this->info("\nsomething went wrong when inserting category...");
            //     return "something went wrong on creating category.";
            // }

            $bar1->advance();
        }
        $bar1->finish();
        $this->info("\ncrawl sitemap.xml success...");

        $this->info('crawling news detail and inserting to database start...');
        $bar2 = $this->output->createProgressBar(count($results));
        $bar2->start();
        $countInsert = 0;
        foreach ($results as $index => $result) {
            $countInsert++;

            try {
                if ($result->source == "Detik") $page = $client->request('GET', $result->url . '?single=1');
                if ($result->source == "Viva") $page = $client->request('GET', $result->url . '?page=all');
                if ($result->source == "Kompas") $page = $client->request('GET', $result->url . '?page=all');
                // if ($result->source == "Merdeka.com") $page = $client->request('GET', $result->url);
            } catch (\Throwable $th) {
                //throw $th;
                $this->info("\n request fail on ". $countInsert. " $result->url error $th");
            }

            // crawl author
            if ($result->source == "Detik") {
                try {
                    $author =  count(explode('-', $page->filter('.detail__author')->text())) > 0 ? explode('-', $page->filter('.detail__author')->text())[0] :  null;
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
            // if ($result->source == "Merdeka.com") {
            //     try {
            //         echo $page->filter('.reporter a')->text();
            //     } catch (\Throwable $th) {
            //         echo "empty";
            //     }
            // }

            // crawl image
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
                    $img = $page->filter('.photo__wrap img')->eq(0)->extract(['src', 'alt']);
                } catch (\Throwable $th) {
                    $img = null;
                }
            }
            // if ($result->source == "Merdeka.com") {
            //     try {
            //         print_r($page->filter('.mdk-dt-img img')->eq(0)->extract(['src', 'alt']));
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //         echo "img empty";
            //     }
            // }

            // crawl body
            $body = [];
            if ($result->source == "Detik") {
                $body[] = $page->filter('.detail__body-text')->each(function ($item) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
                });
            }
            if ($result->source == "Viva") {
                $body[] = $page->filter('.main-content-detail')->each(function ($item) use($result){
                    $src = $item->filter('img')->extract(['data-original']);
                    $item->filter('img')->each(function ($img, $i) use ($src) {
                        try {
                            $img->getNode(0)->setAttribute('src', $src[$i]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    });
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
                });
            }
            if ($result->source == "Kompas") {
                $body[] = $page->filter('.read__content')->each(function ($item) {
                    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $item->html());
                });
            }
            // if($result->source == "Merdeka.com"){
            //     $page->filter('.mdk-body-paragraph')->each(function ($item) {
            //         echo $item->html();
            //         echo "<br>";
            //     });
            // }

            try {
                $newsScrapeExists = News::where([
                    ['title', trim(News::generateExcerpt($result->title, 200))],
                    ['slug', Str::slug($result->title)],
                ])->exists();

                if (!$newsScrapeExists) {
                    DB::beginTransaction();
                    $news = News::create([
                        'category_id' => Category::where('name', 'Olahraga')->first()->id,
                        'category_crawl' => 'Olahraga',
                        'is_crawl' => true,
                        'author_crawl' => trim($author),
                        'source_crawl' => trim($result->source),
                        'title' => News::generateExcerpt($result->title, 200),
                        'slug' => (new News())->uniqueSlug($result->title),
                        'image' => $img ? trim($img[0][0]) : null,
                        'image_description' => $img ? trim($img[0][1]) : null,
                        // 'excerpt' => Str::limit(strip_tags(trim($body[0][0])), 200),
                        'excerpt' => News::generateExcerpt($body[0][0], 200),
                        'is_highlight' => true,
                        'publish_status' => true,
                        'comment_status' => true,
                        'published_at' => Carbon::parse($result->date)->format('Y-m-d H:i:s'),
                    ]);
                    $news->body = trim($body[0][0]);
                    $news->save();
                    DB::commit();
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                // throw $th;
                $this->info("\n crawling news detail and inserting fail on ". $countInsert);
                // return 'gagal insert di percobaan ' . $countInsert;
            }
            $bar2->advance();
        }
        $bar2->finish();
        $this->info("\n crawling news detail and inserting to database success...");
        return 1;
    }
}
