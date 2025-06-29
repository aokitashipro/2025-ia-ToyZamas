<?php

namespace App\Http\Controllers\Api\Toyzamas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Toyzamas\ToyListResource;
use App\Http\Resources\Toyzamas\ToyResource;
use Illuminate\Http\Request;
use App\Models\Toy;

use App\Services\SortService;


class ToyController extends Controller
{
    private $sort;
    public function __construct(SortService $sortService)
    {
        $this->sort = $sortService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = $this->sort->sort($request);
        return ToyListResource::collection($result);
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
