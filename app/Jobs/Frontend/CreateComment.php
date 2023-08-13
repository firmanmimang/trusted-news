<?php

namespace App\Jobs\Frontend;

use App\Http\Requests\Frontend\CommentStoreRequest;
use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CreateComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $body;
    private $author;
    private $news;

    /**
     * Create a new job instance.
     */
    public function __construct(string $body, User $author, News $news)
    {
        $this->body = $body;
        $this->author = $author;
        $this->news = $news;
    }

    public static function fromRequest(CommentStoreRequest $request): self
    {
        return new static(
            $request->body(),
            $request->author(),
            $request->news()
        );
    }

    /**
     * Execute the job.
     */
    public function handle(): Comment
    {
        $comment = $this->news->comments()->create([
            'body' => $this->body,
            'user_id' => $this->author->id,
            'slug' => Str::uuid(),
            'news_id' => $this->news->id,
            'date' => Carbon::now(),
            'status' => true,
        ]);

        // $comment->authoredBy($this->author);
        // $comment->save();

        return $comment;
    }
}
