<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Toy;
use App\Models\Sale;
use App\Models\Sale_Item;
use App\Http\Resources\toyzamas\BuyResource;
//メール処理に使うので追加
use App\Mail\PurchaseCompletedMail;
use Illuminate\Support\Facades\Mail;

class BuyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id(); // ログインユーザーのID取得
        // $sale = null; // 事前に定義。メール処理で使う
        DB::beginTransaction();
        try {
            // 1. カート情報取得
            $cartItems = Cart::where('user_id', $userId)->get();

            // 3. salesテーブルに登録
            $sale = Sale::create([
                'user_id' => $userId
            ]);

            // 4. sales_itemsテーブルに登録 & 在庫減算
            foreach ($cartItems as $item) {
                Sale_Item::create([
                    'sale_id' => $sale->id,
                    'toy_id' => $item->toy_id,
                    'purchase_num' => $item->quantity,
                    'purchase_amount' => $item->quantity * $item->toy->price, // 購入金額を計算
                    'purchase_date' => now(),
                ]);

                // 在庫減算
                $product = Toy::find($item->toy_id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            // 5. カート削除
            Cart::where('user_id', $userId)->delete();
            DB::commit();

            // 5. メール送信
            Mail::to(Auth::user()->email)->send(new PurchaseCompletedMail($sale));
            return response()->json(['message' => '購入が完了しました']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with(['items.toy'])->find($id);
        if (!$sale) {
            return response()->json(['message' => '購入情報が見つかりません'], 404);
        }
        return new BuyResource($sale);
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
        //
    }
}
