<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\UseCases\PostCategoryUseCase;
use App\Http\Resources\PostCategoryCollection;

class PostCategoryController extends Controller
{
    /**
     * @var $postCategoryUseCase
     */
    protected $postCategoryUseCase;
    
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(PostCategoryUseCase $postCategoryUseCase)
    {
        $this->postCategoryUseCase = $postCategoryUseCase;
        $this->authorizeResource(PostCategory::class, 'post-category');
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
        $category = $this->postCategoryUseCase->create($request->all());

        return response()->json([
            'data' => $category,
            'message' => 'create_success',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostCategory $postCategory)
    {
        $this->postCategoryUseCase->update($request->all(), $postCategory);
        return response()->json([
            'message' => 'update_success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PostCategory  $postCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();
        return response()->json([
            'message' => 'delete_success',
        ]);
    }
}
