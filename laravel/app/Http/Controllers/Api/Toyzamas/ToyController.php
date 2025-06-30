<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toyzamas\ToyListResource;
use App\Http\Resources\Toyzamas\ToyResource;
use Illuminate\Http\Request;
use App\Models\Toy;

use Illuminate\Support\Facades\Log;


class ToyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = $request->query('sort'); //sortの値があればtrue,無ければfalse
        if ($result)
        {
            //予約対象商品なら
            if($request->sort == 1) {
                $toys = Toy::where('is_reserve', true)->get();
                return ToyListResource::collection($toys);
            }

        } else {
            Log::info($request->category_id);
            Log::info($request->series_id);

            if($request->category_id != 0 && $request->series_id != 0)
            {
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('category_id',$request->category_id)
                ->where('series_id',$request->series_id)
                ->where('is_reserve', false) // ★追加
                ->get();
            } else if($request->category_id != 0 && $request->series_id == 0){
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('category_id',$request->category_id)
                ->where('is_reserve', false) // ★追加
                ->get();
            } else if($request->category_id == 0 && $request->series_id != 0){
                $toys = Toy::with(['category', 'series'])
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->where('series_id',$request->series_id)
                ->where('is_reserve', false) // ★追加
                ->get();
            } else{
                $toys = Toy::where('is_reserve', false) // ★追加
                ->orderBy('id', 'asc')->get();
            }
        }
        return ToyListResource::collection($toys);
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
        $toy = Toy::findOrfail($id);
        return new ToyResource($toy);
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
