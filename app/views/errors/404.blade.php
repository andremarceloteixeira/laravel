@extends('layout')

@section('main')
<div class="row">
    <!-- start: 404 -->
    <div class="col-sm-12 page-error">
        <div class="error-number teal">
            404
        </div>
        <div class="error-details col-sm-6 col-sm-offset-3">
            <h3>{{ trans('actions.404_title') }}</h3>
            <p>
                {{ trans('actions.404_body') }}

                <br>
                <a href="{{ route('home.index') }}" class="btn btn-teal btn-return">
                    <i class="clip-home"></i> {{ trans('navigation.home') }}
                </a>
                <br>
            </p>

        </div>
    </div>
    <!-- end: 404 -->
</div>

@stop