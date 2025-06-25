<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toyzamas\ToyListResource;
use App\Http\Resources\Toyzamas\ToyResource;
use Illuminate\Http\Request;
use App\Models\Toy;


class ToyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //仮 ランダムに１０こ取得
        $toys = Toy::inRandomOrder()->limit(10)->get();
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
