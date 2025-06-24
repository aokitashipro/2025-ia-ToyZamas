<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'series'; // テーブル名がデフォルト以外の場合に指定
    protected $fillable = ['name', 'sort_order'];

    public function toys()
    {
        return $this->hasMany(Toy::class, 'series_id'); // 外部キー名を指定
    }
}
