<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Owner\CategoryListResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        return CategoryListResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return (new CategoryListResource($category))
            ->additional(['message' => 'カテゴリーが登録されました'])->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return new CategoryListResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return response()
            ->json([
                'message' => '更新成功',
                'data' => $category
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'カテゴリを削除しました'], 200);
    }
}
