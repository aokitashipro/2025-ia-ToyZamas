<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Toyzamas\ReservesListResource;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $reserve = Reserve::create([
            'user_id' => $user_id,
            'toy_id' => $request->toy_id,
            'reserve_date' => $request->reserve_date,
            'reserve_num' => $request->reserve_num
        ]);

        return response()->json([
            'reserve' => $reserve,
            'message' => '予約登録されました'
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
