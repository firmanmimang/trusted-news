<?php

namespace App\Http\Controllers\Backoffice\Classification;

use App\Helpers\MLHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Phpml\Classification\NaiveBayes;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\StopWords;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

class NewsClassification extends Controller
{
    public function index()
    {
        $words = [];
        if(request()->post('title')){
            $words = MLHelper::tokenizeAndRemovePunctuation(request()->post('title'));
            dd($words);
        }
        return view('pages.cms.classification.index', compact('words'));
    }

    public function trainNaiveBaiyes()
    {
        // // Fetch news data from the database
        // $newsItems = News::without(['category', 'author'])->orderBy('published_at')->get()->toArray();
        // $titles = array_column($newsItems, 'title');
        // $categories = array_column($newsItems, 'category_crawl');
        
        // // Split the data into training and testing sets
        // $split = (int)(count($newsItems) * 0.8);
        // $trainingNews = array_slice($newsItems, 0, $split);
        // $testingNews = array_slice($newsItems, $split);
        
        
        // // Set the chunk size
        // $chunkSize = 1000;
        
        // // Create a TokenCountVectorizer
        // $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD));

        // // Initialize variables to store vectorized titles and categories
        // $vectorizedTitles = collect([]);

        // dump(count($trainingNews));
        
        // // Iterate over the data in chunks
        // // for ($i = 0; $i < count($trainingNews); $i += $chunkSize) {
        // for ($i = 0; $i < 15000; $i += $chunkSize) {
        //     $chunkTitles = array_slice($trainingNews, $i, $chunkSize);

        //     $arrayTitles = collect($chunkTitles)->pluck('title')->toArray();
            
        //     // Fit the vectorizer and transform the chunk
        //     $vectorizer->fit($arrayTitles);
        //     $vectorizer->transform($arrayTitles);

        //     // Append vectorized data to the arrays
        //     $vectorizedTitles->push($arrayTitles);
        //     dump('con', memory_get_usage(false));
        //     // if($i>9999) {
        //     //     dd('finish');
        //     // }
        // }

        // dd($vectorizedTitles);


        // // Create an ArrayDataset with the vectorized titles
        // $dataset = new ArrayDataset($vectorizedTitles->toArray(), $categories);

        // // Train the Naive Bayes classifier
        // $classifier = new NaiveBayes();
        // $classifier->train($dataset->getSamples(), $dataset->getTargets());

        // // Transform the testing titles using the same vectorizer
        // $vectorizedTestingTitles = $vectorizer->transform($testingNews['title']);

        // // Test the classifier
        // $predictedCategories = $classifier->predict($vectorizedTestingTitles);

        // // Evaluate the accuracy
        // $accuracy = $this->calculateAccuracy($predictedCategories, $testingNews['category']);

        // return "Naive Bayes classifier trained. Accuracy: $accuracy";

    }

    public function cleanTitle()
    {
        $newsItems = News::without(['category', 'author'])->orderBy('published_at')->get();

        foreach($newsItems as $item){
            $cleanedTitle = News::generateExcerpt($item->title, 200);
            $item->update(['title' => $cleanedTitle]);
        }
        dump(count($newsItems));
    }

    public function removeDuplicateNews()
    {
        $duplicateTitles = News::select('title')
            ->groupBy('title')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('title');

        // Loop through duplicate titles and keep the first occurrence
        foreach ($duplicateTitles as $title) {
            $firstNews = News::where('title', $title)->first();
            
            // Delete all duplicates except the first occurrence
            News::where('title', $title)
                ->where('id', '<>', $firstNews->id)
                ->delete();
        }

        // Output a message indicating the process is complete
        echo "Duplicate news titles removed successfully.";
    }
}
