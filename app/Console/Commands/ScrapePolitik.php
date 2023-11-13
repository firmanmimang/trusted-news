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
    protected $description = 'Scrape news politik from several news portal';

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
            // 'https://kilaskementerian.kompas.com/kemenkumham/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kementerian-panrb/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemendes/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kementan/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenkes/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/ditjen-migas/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kementerian-investasi-bkpm/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemensos/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemendag/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenkominfo/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemnaker/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemen-kp/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenparekraf/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenlu/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/bappenas/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/ditjen-cipta-karya/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenhub/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/ditjen-ebtke/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemdikbud/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/brin/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenko-perekonomian/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kementerian-pupr/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/fmb9/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/ditjen-penyediaan-perumahan/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/dit-sarana-tj-hubdat/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/djpu-kemenhub/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/ditjen-sda-kementerian-pupr/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemenko-pmk/news/sitemap.xml',
            // 'https://kilaskementerian.kompas.com/kemhan/news/sitemap.xml',

            'https://www.viva.co.id/sitemap/news/pilkada.xml',
            'https://www.viva.co.id/sitemap/news/militer.xml',

            'https://news.detik.com/pemilu/sitemap_news.xml',
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
                    ['title', trim($result->title)],
                    ['slug', Str::slug($result->title)],
                ])->exists();

                if (!$newsScrapeExists) {
                    DB::beginTransaction();
                    $news = News::create([
                        'category_id' => Category::where('name', 'Politik')->first()->id,
                        'category_crawl' => 'Politik',
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
