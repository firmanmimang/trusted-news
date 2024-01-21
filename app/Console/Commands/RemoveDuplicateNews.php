<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;

class RemoveDuplicateNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicate-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete duplicate news';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duplicateTitles = News::select('title')
            ->groupBy('title')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('title');

        $bar1 = $this->output->createProgressBar(count($duplicateTitles->toArray()));
        $bar1->start();

        // Loop through duplicate titles and keep the first occurrence
        foreach ($duplicateTitles as $title) {
            $firstNews = News::where('title', $title)->first();
            
            // Delete all duplicates except the first occurrence
            News::where('title', $title)
                ->where('id', '<>', $firstNews->id)
                ->delete();

            $bar1->advance();
        }
        $bar1->finish();
        // Output a message indicating the process is complete
        $this->newLine();
        $this->info("Duplicate news titles removed successfully.");
        return 1;
    }
}
