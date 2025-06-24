<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // テーブル名がデフォルト以外の場合に指定
    protected $fillable = ['name', 'sort_order'];

    public function toys()
    {
        return $this->hasMany(Toy::class);
    }
}
