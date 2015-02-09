@extends('layout')

@section('css-required')
@stop

@section('js-required')
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}"></script>
@stop

@section('breadcrumb')
<li><i class="clip-users"></i> <a href="{{ route('experts.index') }}">{{ trans('navigation.experts') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.update') . ' ' . trans('experts.singular') . ' ' . $expert->user->name }}</li>
@stop

@section('title')
{{ trans('navigation.experts') }} <small>{{ trans('actions.update') . ' ' . trans('experts.singular') . ' ' . $expert->user->name }} </small>
@stop

@section('main')

@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::model($expert, ['route' => ['experts.update', $expert->user_id], 'method' => 'PATCH', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('users.username') }}
    </label>
    <div class="col-sm-9">
        <span class="input-icon input-icon-right">
            {{ Form::text('username', Input::old('username'), ['class' => 'form-control']) }}
            <i class="fa fa-asterisk"></i> 
        </span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('users.name') }}
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
        {{ trans('users.email') }}
    </label>
    <div class="col-sm-9">
        {{ Form::text('email', Input::old('email'), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('experts.function') }}
    </label>
    <div class="col-sm-9">
        {{ Form::text('function', Input::old('function'), ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('experts.birthday') }}
    </label>
    <div class="col-sm-9">
        {{ Form::date('birthday', Form::getValueAttribute('birthday')) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label label_blue">
        {{ trans('users.photo') }}
    </label>
    <div class="col-sm-9">
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: 125px; height: 125px;">
                {{ HTML::image($expert->photo) }}
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 125px; max-height: 1250px; line-height: 20px;"></div>
            <div>
                <span class="btn btn-blue btn-file">
                    <span class="fileupload-new">
                        <i class="fa fa-picture-o"></i> {{ trans('actions.select') }}
                    </span><span class="fileupload-exists">
                        <i class="fa fa-picture-o"></i> {{ trans('actions.change') }}
                    </span>
                    {{ Form::file('photo') }}
                </span>
                <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                    <i class="fa fa-times"></i> {{ trans('actions.remove') }}
                </a>
            </div>
        </div>
        <div class="alert alert-info">
            <span class="label label-info">{{ strtoupper(trans('actions.note')) }}!</span>
            <span> {{ trans('notes.profile_image') }} </span>
        </div>
        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-8 pull-right">
                <button type="" class="btn btn-green pull-right">
                    <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
                </button>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}

@stop

