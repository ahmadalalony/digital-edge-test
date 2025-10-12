<p>{{ __('Hello') }} {{ $user->first_name }} {{ $user->last_name }},</p>

<p>{!! nl2br(e($bodyMessage)) !!}</p>

<p>{{ config('app.name') }}</p>