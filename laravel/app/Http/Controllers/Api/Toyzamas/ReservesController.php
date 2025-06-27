<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Toyzamas\ReservesListResource;
use App\Models\Reserve;
use App\Models\User;
use App\Models\Toy;
use App\Models\Sale;
use App\Models\Sale_Item;
use App\Models\Stocks_History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class ReservesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = User::find(Auth::id())->id;
        $reserve = Reserve::where('user_id', $user_id)->get();
        return ReservesListResource::collection($reserve);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::id(); // 現在のユーザーIDを取得
        DB::beginTransaction();

        try{
            //salesテーブルに登録
            $sale = Sale::create([
                'user_id' => $user_id
            ]);

            $reserve = Reserve::create([
                'user_id' => $user_id,
                'toy_id' => $request->toy_id,
                'reserve_date' => $request->reserve_date,
                'reserve_num' => $request->reserve_num
            ]);

            $sale_item = Sale_Item::create([
                'sale_id' => $sale->id,
                'toy_id' => $request->toy_id,
                'purchase_num' => $request->reserve_num,
                'purchase_amount' => $request->reserve_num * $request->reserve_price, // 購入金額を計算
                'purchase_date' => now(),
            ]);

            // 在庫減算
            $toy = Toy::find($request->toy_id);
            $toy->stock -= $request->reserve_num;
            $toy->save();

            $stock_history = Stocks_History::create([
                'toy_id' => $request->toy_id,
                'be_stored' => $toy->stock,
                'stock_in' => 0,
                'stock_out' => $request->reserve_num,
                'description' => '予約'
            ]);


            DB::commit();
            return response()->json([
                'reserve' => $reserve,
                'message' => '予約登録されました'
            ], 201);
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
            $reserve = Reserve::findOrFail($id);
            $reserve->delete();

             // 在庫増算
            $toy = Toy::find($reserve->toy_id);
            $toy->stock += $reserve->reserve_num;
            $toy->save();

            //在庫履歴
            $stock_history = Stocks_History::create([
                'toy_id' => $reserve->toy_id,
                'be_stored' => $toy->stock,
                'stock_in' => $reserve->reserve_num,
                'stock_out' => 0,
                'description' => '予約取り消し'
            ]);

            return response()->json([
                'message' => '予約が削除されました',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '予約が見つかりません',
            ], 404);
        }
    }
}
