<?php

namespace App\Http\Controllers\Backoffice\Classification;

use App\Helpers\MLHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
