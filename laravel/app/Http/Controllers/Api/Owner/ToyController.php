<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toy;
use App\Http\Resources\Owner\ToyListResource;
use App\Http\Resources\Owner\ToyResource;

use Illuminate\Support\Facades\Log;

class ToyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toys = Toy::with(['category', 'series'])
                    ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                    ->get();

        return ToyListResource::collection($toys);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //publicのstorageに画像ファイルを保存
        $file_path = $request->image_url->store('images', 'public');

        $toy = Toy::create([
            'name' => $request->name,
            'information' => $request->information,
            'price' => $request->price,
            'is_selling' => $request->is_selling,
            'is_reserve' => $request->is_reserve,
            'category_id' => $request->category_id,
            'series_id' => $request->series_id,
            'image_url' => $file_path,
            'stock' => $request->stock,
            'release_date' => $request->release_date
        ]);

        return (new ToyResource($toy));
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
        if($toy->image_url !== $request->image_url){
            $file_path = $request->image_url->store('images', 'public');
            $toy->image_url = $file_path;
        }
        $toy->is_selling = $request->is_selling;
        $toy->is_reserve = $request->is_reserve;
        $toy->release_date = $request->release_date;

        $toy->save();

        return (new ToyResource($toy))
        ->additional(['message' => '商品情報が更新されました。商品詳細へ戻ります'])
        ->response()
        ->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $toy = Toy::findOrFail($id);
        $toy->delete();


        return (new ToyResource($toy))
        ->additional(['message' => '商品情報が削除されました。商品一覧へ戻ります'])
        ->response()
        ->setStatusCode(201);
    }

    public function sort(Request $request)
    {
        if($request->sort === 'price_high'){
            $toys = Toy::with(['category', 'series'])
                ->orderBy('price', 'desc')
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->get();
        }else if($request->sort === 'price_low'){
            $toys = Toy::with(['category', 'series'])
                ->orderBy('price', 'asc')
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->get();
        // }else if($request->sort === 'category_high'){

        // }else if($request->sort === 'category_low'){

        // }else if($request->sort === 'series_high'){

        // }else if($request->sort === 'series_low'){

        }else if($request->sort === 'stock_much'){
            $toys = Toy::with(['category', 'series'])
                ->orderBy('stock', 'desc')
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->get();
        }else if($request->sort === 'stock_little'){
            $toys = Toy::with(['category', 'series'])
                ->orderBy('price', 'asc')
                ->select('id', 'name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve', 'created_at')
                ->get();
        }
        return ToyListResource::collection($toys);
    }
}
