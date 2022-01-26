@php
    use App\Models\Category;
    use App\Utils\FormatUtil;
@endphp

{{ $name }}さんの支払い入力結果<br>

{{ $year }}年{{ $month }}月分変動費<br>
@foreach($payments as $category_id => $payment)
    {{ Category::getCategoryName($category_id, Auth::id()) }} ： ¥{{ FormatUtil::numberFormat($payment) }}<br>
@endforeach

変動費の合計金額 ： ¥{{ FormatUtil::numberFormat($paymentSum) }}<br>

固定費 ： ¥{{ FormatUtil::numberFormat(config('const.fixed_cost')) }}<br>

変動費と固定費の合計金額 ： ¥{{ FormatUtil::numberFormat($totalAmount) }}
