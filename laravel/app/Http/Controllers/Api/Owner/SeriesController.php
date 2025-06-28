<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Http\Requests\SeriesRequest;
use App\Http\Resources\Owner\SeriesListResource;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $series = Series::orderBy('id', 'asc')->get();
        return SeriesListResource::collection($series);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeriesRequest $request)
    {
        $series = Series::create($request->all());
        return (new SeriesListResource($series))
            ->additional(['message' => 'シリーズが登録されました'])->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $series = Series::findOrFail($id);
        return new SeriesListResource($series);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeriesRequest $request, string $id)
    {
        $series = Series::findOrFail($id);
        $series->update($request->validated());
        return response()->json([
            'message' => '更新成功',
            'data' => $series
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $series = Series::findOrFail($id);
        $series->delete();
        return response()->json(['message' => 'カテゴリを削除しました'], 200);
    }
}
