<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stocks_History extends Model
{
    protected $table = 'stocks_history'; // テーブル名がデフォルト以外の場合に指定

    public function toy()
    {
        return $this->belongsTo(Toy::class, 'toy_id'); // 外部キー名を指定
    }
    protected $fillable = ['toy_id', 'be_stored','stock_in','stock_out','description'];
}
