@extends('layout')

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
<li class="active"><i class="clip-copy-3"></i> {{ trans('actions.update') . ' ' . trans('processes.singular') . ' ' . $process->certificate }} </li>
@stop

@section('title')
{{ trans('navigation.processes') }} <small>{{ trans('actions.update') . ' ' . trans('processes.singular') . ' ' . $process->certificate }} </small>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
{{ Form::model($process, ['route' => ['processes.update', $process->id], 'method' => 'PATCH', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form']) }}
<div class="col-md-12 space20 z-index hidden-xs">
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
                    {{ trans('processes.certificate') }}
                </label>
                <div class="col-sm-9">
                    <span class="input-icon input-icon-right">
                        <?php if(!$process->certificate) { ?>
                            {{ Form::text('certificate', Input::old('certificate'), ['class' => 'form-control']) }}
                            <i class="fa fa-asterisk"></i>
                        <?php } else { ?>
                            <label class="col-sm-1 control-label label_blue">
                                {{ $process->certificate  }}
                            </label>
                            <input type="hidden" name="certificate" value="<?php echo $process->certificate; ?>"/>
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
                    {{ Form::text('insured_id', Helper::isNull($process->insured) ? '' : $process->insured->name, ['class' => 'form-control', 'id' => 'insuredSearch']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.taker_id') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::text('taker_id', Helper::isNull($process->taker) ? '' : $process->taker->name, ['class' => 'form-control', 'id' => 'takerSearch']) }}
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
                    {{ Form::date('preliminar_date', $process->preliminar_date) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                </label>
                <div class="col-sm-9">
                    <label class="checkbox-inline control-label label_blue">
                        {{ Form::checkbox('preliminar_sent', null, $process->preliminar_sent, ['class' => 'grey']) }}
                        Relatório Preliminar enviado?
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.process_attachments') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::file('process_attachments[]', ['multiple' => 'multiple', 'accept' => '.png,.gif,.jpg,.pdf,.msg,.doc,.docx,.xls,.xlsx']) }}
                    <div class="space10"></div>
                    <button type="" class="btn btn-green visible-xs">
                        <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
                    </button>
                    <div class="space10"></div>
                    @foreach($process->processAttachs as $f)
                    <p id="file-remove-{{$f->id}}"> 
                        <a href="{{ route('processes.downloadProcessAttach', $f->id) }}" target="_blank">
                            <img src="{{ asset(Helper::getFileTypeImg(explode('.', $f->name)[1])) }}" width="30" height="30" /> {{ $f->name }}
                        </a>
                        <button data-type="async" data-values='{"id": {{ $f->id }} }' data-remove-id="file-remove-{{$f->id}}" data-title="{{ trans('actions.deleting', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-url="{{ route('processes.deleteProcessAttach') }}" data-method="GET" type="button" class="btn btn-bricky btn-xs remove-btn confirm-btn"><i class="fa fa-times fa fa-white"></i></button>
                    </p>
                    @endforeach
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.client_attachments') }}
                </label>
                <div class="col-sm-9">
                    {{ Form::file('client_attachments[]', ['multiple' => 'multiple', 'accept' => '.png,.gif,.jpg,.pdf,.msg,.doc,.docx,.xls,.xlsx']) }}
                    <div class="space10"></div>
                    <button type="" class="btn btn-green visible-xs">
                        <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
                    </button>
                    <div class="space10"></div>
                    @foreach($process->clientAttachs as $f)
                    <p id="file-remove-{{$f->id}}"> 
                        <a href="{{ route('processes.downloadClientAttach', $f->id) }}" target="_blank">
                            <img src="{{ asset(Helper::getFileTypeImg(explode('.', $f->name)[1])) }}" width="30" height="30" /> {{ $f->name }}
                        </a>
                        <button data-type="async" data-values='{"id": {{ $f->id }} }' data-remove-id="file-remove-{{$f->id}}" data-title="{{ trans('actions.deleting', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $f->name, 'type' => 'ficheiro']) }}" data-url="{{ route('processes.deleteClientAttach') }}" data-method="GET" type="button" class="btn btn-bricky btn-xs remove-btn confirm-btn"><i class="fa fa-times fa fa-white"></i></button>
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
            <button type="" class="btn btn-green visible-xs">
                <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
            </button>
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
            <button type="" class="btn btn-green visible-xs">
                <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
            </button>         
        </div>
        <div class="tab-pane" id="panel_tab_extra" style="margin-bottom: 30px;">
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
            <button type="" class="btn btn-green visible-xs">
                <i class="fa fa-check-square"></i> {{ trans('actions.save') }} 
            </button>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop