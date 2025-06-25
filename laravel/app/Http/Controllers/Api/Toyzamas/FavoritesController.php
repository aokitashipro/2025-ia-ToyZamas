<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toyzamas\FavoritesListResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorit;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = User::find(Auth::id())->id;
        $favorit = Favorit::where('user_id', $user_id)->get();
        return FavoritesListResource::collection($favorit);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $user_id = Auth::id(); // 現在のユーザーIDを取得
    //     $favorit= Favorit::create([
    //         'user_id' => $user_id,
    //         'toy_id' => $request->toy_id,
    //     ]);

    //     return response()->json([
    //         'favorit' => $favorit,
    //         'message' => 'お気に入り登録されました'
    //     ], 201);
    // }
    public function store(Request $request)
    {
        $user_id = Auth::id(); // 現在のユーザーIDを取得

        // すでにお気に入り登録されているか確認
        $exists = Favorit::where('user_id', $user_id)
            ->where('toy_id', $request->toy_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'この商品は既にお気に入り登録されています。',
            ], 409); // HTTP 409 Conflict
        }

        try {
            // お気に入り登録処理
            $favorit = Favorit::create([
                'user_id' => $user_id,
                'toy_id' => $request->toy_id,
            ]);

            return response()->json([
                'favorit' => $favorit,
                'message' => 'お気に入り登録されました',
            ], 201); // HTTP 201 Created
        } catch (\Exception $e) {
            // エラーハンドリング
            return response()->json([
                'message' => 'お気に入り登録中にエラーが発生しました。',
                'error' => $e->getMessage(),
            ], 500); // HTTP 500 Internal Server Error
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //エラーが出たらキャッチする仕組み
        //うまくいったらそのまま処理続行
        try {
            $favorit = Favorit::findOrFail($id);
            $favorit->delete();

            return response()->json([
                'message' => '商品が削除されました',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '商品が見つかりません',
            ], 404);
        }
    }
}
