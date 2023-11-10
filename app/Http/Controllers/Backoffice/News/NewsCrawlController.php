<?php

namespace App\Http\Controllers\Backoffice\News;

use App\Helpers\AlertHelper;
use App\Helpers\ParseUrlHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\News\NewsCrawlUpdateRequest;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsCrawlController extends Controller
{
    public function index()
    {
        return view('pages.cms.news.crawl.index', [
            'news' => News::with(['category'])->withCount('comments')
                        ->crawlStatus(true)
                        ->when(request()->get('search'), fn ($query) => $query->where('title', 'LIKE', '%'.request()->get('search').'%'))
                        ->when(request()->get('column'), fn ($query) => $query->orderBy(request()->get('column'), request()->get('order')))
                        ->latest()->paginate(request()->size ?? 10),
        ]);
    }

    public function edit($news)
    {
        $news = News::with(['category'])->withCount('comments')
                    ->where('slug', $news)->crawlStatus(true)
                    ->firstOrFail();

        return view('pages.cms.news.crawl.edit', [
            'news' => $news,
            'categories' => Category::get(['id', 'name']),
        ]);
    }

    public function update(NewsCrawlUpdateRequest $request, News $news)
    {
        try {
            DB::beginTransaction();
            $news->slug = $request->title === $news->title ? $news->title : (new News())->uniqueSlug($request->title);
            $news->title = $request->title;
            $news->category_id = $request->category;
            $news->excerpt = $request->excerpt ? $request->excerpt : News::generateExcerpt($request->description, 250);
            $news->body = $request->description;
            $news->image_description = $request->image_description;
            $news->publish_status = $request->publish_status === 'true' ? true : false;
            $news->comment_status = $request->comment_status === 'true' ? true : false;
            $news->is_crawl = true;
            $news->is_highlight = false;
            $news->save();

            if(request()->hasFile('image')) {
                $image = ParseUrlHelper::ParseUrl($news->replaceImageNews($request->file('image')));
                $news->image = $image;
                $news->save();
            }
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "News $news->title"]));
            return redirect()->route('cms.news.crawl.index');
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
            $news->delete();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "News $news->title"]));
            return redirect()->route('cms.news.crawl.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    
}
