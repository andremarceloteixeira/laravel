@extends('emails.layout')

@section('main')
{{ trans('emails.welcome', ['name' => $name]) }}

<p>
    {{ trans('emails.reset-content', ['email' => $username, 'password' => $password]) }}
</p>

<p>
    {{ trans('emails.regards', ['name' => $signature]) }}
</p>
@stop