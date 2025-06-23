<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'series'; // テーブル名がデフォルト以外の場合に指定
    protected $fillable = ['name', 'sort_order'];
}
