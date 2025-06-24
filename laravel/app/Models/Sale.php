<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales'; // テーブル名がデフォルト以外の場合に指定

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 外部キー名を指定
    }

    protected $fillable = ['user_id'];
}
