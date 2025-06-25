<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Models\Series;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psy\Util\Json;

use App\Http\Requests\SeriesRequest;//リクエスト追加
use App\Http\Resources\Owner\SeriesListResource;//リソース追加

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $series = Series::orderBy('sort_order')
            ->get();//ソート優先度を昇順で並びかえて表示

        return SeriesListResource::collection($series);
        // 取得したデータをSeriesListResourceに変換し、統一フォーマットで返却
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeriesRequest $request)
    {
        $Series = Series::create($request->validate());
        // 登録完了メッセージを追加してレスポンス
        return (new SeriesListResource($Series ))
            ->additional(['message' => 'カテゴリーが登録されました'])->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Series  = Series ::findOrFail($id);
        return new SeriesListResource($Series);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeriesRequest $request, string $id)
    {
        $Series = Series::findOrFail($id);
        $Series->update($request->validated());
        return response()->json([
            'message' => '更新成功',
            'data' => $Series
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Series = Series::findOrFail($id);
        $Series->delete();
        return response()->json(['message' => 'カテゴリを削除しました'], 200);
    }
}
