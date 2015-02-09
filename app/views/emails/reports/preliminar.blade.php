@extends('emails.layout')

@section('main')
{{ trans('emails.welcome', ['name' => $name]) }}

<p>
    {{ trans('emails.preliminar-content', ['id' => $certificate]) }}
</p>

<p>
    {{ trans('emails.regards', ['name' => $signature]) }}
</p>
@stop