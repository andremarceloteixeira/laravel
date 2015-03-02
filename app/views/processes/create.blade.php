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

    $("#insuredSearch").autocomplete({
        source: function(request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(<?php echo Insured::dropdownInsured() ?>, function(value) {
                return matcher.test(value.searchable);
            }));
        }
    });

    $("#takerSearch").autocomplete({
        source: function(request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response($.grep(<?php echo Insured::dropdownTaker() ?>, function(value) {
                return matcher.test(value.searchable);
            }));
        }
    });
});
</script>
@stop

@section('breadcrumb')
<li><i class="fa fa-tasks"></i> <a href="{{ route('processes.index') }}">{{ trans('navigation.processes') }}</a></li>
<li class="active"><i class="clip-copy-3"></i> {{ trans('actions.create', ['type' => trans('processes.singular')]) }}</li>
@stop

@section('title')
{{ trans('navigation.processes') }} <small>{{ trans('actions.create', ['type' => trans('processes.singular')]) }}</small>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::open(['route' => 'processes.store', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="col-md-12 space20 z-index">
    <button type="submit" class="btn btn-green pull-right">
        <i class="fa fa-check-square"></i> {{ trans('actions.confirm') }}
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
                    {{ trans('processes.certificate') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        <?php if(!$reference) { ?>
                            {{ Form::text('certificate', Input::old('certificate'), ['class' => 'form-control']) }}
                            <i class="fa fa-asterisk"></i>
                        <?php } else { ?>
                            <label class="col-sm-1 control-label label_blue">
                                {{ $reference  }}
                            </label>
                            <input type="hidden" name="certificate" value="<?php echo $reference; ?>"/>
                        <?php } ?>
                    </span>
                </div>
            </div>
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
                    {{ trans('processes.expert_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::select('expert_id', Expert::dropdown(), Input::old('expert_id'), ['class' => 'form-control search-select']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.reference') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('reference', Input::old('reference'), ['class' => 'form-control']) }}
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
                    {{ Form::text('insured_id', Input::old('insured_id'), ['class' => 'form-control', 'id' => 'insuredSearch']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.taker_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('taker_id', Input::old('taker_id'), ['class' => 'form-control', 'id' => 'takerSearch']) }}
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
                    {{ trans('processes.process_attachments') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::file('process_attachments[]', ['multiple' => 'multiple', 'accept' => '.png,.gif,.jpg,.pdf,.msg,.doc,.docx,.xls,.xlsx']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.client_attachments') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::file('client_attachments[]', ['multiple' => 'multiple', 'accept' => '.png,.gif,.jpg,.pdf,.msg,.doc,.docx,.xls,.xlsx']) }}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_deadlines">
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.deadline_preliminar') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('deadline_preliminar', Helper::isNull(Input::old('deadline_preliminar')) ? Config::get('settings.deadline_preliminar') : Input::old('deadline_preliminar'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.deadline_complete') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('deadline_complete', Helper::isNull(Input::old('deadline_complete')) ? Config::get('settings.deadline_complete') : Input::old('deadline_complete'), ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_situational">
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
            <div class="row">
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
        </div>
    </div>
</div>
{{ Form::close() }}
@stop