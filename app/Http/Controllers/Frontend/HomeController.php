<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CommentStoreRequest;
use App\Http\Requests\Frontend\CommentUpdateRequest;
use App\Jobs\Frontend\CreateComment;
use App\Jobs\Frontend\UpdateComment;
use App\Models\Comment;
use App\Models\News;
use App\Policies\CommentPolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.frontend.home', [
            'news' => News::where('publish_status', true)
                        ->filter(request(['q', 's', 'category', 'author']))
                        ->orderBy('published_at', 'DESC')
                        ->paginate(12)
                        ->withQueryString()
                        ->fragment('berita'),
        ]);
    }

    public function show(News $news)
    {
        // Auth::loginUsingId(1);
        // Auth::logoutCurrentDevice();
        // dd(auth()->user()->hasPermissionTo('comment'));

        $comments = $news->comments()->with('authorRelation')->get();

        return view('pages.frontend.show', compact(['news', 'comments']));
    }

    public function storeComment(CommentStoreRequest $request, News $news)
    {
        $this->authorize(CommentPolicy::CREATE, Comment::class);

        abort_if(!$news->comment_status, 403, 'COMMENT TURN OFF');

        try {
            DB::beginTransaction();
            $this->dispatchSync(CreateComment::fromRequest($request));
            DB::commit();
            
            AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "Comment"]));
            return back()->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.crud_create', ['type' => "Comment"])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back()->withErrors(['500' => trans('server.500')]);
        }
    }

    public function updateComment(CommentUpdateRequest $request, News $news, Comment $comment)
    {
        $this->authorize(CommentPolicy::UPDATE, $comment);

        abort_if(!$news->comment_status, 403, 'COMMENT TURN OFF');

        try {
            DB::beginTransaction();
            if($comment->body !== $request->body){
                $this->dispatchSync(UpdateComment::fromRequest($request));
            }
            DB::commit();
            
            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Comment"]));
            return back()->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.crud_update', ['type' => "Comment"])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back()->withErrors(['500' => trans('server.500')]);
        }
    }

    public function deleteComment(News $news, Comment $comment)
    {
        $this->authorize(CommentPolicy::DELETE, $comment);

        abort_if(!$news->comment_status, 403, 'COMMENT TURN OFF');

        try {
            DB::beginTransaction();
            $news->comments()->where('slug', $comment->slug)->delete();
            DB::commit();
            
            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "Comment"]));
            return back()->with('alert', ['type' => AlertHelper::ALERT_SUCCESS, 'message' => trans('success.crud_delete', ['type' => "Comment"])]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back()->withErrors(['500' => trans('server.500')]);
        }
    }
}
