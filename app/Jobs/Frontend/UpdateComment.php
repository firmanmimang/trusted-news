<?php

namespace App\Jobs\Frontend;

use App\Http\Requests\Frontend\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $body;
    private $author;
    private $news;
    private $comment;

    /**
     * Create a new job instance.
     */
    public function __construct(string $body, User $author, News $news, Comment $comment)
    {
        $this->body = $body;
        $this->author = $author;
        $this->news = $news;
        $this->comment = $comment;
    }

    public static function fromRequest(CommentUpdateRequest $request): self
    {
        return new static(
            $request->body(),
            $request->author(),
            $request->news(),
            $request->comment(),
        );
    }

    /**
     * Execute the job.
     */
    public function handle(): Comment
    {
        $this->news->comments()->where('slug', $this->comment->slug)->update([
            'body' => $this->body,
        ]);

        return $this->comment;
    }
}
