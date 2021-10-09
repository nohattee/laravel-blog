<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Resources\PostCategoryCollection;

class PostCategoryController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(File::class, 'post-category');
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PostCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostCategory $category)
    {
        $validated = $request->validate(PostCategory::$rules);

        $category->update($validated);
        return response()->json([
            'message' => 'update_success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PostCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCategory $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'delete_success',
        ]);
    }
}
