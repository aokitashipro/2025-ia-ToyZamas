@component('mail::message')
#{{ $user->name }} 様
この度はToyZamasをご利用いただき、誠にありがとうございます。

以下の内容でご予約を承りました。

- 商品名: {{ $toy->name }}
- 予約日: {{ $reserve->reserve_date }}
- 数量: {{ $reserve->reserve_num }}

またのご利用をお待ちしております。

@endcomponent
