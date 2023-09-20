<?php

namespace App\Http\Controllers\Backoffice\News;

use App\Helpers\AlertHelper;
use App\Helpers\ParseUrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\News\NewsStoreRequest;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index()
    {
        return view('pages.cms.news.in-house.index', [
            'news' => News::with(['author', 'category'])->withCount('comments')
                        ->crawlStatus(false)
                        ->when(request()->get('search'), fn ($query) => $query->where('title', 'LIKE', '%'.request()->get('search').'%'))
                        ->when(request()->get('column'), fn ($query) => $query->orderBy(request()->get('column'), request()->get('order')))
                        ->latest()->paginate(request()->size ?? 10),
        ]);
    }

    public function create()
    {
        return view('pages.cms.news.in-house.create', [
            'categories' => Category::get(['id', 'name']),
        ]);
    }
    
    public function store(NewsStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $news = new News();
            $news->title = $request->title;
            $news->slug = (new News())->uniqueSlug($request->title);
            $news->category_id = $request->category;
            $news->user_id = auth('cms')->user()->id;
            $news->excerpt = $request->excerpt ? $request->excerpt : News::generateExcerpt($request->description, 250);
            $news->body = $request->description;
            $news->image_description = $request->image_description;
            $news->publish_status = $request->publish_status === 'true' ? true : false;
            $news->comment_status = $request->comment_status === 'true' ? true : false;
            $news->is_crawl = false;
            $news->is_highlight = false;
            $news->save();

            if(request()->hasFile('image')) {
                $image = ParseUrlHelper::ParseUrl($news->replaceImageNews($request->file('image')));
                $news->image = $image;
                $news->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "News $news->title"]));
            return redirect()->route('cms.news.in-house.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function edit(News $news)
    {
        return view('pages.cms.news.in-house.edit', [
            'news' => $news->load(['author', 'category'])->loadCount('comments'),
            'categories' => Category::get(['id', 'name']),
        ]);
    }

    public function update(NewsStoreRequest $request, News $news)
    {
        try {
            DB::beginTransaction();
            $news->slug = $request->title === $news->title ? $news->title : (new News())->uniqueSlug($request->title);
            $news->title = $request->title;
            $news->category_id = $request->category;
            $news->user_id = auth('cms')->user()->id;
            $news->excerpt = $request->excerpt ? $request->excerpt : News::generateExcerpt($request->description, 250);
            $news->body = $request->description;
            $news->image_description = $request->image_description;
            $news->publish_status = $request->publish_status === 'true' ? true : false;
            $news->comment_status = $request->comment_status === 'true' ? true : false;
            $news->is_crawl = false;
            $news->is_highlight = false;
            $news->save();

            if(request()->hasFile('image')) {
                $image = ParseUrlHelper::ParseUrl($news->replaceImageNews($request->file('image')));
                $news->image = $image;
                $news->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "News $news->title"]));
            return redirect()->route('cms.news.in-house.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function destroy(News $news)
    {
        try {
            DB::beginTransaction();
            $news->deleteImage();
            $news->delete();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "News $news->title"]));
            return redirect()->route('cms.news.in-house.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
