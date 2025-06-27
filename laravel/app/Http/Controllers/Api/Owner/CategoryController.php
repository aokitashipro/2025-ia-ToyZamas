<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;


use App\Http\Requests\CategoryRequest;//リクエスト追加
use App\Http\Resources\owner\CategoryListResource;//リソース追加

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('sort_order')
            ->get();//ソート優先度を昇順で並びかえて表示

        return CategoryListResource::collection($categories);

     // ->json(['data' => $categories], 200);

        // 取得したデータをCategoryListResourceに変換し、統一フォーマットで返却
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validate());
        // 登録完了メッセージを追加してレスポンス
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
        return response()->json([
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
