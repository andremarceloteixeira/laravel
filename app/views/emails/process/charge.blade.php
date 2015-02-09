@extends('emails.layout')

@section('main')
{{ trans('emails.welcome', ['name' => $name]) }}

<p>
    {{ trans('emails.charge-content', ['certificate' => $certificate]) }}
</p>

<p>
    {{ trans('emails.regards', ['name' => $signature]) }}
</p>
@stop