@php
use App\Models\Category;
use App\Utils\FormatUtil;
@endphp

{{ $name }}さんの支払い入力結果<br>

{{ $year }}年{{ $month }}月分変動費<br>
@foreach($payments as $category_id => $payment)
    {{ Category::getCategoryName($category_id) }} ： ¥{{ FormatUtil::numberFormat($payment) }}<br>
@endforeach

合計 ¥{{ FormatUtil::numberFormat($paymentSum) }}<br>

固定費 ¥{{ FormatUtil::numberFormat(config('const.fixed_cost')) }}<br>

1人あたり支払い金額合計 ¥{{ FormatUtil::numberFormat($calcResult) }}