@extends('layout')

@section('breadcrumb')
<li><i class="clip-tree"></i> <a href="{{ route('types.index') }}">{{ trans('navigation.types') }}</a></li>
<li class="active"><i class="clip-tree-2"></i> {{ trans('actions.create', ['type' => trans('types.singular')]) }}</li>
@stop

@section('title')
{{ trans('navigation.types') }} <small>{{ trans('actions.create', ['type' => trans('types.singular')]) }}</small>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        $('#add-field').click(function() {
            var rmv = $('<div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>');
            var f = $('#main-field').clone();
            f.removeAttr('id');
            f.show();
            f.append(rmv);
            $('#fields-section').append(f);
        });

        $('#add-checkfield').click(function() {
            var rmv = $('<div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>');
            var f = $('#main-checkfield').clone();
            f.removeAttr('id');
            f.show();
            f.append(rmv);
            $('#checkfields-section').append(f);
        });


        $(document).on('click', 'button.remove-btn', function() {
            $(this).closest('.row').remove();
        });

        TextArea.init();
    });
</script>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::open(['route' => 'types.store', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="col-md-12 space20 z-index">
    <button type="" class="btn btn-green pull-right">
        <i class="fa fa-check-square"></i> {{ trans('actions.confirm') }} 
    </button>
</div>

<div class="tabbable">
    <ul id="myTab" class="nav nav-tabs tab-blue">
        <li class="active">
            <a href="#panel_tab_info" data-toggle="tab">
                <i class="green clip-info"></i> {{ trans('types.info') }}
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_fields" data-toggle="tab">
                <i class="clip-keyboard-2"></i> {{ trans('types.fields') }} 
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_checkfields" data-toggle="tab">
                <i class="clip-checkbox-checked"></i> {{ trans('types.checkfields') }} 
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="panel_tab_info">
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('types.code') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        {{ Form::text('code', Input::old('code'), ['class' => 'form-control']) }}
                        <i class="fa fa-asterisk"></i> 
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('types.name') }}
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
                    {{ trans('types.title') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        {{ Form::text('title', Input::old('title'), ['class' => 'form-control']) }}
                        <i class="fa fa-asterisk"></i> 
                    </span>
                    <span class="help-block"><i class="fa fa-info-circle"></i> {{ trans('types.note_1') }}</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('types.first_notes') }}
                </label>
                <div class="col-sm-9">
                    <textarea maxlength="255" name="first_notes" cols="55" row="2">{{ Input::old('first_notes') }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('types.last_notes') }}
                </label>
                <div class="col-sm-9">
                    <textarea maxlength="255" name="last_notes" cols="55" row="2">{{ Input::old('last_notes') }}</textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_fields">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button id="add-field" type="button" class="btn btn-green">
                        <i class="fa fa-plus"></i> {{ trans('actions.add') }} 
                    </button>
                </div>
            </div>
            <div id="fields-section">
                <div id="main-field" class="row" style="display:none;">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="fields[]" />
                            </div>
                        </div>
                    </div>
                </div>
                @if(!is_null(Input::old('fields')))
                @foreach(Input::old('fields') as $v)
                @if($v!="")
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" value="{{ $v }}" name="fields[]" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>
                </div>
                @endif
                @endforeach
                @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="fields[]" />
                            </div>
                        </div>
                    </div>
                </div> 
                @endif
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_checkfields">
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button id="add-checkfield" type="button" class="btn btn-green">
                        <i class="fa fa-plus"></i> {{ trans('actions.add') }} 
                    </button>
                </div>
            </div>
            <div id="checkfields-section">
                <div id="main-checkfield" class="row" style="display:none;">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" maxlength="500" rows="2" name="checkboxes[]"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!is_null(Input::old('checkboxes')))
                @foreach(Input::old('checkboxes') as $v)
                @if($v!="")
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" maxlength="500" rows="2" name="checkboxes[]">{{ $v }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"><button type="button" class="btn btn-bricky remove-btn"><i class="fa fa-times fa fa-white"></i></button></div>
                </div>
                @endif
                @endforeach
                @else
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-sm-3 control-label label_blue">
                                {{ trans('types.value') }}
                            </label>
                            <div class="col-sm-6">
                                <textarea class="form-control" maxlength="500" rows="2" name="checkboxes[]"></textarea>
                            </div>
                        </div>
                    </div>
                </div> 
                @endif
            </div>
        </div>
    </div>
</div>

{{ Form::close() }}

@stop

