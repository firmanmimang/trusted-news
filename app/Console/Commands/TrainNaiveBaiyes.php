<?php

namespace App\Console\Commands;

use App\Helpers\MLHelper;
use App\Models\Category;
use App\Models\News;
use App\Models\VectorizeNews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Phpml\Classification\NaiveBayes;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\StopWords;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Metric\Accuracy;
use Phpml\Metric\ClassificationReport;
use Phpml\Metric\ConfusionMatrix;
use Phpml\ModelManager;
use Phpml\Pipeline;
use Phpml\Tokenization\NGramTokenizer;
use Phpml\Tokenization\WordTokenizer;

class TrainNaiveBaiyes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:naive-bayes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make model training naive bayes news title classification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start execute modeling naive bayes');
        
        // Fetch news category
        $start = now();

        $categories = Category::get()->pluck('name');

        $news = collect();

        foreach ($categories as $category) {
            $limitTraining = 0;
            if($category === 'Kesehatan'){
                $limitTraining = 500;
            }
            if($category === 'Hukum'){
                // $limitTraining = 2100;
                $limitTraining = 500;
            }
            if($category === 'Kuliner'){
                // $limitTraining = 2300;
                $limitTraining = 500;
            }
            if($category === 'Politik' || $category === 'Teknologi'){
                // $limitTraining = 2500;
                $limitTraining = 500;
            }
            if($category === 'Pendidikan' || $category === 'Otomotif'){
                // $limitTraining = 3500;
                $limitTraining = 500;
            }
            if($category === 'Ekonomi' ){
                // $limitTraining = 4000;
                $limitTraining = 500;
            }
            if($category === 'Hiburan'){
                // $limitTraining = 4500;
                $limitTraining = 500;
            }
            if($category === 'Olahraga'){
                // $limitTraining = 5900;
                $limitTraining = 500;
            }

            $selectedNews = VectorizeNews::where('category', $category)
                ->limit($limitTraining)
                ->get();

            // dump(count($selectedNews->toArray()));

            $news = $news->merge($selectedNews);

        }

        // $news = VectorizeNews::get();
    
        // Create an ArrayDataset
        $this->info('create dataset');
        $dataset = new ArrayDataset($news->pluck('title')->toArray(), $news->pluck('category')->toArray());

        $this->info('split train and test samples');
        $split = new StratifiedRandomSplit($dataset, 0.2);
        $samples = $split->getTrainSamples();

        $this->info("train " . count($split->getTrainSamples()));
        $this->info("test " . count($split->getTestSamples()));
        
        $stemmer = new \Sastrawi\Stemmer\Stemmer(MLHelper::stemDictionary());
        $this->info('stem news training');
        $stemWords = collect();
        $stemTestWords = collect();
        foreach($samples as $title){
            $stemWords = $stemWords->push($stemmer->stem( strtolower($title)));
        }
        foreach($split->getTestSamples() as $title){
            $stemTestWords = $stemTestWords->push($stemmer->stem( strtolower($title)));
        }
        
        // Create a TokenCountVectorizer
        // $vectorizer = new TokenCountVectorizer(new WordTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD));
        // $this->info('vectorize train');
        // $vectorizer->fit($samples);
        // $vectorizer->transform($samples);

        // $this->info('start vectorize data testing');
        // // Transform the testing titles using the same vectorizer
        // $vectorizedTestingTitles = $newsTesting->map(function($news) use($stemmer) {
        //     return $stemmer->stem(strtolower($news->title));
        // })->toArray();

        // $vectorizer->fit($vectorizedTestingTitles);
        // $vectorizer->transform($vectorizedTestingTitles);

        // dump("testing title" . count($vectorizedTestingTitles));
        // dump("testing category" . count($newsTesting->pluck('category_crawl')->toArray()));
        // dump($vectorizedTestingTitles[0]);
        // $this->info('vetorize data testing complete');
        
        $startTrain = now();
        $this->info('start make model train naive bayes');
        $pipeline = new Pipeline([
            new TokenCountVectorizer(new WordTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD)),
            new TfIdfTransformer()
        ], new NaiveBayes());
        $pipeline->train($stemWords->toArray(), $split->getTrainLabels());
        $this->info('training time : '. $startTrain->diffInSeconds(now()) . " seconds");

        $startTest = now();
        $this->info('start testing');
        $predicted = $pipeline->predict($stemTestWords->toArray());

        $this->info('testing complete, start calculating the accuracy');
        $this->info('testing time : '. $startTest->diffInSeconds(now()) . " seconds");

        $text = 'Produksi Pisang di Desa Mekarbuana Karawang Capai 14 Ton/Hari'; // or load it from request, api, cli, etc.

        dump($text);
        dump($pipeline->predict([$text]));
        
        $accuracy = Accuracy::score($split->getTestLabels(), $predicted);
        $report = new ClassificationReport($split->getTestLabels(), $predicted);
        $confusionMatrix = ConfusionMatrix::compute($split->getTestLabels(), $predicted);

        $this->info('execution time : '. $start->diffInSeconds(now()) . " seconds");

        dump('accuracy');
        dump($accuracy);
        dump('precision');
        dump($report->getPrecision());
        dump('recall');
        dump($report->getRecall());
        dump('f1 score');
        dump($report->getF1score());
        dump('support');
        dump($report->getSupport());
        dump('average');
        dump($report->getAverage());
        dump('confussion matrix');
        dump($confusionMatrix);

        $this->info('store model training');
        $modelManager = new ModelManager();
        $modelManager->saveToFile($pipeline, storage_path("app\\naive-bayes.phpml"));

        return "Naive Bayes classifier trained. Accuracy: $accuracy";
    }
}
