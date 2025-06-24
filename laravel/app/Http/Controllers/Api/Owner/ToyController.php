<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toy;
use App\Http\Resources\Owner\ToyListResource;
use App\Http\Resources\Owner\ToyResource;

class ToyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toys = Toy::with(['category', 'series'])
                    ->select('name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                    ->get();

        return ToyListResource::collection($toys);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $toy = Toy::create($request->all());

        return('登録完了');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $toy = Toy::with(['category', 'series'])
                    ->findOrFail($id);

        return (new ToyResource($toy))
                    ->additional(['message' => '商品詳細']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $toy = Toy::findOrFail($id);

        $toy->name = $request->name;
        $toy->information = $request->information;
        $toy->price = $request->price;
        $toy->category_id = $request->category_id;
        $toy->series_id = $request->series_id;
        $toy->image_url = $request->image_url;
        $toy->stock = $request->stock;
        $toy->is_reserve = $request->is_reserve;
        $toy->release_date = $request->release_date;

        $toy->save();

        return('更新完了');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $toy = Toy::findOrFail($id)
                    ->delete();

        return('情報を削除しました');
    }
}
