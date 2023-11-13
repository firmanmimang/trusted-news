<?php

namespace App\Console\Commands;

use App\Helpers\MLHelper;
use Illuminate\Console\Command;
use Phpml\Classification\NaiveBayes;
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
        $modelPath = storage_path('ml/model/naive-baiyes.txt');

        if (file_exists($modelPath)) {
            // Load the trained model and vectorizer from the file
            $data = json_decode(file_get_contents($modelPath), true);
            $classifier = NaiveBayes::restore($data['classifier']);
            $vectorizer = TokenCountVectorizer::restore($data['vectorizer']);
        } else {
            // Sample categories, you should replace these with your actual categories.
            $categories = ['Politics', 'Sports', 'Technology', 'Entertainment'];

            // Sample training data, you should replace these with your actual training data.
            $trainingData = [
                ['Politics', 'Political news title'],
                ['Sports', 'Sports news title'],
                ['Technology', 'Tech news title'],
                ['Entertainment', 'Entertainment news title'],
                // Add more training data as needed.
            ];

            // Create a pipeline with a Tokenizer, Vectorizer, and Classifier
            $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer(), new StopWords(MLHelper::INDONESIA_STOP_WORD));
            $classifier = new NaiveBayes();

            $pipeline = new Pipeline([$vectorizer, $classifier]);
            $pipeline->train($trainingData, $categories);

            // Save the trained model and vectorizer to a file
            JSON::save($pipeline, $modelPath);
        }

        // Classify the news category based on the provided title
        $vectorizedTitle = $vectorizer->transform([$title]);
        $predictedCategory = $classifier->predict($vectorizedTitle[0]);

        return $predictedCategory;
    }
}
