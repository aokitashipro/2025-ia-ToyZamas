<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toyzamas\CartResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Toyzamas\localStorage;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = User::find(Auth::id())->id;
        $carts = Cart::where('user_id', $user_id)->get();
        return CartResource::collection($carts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::id(); // 現在のユーザーIDを取得
        $cart = Cart::create([
            'user_id' => $user_id,
            'toy_id' => $request->toy_id,
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'cart' => $cart,
            'message' => '商品情報が登録されました'
        ], 201);
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
        // 該当商品を取得（見つからなければ404）
        $cart = Cart::findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->save();

        // 成功レスポンス
        return (new CartResource($cart))
            ->additional(['message' => '商品情報が更新されました'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //エラーが出たらキャッチする仕組み
        //うまくいったらそのまま処理続行
        try {
            $book = Cart::findOrFail($id);
            $book->delete();

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
