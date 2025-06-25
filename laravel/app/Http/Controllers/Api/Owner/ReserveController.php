<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserve;
use App\Models\Toy;
use Illuminate\Support\Facades\DB;


class ReserveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //予約に関連するユーザーや商品の情報を一括取得してAPIで返すため。
        $reserves = Reserve::with(['user', 'toy'])->latest()->get();//latest() は新しい予約順に並べる
        //
        return response()->json([
            'data' => $reserves
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'toy_id' => 'required|exists:toys,id',
            'reserve_date' => 'required|date',
            'reserve_num' => 'required|integer|min:1',
        ]);

        // 対象商品を取得
        $toy = Toy::findOrFail($request->toy_id);

        // 在庫チェック
        if ($toy->stock < $request->reserve_num) {
            return response()->json([
                'message' => '在庫がありません。現在の在庫数: ' . $toy->stock
            ], 400);
        }
        // 在庫数を減らす
        $toy->stock -= $request->reserve_num;
        $toy->save();

        // 予約を登録
        $reserve = Reserve::create([
            'user_id' => $request->user_id,
            'toy_id' => $request->toy_id,
            'reserve_date' => $request->reserve_date,
            'reserve_num' => $request->reserve_num,
        ]);
        return response()->json([
            'message' => '電話予約を登録しました。',
            'data' => $reserve
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
        $request->validate([
            'reserve_date' => 'required|date',
            'reserve_num' => 'required|integer|min:1',
        ]);
        //
        $reserve = Reserve::findOrFail($id);
        $toy = Toy::findOrFail($reserve->toy_id);

        DB::transaction(function () use ($request, $reserve, $toy) {
            $original_num = $reserve->reserve_num;
            $new_num = $request->reserve_num;
            $diff = $new_num - $original_num;

            if ($toy->stock < $diff) {//在庫が足りない時だけ例外
                throw new \Exception('在庫が足りません。現在の在庫数: ' . $toy->stock);
            }

            // 在庫再調整
            $toy->stock -= $diff;//予約数の差分だけ在庫を調整
            $toy->save();

            // 予約更新
            $reserve->update([
                'reserve_date' => $request->reserve_date,
                'reserve_num' => $new_num,
            ]);
        });

        return response()->json([
            'message' => '予約情報を更新しました。',
            'data' => $reserve
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $reserve = Reserve::findOrFail($id);
        $toy = Toy::findOrFail($reserve->toy_id);
        DB::transaction(function () use ($reserve, $toy) {
            // 在庫を元に戻す
            $toy->stock += $reserve->reserve_num;
            $toy->save();

            $reserve->delete();
        });

        return response()->json(['message' => '予約を削除しました。']);
    }
}
