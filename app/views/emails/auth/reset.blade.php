@extends('emails.layout')

@section('main')
{{ trans('emails.welcome', ['name' => $name]) }}

<p>
    {{ trans('emails.reset-content', ['email' => $email, 'password' => $password]) }}
</p>

<p>
    {{ trans('emails.regards', ['name' => $signature]) }}
</p>
@stop