@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
        TextArea.init();
    });
</script>
@stop

@section('breadcrumb')
<li><i class="fa fa-tasks"></i> <a href="{{ route('clients.processes.index') }}">{{ trans('navigation.clients.processes') }}</a></li>
<li class="active"><i class="clip-copy-3"></i> {{ trans('actions.create', ['type' => trans('processes.singular')]) }}</li>
@stop

@section('title')
{{ trans('navigation.clients.processes') }} <small>{{ trans('actions.create', ['type' => trans('processes.singular')]) }}</small>
@stop

@section('main')
@if(Session::has('attachs_warning'))
<div class="alert alert-block alert-warning fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.warning') }}!</h4>
    <p>{{ Session::get('attachs_warning') }}</p>
</div>
@endif
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::open(['route' => 'clients.processes.store', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.reference') }}
    </label>
    <div class="col-sm-9">
         {{ Form::text('reference', $reference, ['value' => $reference, 'class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.email') }}
    </label>
    <div class="col-sm-9">
        <span class="input-icon input-icon-right">
            {{ Form::text('email', Input::old('email'), ['class' => 'form-control']) }}
            <i class="fa fa-asterisk"></i> 
        </span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.apolice') }}
    </label>
    <div class="col-sm-9">
        <span class="input-icon input-icon-right">
            {{ Form::text('apolice', Input::old('apolice'), ['class' => 'form-control']) }}
            <i class="fa fa-asterisk"></i> 
        </span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.client_attachments') }}
    </label>
    <div class="col-sm-9">
        {{ Form::file('attachments[]', ['multiple' => 'multiple', 'accept' => '.png,.gif,.jpg,.pdf,.msg,.doc,.docx,xls,xlsx']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.client_insureds_info') }}
    </label>
    <div class="col-sm-9">
        <textarea maxlength="5000" name="client_insureds_info" cols="55" row="2">{{ Input::old('client_insureds_info') }}</textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('processes.client_others_info') }}
    </label>
    <div class="col-sm-9">
        <textarea maxlength="5000" name="client_others_info" cols="55" row="2">{{ Input::old('client_others_info') }}</textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
    </label>
    <div class="col-sm-9">
        <button type="submit" class="btn btn-green pull-right">
            <i class="fa fa-check-square"></i> {{ trans('actions.confirm') }} 
        </button>
    </div>
</div>
{{ Form::close() }}

@stop
