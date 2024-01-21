<?php

namespace App\Console\Commands;

use App\Helpers\MLHelper;
use App\Models\Category;
use App\Models\VectorizeNews;
use Illuminate\Console\Command;
use Phpml\Classification\SVC;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\StopWords;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Metric\Accuracy;
use Phpml\Metric\ClassificationReport;
use Phpml\Metric\ConfusionMatrix;
use Phpml\Pipeline;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Tokenization\WordTokenizer;

class TrainSVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:svc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make model training SVC news title classification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('start execute modeling svc');

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

        // Create an ArrayDataset with the vectorized titles
        $this->info('create dataset');
        $dataset = new ArrayDataset($news->pluck('title')->toArray(), $news->pluck('category')->toArray());

        // $samples = $dataset->getSamples();
        $this->info('split train and test samples');
        $split = new StratifiedRandomSplit($dataset, 0.2);
        $samples = $split->getTrainSamples();

        $this->info("train " . count($samples));
        $this->info("test " . count($split->getTestSamples()));
        // $this->info("train " . 14160);
        // $this->info("test " . 3540);
        
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
        
        $startTrain = now();
        $this->info('start make model train SVC');
        $pipeline = new Pipeline([
            new TokenCountVectorizer(new WordTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD)),
            new TfIdfTransformer()
        ], new SVC(
                kernel: Kernel::RBF,
                cost: 1.0,
                degree: 3,
                coef0: 0.0,
                tolerance: 0.01
            )
        );
        $pipeline->train($stemWords->toArray(), $split->getTrainLabels());
        $this->info('training time : '. $startTrain->diffInSeconds(now()) . " seconds");

        $startTest = now();
        $this->info('start testing');

        $predicted = $pipeline->predict($stemTestWords->toArray());

        $this->info('testing complete, start calculating the accuracy');
        $this->info('testing time : '. $startTest->diffInSeconds(now()) . " seconds");
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

        return "SVC classifier trained. Accuracy: $accuracy";
    }
}
