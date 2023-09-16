<?php

namespace App\Http\Controllers\Backoffice\Category;

use App\Helpers\AlertHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Category\CategoryStoreRequest;
use App\Http\Requests\Backoffice\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.cms.category.index', [
            'categories' => Category::
                                when(request()->get('search'), fn ($query) => $query->where('name', 'LIKE', '%'.request()->get('search').'%'))
                                ->when(request()->get('column'), fn ($query) => $query->orderBy(request()->get('column'), request()->get('order')))
                                ->latest()->paginate(request()->size ?? 10)
        ]);
    }

    public function create()
    {
        return view('pages.cms.category.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = $request->name;
            $category->slug = (new Category())->uniqueSlug($request->name);
            $category->save();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_create', ['type' => "Category $category->name"]));
            return redirect()->route('cms.category.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function edit(Category $category)
    {
        return view('pages.cms.category.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            DB::beginTransaction();
            $category->slug = $request->name === $category->name ? $category->slug : (new Category())->uniqueSlug($request->name);
            $category->name = $request->name;
            $category->save();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_update', ['type' => "Category $category->name"]));
            return redirect()->route('cms.category.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }

    public function destroy(Category $category)
    {
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();

            AlertHelper::flashSuccess(trans('success.crud_delete', ['type' => "Category $category->name"]));
            return redirect()->route('cms.category.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // throw $th;
            Log::error($th);

            AlertHelper::flashError(trans('server.500'));
            return back();
        }
    }
}
