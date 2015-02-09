@extends('layout')

@section('css-required')
@stop

@section('js-required')
<script src="{{ asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js') }}"></script>
<script>
$(document).ready(function() {
    $(".currency").maskMoney();

    $('#add-field').click(function() {
        var rmv = $('<div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>');
        var f = $('#main-field').clone();
        f.removeAttr('id');
        f.show();
        f.append(rmv);
        $('#panel_tab_extra').append(f);
    });

    $(document).on('click', 'button.remove-btn', function() {
        $(this).closest('.row').remove();
    });

    Modal.confirmListener();
});
</script>
@stop

@section('breadcrumb')
<li><i class="fa fa-tasks"></i> <a href="{{ route('me.processes.index') }}">{{ trans('navigation.me.processes') }}</a></li>
<li class="active"><i class="clip-copy-3"></i> {{ trans('actions.update') . ' ' . trans('processes.singular') . ' ' . $process->certificate }} </li>
@stop

@section('title')
{{ trans('navigation.me.processes') }} <small>{{ trans('actions.update') . ' ' . trans('processes.singular') . ' ' . $process->certificate }} </small>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::model($process, ['route' => ['me.processes.update', $process->id], 'method' => 'PATCH', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="col-md-12 space20 z-index">
    <button type="" class="btn btn-green pull-right">
        <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
    </button>
</div>
<div class="tabbable">
    <ul id="myTab" class="nav nav-tabs tab-blue">
        <li class="active">
            <a href="#panel_tab_info" data-toggle="tab">
                <i class="green clip-info"></i> {{ trans('processes.info') }}
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_deadlines" data-toggle="tab">
                <i class="green clip-alarm"></i> {{ trans('processes.deadlines') }} 
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_situational" data-toggle="tab">
                <i class="green clip-user-5"></i> {{ trans('processes.situational') }} 
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_extra" data-toggle="tab">
                <i class="green fa fa-sitemap"></i> {{ trans('processes.extra') }} 
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="panel_tab_info">
             <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.client_id') }} 
                </label>
                <div class="col-sm-9">
                    {{ Form::select('client_id', Client::dropdown(), Input::old('client_id'), ['class' => 'form-control search-select']) }}
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
                    {{ Form::text('apolice', Input::old('apolice'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.insured_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::select('insured_id', Insured::dropdownInsured(), Input::old('insured_id'), ['class' => 'form-control search-select']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.taker_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::select('taker_id', Insured::dropdownTaker(), Input::old('taker_id'), ['class' => 'form-control search-select']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.type_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::select('type_id', Type::dropdown(), Input::old('type_id'), ['class' => 'form-control search-select']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.preliminar_date') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::date('preliminar_date', Input::old('preliminar_date')) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.attachments') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::file('attachments[]', ['multiple' => 'multiple']) }}
                    <div class="space10"></div>
                    @foreach($process->attachs as $f)
                    <p id="file-remove-{{$f->id}}"> 
                        <a href="{{ asset($f->path) }}" target="_blank">
                            <img src="{{ asset('assets/images/files/48/'.explode('.', $f->name)[1].'.png') }}" width="30" height="30" /> {{ $f->name }}
                        </a>
                        <button data-type="async" data-values='{"id": {{ $f->id }} }' data-remove-id="file-remove-{{$f->id}}" data-title="{{ trans('actions.deleting', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-url="{{ route('processes.deleteAttach') }}" data-method="GET" type="button" class="btn btn-bricky btn-xs remove-btn confirm-btn"><i class="fa fa-times fa fa-white"></i></button>
                    </p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_deadlines">
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.deadline_preliminar') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('deadline_preliminar', Input::old('deadline_preliminar'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.deadline_complete') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('deadline_complete', Input::old('deadline_complete'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_situational">
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.situation_date') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('situation_date', Input::old('situation_date'), ['class' => 'form-control', 'disabled' => 'disabled']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.situation_losts') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        {{ Form::text('situation_losts', Input::old('situation_losts'), ['class' => 'form-control currency']) }}
                        <i class="fa fa-eur"></i> 
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.situation_observations') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::textarea('situation_observations', Input::old('situation_observations'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_extra">
            <div class="col-md-12 space20">
                <button id="add-field" type="button" class="btn btn-green">
                    <i class="fa fa-plus"></i> {{ trans('actions.add') }} 
                </button>
            </div>
            <div id="main-field" class="row" style="display:none;">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-6 control-label label_blue">
                            {{ trans('processes.key') }}
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name='key[]' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-6 control-label label_blue">
                            {{ trans('processes.value') }}
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name='value[]' class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            @foreach($process->fields as $f)
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-6 control-label label_blue">
                            {{ trans('processes.key') }}
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name='key[]' value="{{ $f->key }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-6 control-label label_blue">
                            {{ trans('processes.value') }}
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name='value[]' value="{{ $f->value }}" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>
            </div>
            @endforeach
        </div>
    </div>
</div>
{{ Form::close() }}
@stop