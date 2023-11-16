<?php

namespace App\Console\Commands;

use App\Helpers\MLHelper;
use App\Models\News;
use Illuminate\Console\Command;
use Phpml\Classification\NaiveBayes;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\StopWords;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Pipeline;
use Phpml\Serializer\JSON;
use Phpml\Tokenization\WhitespaceTokenizer;

class TrainNaiveBaiyes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:naive-baiyes';

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
        // Fetch news data from the database 1500 of each category
        $categories = News::select('category_crawl')
            ->distinct()
            ->pluck('category_crawl');

        $newsTraining = collect();
        $newsTesting = collect();

        foreach ($categories as $category) {
            $selectedNews = News::where('category_crawl', $category)
                ->inRandomOrder()
                ->limit($category === 'Kesehatan' ? 1000 : 1500)
                ->get();

            $newsTraining = $newsTraining->merge($selectedNews);

            // Select additional news not in the filtered set
            $newsTesting = $newsTesting->merge(
                News::where('category_crawl', $category)
                    ->whereNotIn('id', $selectedNews->pluck('id'))
                    ->inRandomOrder()
                    ->limit(500)
                    ->get()
            );
        }

        // dump('ekonomi ' . $newsTesting->where('category_crawl', 'Ekonomi')->count());
        // dump('hiburan ' . $newsTesting->where('category_crawl', 'Hiburan')->count());
        // dump('hukum ' . $newsTesting->where('category_crawl', 'Hukum')->count());
        // dump('kesehatan ' . $newsTesting->where('category_crawl', 'Kesehatan')->count());
        // dump('kuliner ' . $newsTesting->where('category_crawl', 'Kuliner')->count());
        // dump('olahraga ' . $newsTesting->where('category_crawl', 'Olahraga')->count());
        // dump('otomotif ' . $newsTesting->where('category_crawl', 'Otomotif')->count());
        // dump('pendidikan ' . $newsTesting->where('category_crawl', 'Pendidikan')->count());
        // dump('politik ' . $newsTesting->where('category_crawl', 'Politik')->count());
        // dump('teknologi ' . $newsTesting->where('category_crawl', 'Teknologi')->count());
        // dump('total ' . $newsTesting->count());
        // return 1;
        $titles = $newsTraining->pluck('title')->toArray();
        $categories = $newsTraining->pluck('category_crawl')->toArray();
        
        // Split the data into training and testing sets
        // $split = (int)(count($newsItems) * 0.8);
        // $trainingNews = array_slice($newsItems, 0, $split);
        // $testingNews = array_slice($newsItems, $split);
        
        
        
        // Create a TokenCountVectorizer
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD));
        
        // Initialize variables to store vectorized titles and categories
        // $vectorizedTitles = collect([]);
        $vectorizedTitles = $titles;
        
        dump(count($titles));
        
        $vectorizer->fit($vectorizedTitles);
        $vectorizer->transform($vectorizedTitles);
        
        // $chunkSize = 1000;
        // Iterate over the data in chunks
        // for ($i = 0; $i < count($titles); $i += $chunkSize) {
        // // for ($i = 0; $i < 15000; $i += $chunkSize) {
        //     $chunkTitles = array_slice($titles, $i, $chunkSize);

        //     $arrayTitles = $chunkTitles;
            
        //     // Fit the vectorizer and transform the chunk
        //     $vectorizer->fit($arrayTitles);
        //     $vectorizer->transform($arrayTitles);

        //     // Append vectorized data to the arrays
        //     $vectorizedTitles = $vectorizedTitles->merge($arrayTitles);
        //     $this->info(memory_get_usage(true));
        // }

        // dump(count($vectorizedTitles->toArray()));
        dump(count($vectorizedTitles));
        // return 1;

        $this->info('start make model train naive bayes');
        // Create an ArrayDataset with the vectorized titles
        // $dataset = new ArrayDataset($vectorizedTitles->toArray(), $categories);
        $dataset = new ArrayDataset($vectorizedTitles, $categories);

        // Train the Naive Bayes classifier
        $classifier = new NaiveBayes();
        $classifier->train($dataset->getSamples(), $dataset->getTargets());

        $this->info('model training complete');

        $this->info('start testing');
        // Transform the testing titles using the same vectorizer
        $vectorizedTestingTitles = $newsTesting->pluck('title')->take(10)->toArray();

        $vectorizer->transform($vectorizedTestingTitles);
        // Test the classifier
        $predictedCategories = $classifier->predict($vectorizedTestingTitles);
        $this->info('testing complete, start calculating the accuracy');

        // Evaluate the accuracy
        $accuracy = MLHelper::calculateAccuracy($predictedCategories, $newsTesting->pluck('category_crawl')->toArray());

        $this->info("accuracy : $accuracy");

        return "Naive Bayes classifier trained. Accuracy: $accuracy";
    }
}
