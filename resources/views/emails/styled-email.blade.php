@component('mail::message')
# {{ $details['title'] }}

@foreach ($details['body'] as $item)
{{ $item }}<br>
@endforeach
@component('mail::button', ['url' => 'http://127.0.0.1:8000/login', 'color' => 'success'])
Connectez vous!!
@endcomponent

@lang('Thanks'),<br>
{{ config('app.name') }}
@endcomponent
