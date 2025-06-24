<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toy;
use App\Http\Resources\Owner\ToyListResource;

class ToyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toys = Toy::with(['category', 'series'])
                    ->select('name', 'price', 'category_id', 'series_id', 'stock', 'is_selling', 'is_reserve')
                    ->get();

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
        //
        $toy = Toy::with(['category', 'series'])
                    ->findOrFail($id);

        return $toy;
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
