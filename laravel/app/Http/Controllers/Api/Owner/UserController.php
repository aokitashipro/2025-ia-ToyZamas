<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\Owner\UserListResource;
use App\Http\Resources\Owner\UserResource;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //ユーザーデータ一覧
        $users = User::select('id','name', 'is_admin')
                    ->get();

        return UserListResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = User::create($request->all());

        return ('ユーザー登録完了');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::findOrFail($id);

        return (new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;

        $user->save();
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //エラーが出たらキャッチする仕組み
        //うまくいったらそのまま処理続行
        Log::info($id);
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'ユーザーが削除されました',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ユーザーが見つかりません',
            ], 404);
        }
    }
}
