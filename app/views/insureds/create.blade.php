@extends('layout')

@section('js-required')
@stop

@section('breadcrumb')
<li><i class="clip-users"></i> <a href="{{ route('insureds.index') }}">{{ trans('navigation.insureds') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.create', ['type' => trans('insureds.singular')]) }}</li>
@stop

@section('title')
{{ trans('navigation.insureds') }} <small>{{ trans('actions.create', ['type' => trans('processes.singular')]) }}</small>
@stop

@section('main')

@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::open(['route' => 'insureds.store', 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('insureds.reference') }}
    </label>
    <div class="col-sm-9">
        {{ Form::text('reference', Input::old('reference'), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('insureds.name') }}
    </label>
    <div class="col-sm-9">
        <span class="input-icon input-icon-right">
            {{ Form::text('name', Input::old('name'), ['class' => 'form-control']) }}
            <i class="fa fa-asterisk"></i> 
        </span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('insureds.insured_type_id') }}
    </label>
    <div class="col-sm-9">
        {{ Form::select('insured_type_id', InsuredType::dropdown(), Input::old('insured_type_id'), ['class' => 'form-control search-select']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('insureds.nif') }}
    </label>
    <div class="col-sm-9">
        {{ Form::text('nif', Input::old('nif'), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-2"></div>
    <div class="col-sm-9">
        <button type="submit" class="btn btn-green pull-right">
            <i class="fa fa-check-square"></i> {{ trans('actions.confirm') }} 
        </button>
    </div>
</div>
{{ Form::close() }}

@stop