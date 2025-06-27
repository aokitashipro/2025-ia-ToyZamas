<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Toy;
use App\Models\Stocks_History;
use App\Http\Resources\Owner\StockListResource;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stocks = Toy::select('id', 'name', 'stock')
            ->orderBy('stock', 'asc')
            ->get();

        return StockListResource::collection($stocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $toy = Toy::findOrFail($id);
        // 必要に応じてリソースでラップ
        return response()->json([
            'id' => $toy->id,
            'name' => $toy->name,
            'stock' => $toy->stock,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $toy = Toy::findOrFail($id);

        //toyのstockを仕入れ分増加させる
        $increment = $request->increment;
        $toy->stock += $increment;
        $toy->save();

        //仕入れ履歴に今回の仕入れを追加
        $stock = [
            'toy_id' => $toy->id,
            'be_stored' => $toy->stock,
            'stock_in' => $increment > 0 ? $increment : 0,
            'stock_out' => $increment < 0 ? abs($increment) : 0,
            'description' => $request->description ?? '仕入れによる入庫',
        ];

        Stocks_History::create($stock);

        return response()->json([
            'id' => $toy->id,
            'name' => $toy->name,
            'stock' => $toy->stock,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
