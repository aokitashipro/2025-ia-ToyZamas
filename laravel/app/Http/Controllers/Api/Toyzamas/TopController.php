<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toy;
use App\Http\Resources\toyzamas\TopResource;

class TopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // おすすめ商品（ランダムで3つ）
        $recommendToys = Toy::where('is_selling', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        // その他ランダム商品（ランダムで9つ）
        $randomToys = Toy::where('is_selling', true)
            ->inRandomOrder()
            ->take(9)
            ->get();

        return response()->json([
            'recommend' => TopResource::collection($recommendToys),
            'random' => TopResource::collection($randomToys),
        ]);
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
        //
    }
}
