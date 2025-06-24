<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_Item extends Model
{
    protected $table = 'sales_items'; // テーブル名がデフォルト以外の場合に指定

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id'); // 外部キー名を指定
    }

    public function toy()
    {
        return $this->belongsTo(Toy::class, 'toy_id'); // 外部キー名を指定
    }
    protected $fillable = ['sale_id', 'toy_id','purchase_num','purchase_amount'];
}
