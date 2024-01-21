<?php

namespace App\Console\Commands;

use App\Helpers\MLHelper;
use App\Models\News;
use App\Models\VectorizeNews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Phpml\FeatureExtraction\StopWords;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Tokenization\WordTokenizer;

class TokenCountVectorProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-count-vector-process {--c|insertCheck} {--p|page=} {--P|perPage=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vectorize news title';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $perPage = (int)$this->option('perPage');
        $page = (int)$this->option('page');
        // $news = News::skip(($page - 1) * $perPage)->take($perPage)->orderBy('created_at')->get();

        // Fetch news category
        $categories = News::select('category_crawl')
            ->distinct()
            ->pluck('category_crawl');

        $news = collect();

        foreach ($categories as $category) {
            $limitTraining = 0;
            if($category === 'Kesehatan'){
                $limitTraining = 1500;
            }
            if($category === 'Hukum'){
                // $limitTraining = 2100;
                $limitTraining = 1800;
            }
            if($category === 'Kuliner'){
                // $limitTraining = 2300;
                $limitTraining = 1800;
            }
            if($category === 'Politik' || $category === 'Teknologi'){
                // $limitTraining = 2500;
                $limitTraining = 1800;
            }
            if($category === 'Pendidikan' || $category === 'Otomotif'){
                // $limitTraining = 3500;
                $limitTraining = 1800;
            }
            if($category === 'Ekonomi' ){
                // $limitTraining = 4000;
                $limitTraining = 1800;
            }
            if($category === 'Hiburan'){
                // $limitTraining = 4500;
                $limitTraining = 1800;
            }
            if($category === 'Olahraga'){
                // $limitTraining = 5900;
                $limitTraining = 1800;
            }

            // if(
            //     $category === 'Kesehatan' || 
            //     $category === 'Hukum' ||
            //     $category === 'Kuliner' ||
            //     $category === 'Politik' ||
            //     $category === 'Teknologi'
            // ){
            // if(
            //     $category === 'Pendidikan' || 
            //     $category === 'Otomotif' ||
            //     $category === 'Ekonomi'
            // ){
            // if(
            //     $category === 'Olahraga' || 
            //     $category === 'Hiburan'
            // ){
                $selectedNews = News::where('category_crawl', $category)
                    ->limit($limitTraining)
                    // ->skip(($page - 1) * $perPage)
                    // ->orderBy('created_at')
                    ->inRandomOrder()
                    // ->take($perPage)
                    ->get();

                    dump(count($selectedNews->toArray()));

                    $news = $news->merge($selectedNews);
            // }

        }

        // dump('ekonomi ' . $news->where('category_crawl', 'Ekonomi')->count());
        // dump('hiburan ' . $news->where('category_crawl', 'Hiburan')->count());
        // dump('hukum ' . $news->where('category_crawl', 'Hukum')->count());
        // dump('kesehatan ' . $news->where('category_crawl', 'Kesehatan')->count());
        // dump('kuliner ' . $news->where('category_crawl', 'Kuliner')->count());
        // dump('olahraga ' . $news->where('category_crawl', 'Olahraga')->count());
        // dump('otomotif ' . $news->where('category_crawl', 'Otomotif')->count());
        // dump('pendidikan ' . $news->where('category_crawl', 'Pendidikan')->count());
        // dump('politik ' . $news->where('category_crawl', 'Politik')->count());
        // dump('teknologi ' . $news->where('category_crawl', 'Teknologi')->count());
        // dump('total ' . $news->count());
        $titles = $news->pluck('title')->toArray();
        $categories = $news->pluck('category_crawl')->toArray();

        dump('titles : '.count($titles));
        dump('categories : '. count($categories));

        // stemming
        // $this->info('start stemming');
        // $stemWords = collect();
        // $stemmer = new \Sastrawi\Stemmer\Stemmer(MLHelper::stemDictionary());
        // foreach($titles as $title){
        //     $stemWords = $stemWords->push($stemmer->stem(strtolower($title)));
        // }
        // $this->info('stemming complete');

        // Create a TokenCountVectorizer
        // $this->info('start tokenizing');
        // $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD));

        // $vectorizedTitles = $stemWords->toArray();

        // dump('stemWord : ' . count($vectorizedTitles));

        // $vectorizer->fit($vectorizedTitles);
        // $vectorizer->transform($vectorizedTitles);

        // $this->info('finish tokenizing');

        $this->info('store db');
        DB::transaction(function () use ($news) {
            $dataToInsert = $news->map(function ($n, $key) {
                return [
                    'news_id' => $n->id,
                    'title' => $n->title,
                    'category' => $n->category_crawl,
                    'news_created_at' => $n->created_at,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            $this->info('update or create '. $this->option('insertCheck'));
            
            $bar1 = $this->output->createProgressBar(count($dataToInsert));
            $bar1->start();
            foreach($news->pluck('id')->toArray() as $index => $news_id){
                if($this->option('insertCheck')){
                    VectorizeNews::updateOrCreate(
                        ['news_id' => $news_id],
                        $dataToInsert[$index]
                    );
                }else{
                    VectorizeNews::create(
                        $dataToInsert[$index]
                    );
                }
                $bar1->advance();
            }
            $bar1->finish();
        });
        $this->newLine();
        $this->info('store db complete');
        $this->info("tokenize page $page , per page $perPage, from " .count(News::all()));
    }
}
