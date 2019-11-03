<!DOCTYPE html>
<html lang="{{ config('app.locale') }}"{!! config('app.rtl') ? ' direction="rtl" dir="rtl" style="direction: rtl"' : ''!!}>
<head>
    @include('layouts.head')
</head>

@yield('body')

</html>
