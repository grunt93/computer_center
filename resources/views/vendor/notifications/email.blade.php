@component('mail::message')
{{-- 問候語 --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# 發生錯誤！
@else
# 您好！
@endif
@endif

{{-- 內容 --}}
@foreach ($introLines as $line)
{{ $line }}
@endforeach

{{-- 按鈕 --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- 結尾內容 --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- 補充說明 --}}
@isset($actionText)
@slot('subcopy')
如果您無法點擊「{{ $actionText }}」按鈕，請複製下方連結至瀏覽器中開啟：<br>
<span class="break-all">[{{ $actionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
