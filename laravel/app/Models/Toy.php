<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toy extends Model
{
    protected $table = 'toys'; // テーブル名がデフォルト以外の場合に指定

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // 外部キー名を指定
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id'); // 外部キー名を指定
    }

    protected $fillable = ['name', 'information','price','category_id','series_id','stock','release_date', 'is_selling', 'is_reserve'];

    use HasFactory;
}
