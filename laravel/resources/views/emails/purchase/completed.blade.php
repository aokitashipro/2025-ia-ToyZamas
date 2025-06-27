@component('mail::message')
# ご購入ありがとうございました！

{{ $sale->user->name }} 様

以下の内容でご注文を承りました。

@component('mail::table')
| 商品名 | 数量 | 金額 |
|--------|------|------|
@foreach ($sale->items as $item)
| {{ $item->toy->name }} | {{ $item->purchase_num }} | ¥{{ number_format($item->purchase_amount) }} |
@endforeach
@endcomponent

**合計金額：¥{{ number_format($sale->items->sum('purchase_amount')) }}**
またのご利用をお待ちしております！

@component('mail::button', ['url' => 'http://localhost:5173/'])
マイページを確認する
@endcomponent


@endcomponent

