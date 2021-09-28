<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostCategoryCollection;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new PostCategoryCollection(PostCategory::has('ancestors', '=', 1)->with('descendants')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(PostCategory::$rules);

        $category = PostCategory::create($validated);

        return response()->json([
            'data' => $category,
            'message' => 'create_success',
        ]);
    }
}
