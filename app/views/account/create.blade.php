@extends('layout')

@section('breadcrumb')
<li>
    <i class="clip-home-3"></i>
    <a href="#">{{ trans('navigation.home') }}</a>
</li>
<li class="active">{{ trans('navigation.profile') }}</li>
@stop

@section('title')
<?php if(!empty($user)): ?>
{{ trans('navigation.profile') }} <small>{{ $user->name }}</small>
<?php endif; ?>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::model($user, ['route' => 'account.store', 'files' => true, 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="col-md-12 space20 z-index">
    <button type="submit" class="btn btn-green pull-right">
        <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
    </button>
</div>
<div class="tabbable">
    <ul id="myTab" class="nav nav-tabs tab-blue">
        <li class="active">
            <a href="#panel_tab_general" data-toggle="tab">
                <i class="green clip-info"></i> {{ trans('actions.general') }}
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_password" data-toggle="tab">
                <i class="green fa fa-key"></i> {{ trans('actions.password_change') }} 
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="panel_tab_general">

            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-1">
                    {{ trans('users.email') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('email', Input::old('email'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-2">
                    {{ trans('users.name') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        {{ Form::text('name', Input::old('name'), ['class' => 'form-control']) }}

                        <i class="fa fa-asterisk"></i> 
                    </span>
                </div>
            </div>

            @if(!Check::isClient(Auth::user()))
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-3" >
                    {{ trans('experts.function') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('function', Input::old('function'), ['class' => 'form-control']) }}
                </div>
            </div>
            @endif


            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('experts.birthday') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::date('birthday', Form::getValueAttribute('birthday')) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-5" >
                    {{ trans('users.photo') }}
                </label>
                <div class="col-sm-9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 125px; height: 125px;">
                            <?php if(!empty($user)): ?>
                            {{ HTML::image($user->photo) }}
                            <?php endif;?>
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 125px; max-height: 125px; line-height: 20px;"></div>
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
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_password">
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-3" >
                    {{ trans('users.current_password') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::password('current_password', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-3" >
                    {{ trans('users.new_password') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::password('password', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue" for="form-field-3" >
                    {{ trans('users.new_password_confirmation') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>



{{ Form::close() }}

@stop

