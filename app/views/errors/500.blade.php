@extends('layout')

@section('main')
<div class="row">
    <!-- start: 404 -->
    <div class="col-sm-12 page-error">
        <div class="error-number bricky">
            500
        </div>
        <div class="error-details col-sm-6 col-sm-offset-3">
            <h3>{{ trans('actions.500_title') }}</h3>
            <p>
            {{ trans('actions.500_body') }}
            <div class="alert alert-danger">
                <button data-dismiss="alert" class="close">×</button>
                {{ trans('actions.500_error', ['message' => $message, 'footer' => $footer, 'code' => $code]) }}
            </div>
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