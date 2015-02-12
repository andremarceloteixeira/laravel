@extends('layout')

@section('breadcrumb')
<li><i class="fa fa-tasks"></i> <a href="{{ route($routeBack) }}">{{ $back }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.showing', ['type' => trans('processes.singular'), 'name' => $process->certificate]) }}</li>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        PopOver.init();
        Modal.confirmListener();

        $('.invoiceProcessAction').click(function() {
            $('#invoiceModalId').attr('value', $(this).data('id'));
            $('#invoiceModalTitle').text($(this).data('title'));
            $('#invoiceModal').modal('show');
        });

        $('.completeProcessAction').click(function() {
            $('#completeModalId').attr('value', $(this).data('id'));
            $('#completeModalTitle').text($(this).data('title'));
            $('#completeModal').modal('show');
        });

        $('.cancelProcessAction').click(function() {
            $('#cancelModalId').attr('value', $(this).data('id'));
            $('#cancelModalTitle').text($(this).data('title'));
            $('#cancelModal').modal('show');
        });

        $('#invoiceModalForm').on('submit', function() {
            var l = Ladda.create(document.getElementById('invoiceModalButton'));
            l.start();
        });

        $('#completeModalForm').on('submit', function() {
            var l = Ladda.create(document.getElementById('completeModalButton'));
            l.start();
        });

        $('#cancelModalForm').on('submit', function() {
            var l = Ladda.create(document.getElementById('cancelModalButton'));
            l.start();
        });

        $(document).on('click', '.chargeModalAction', function() {
            $('#chargeModalTitle').text($(this).data('title'));
            $('#chargeModal').modal('show');
        });

        $(document).on('click', '#chargeModalSubmit', function() {
            $.get('<?php echo route('pending.charge') ?>', {id: $('#chargeModalId').val(), expert_id: $('#chargeModalSelect').val()}, function(data) {
                $('#chargeModal').modal('hide');
                if (data['status'] == "success") {
                    Notification.success(data['title'], data['message']);
                    $('#remove-button-charge').remove();
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else if (data['status'] == "error") {
                    Notification.error(data['title'], data['message']);
                }
            });
        });
    });
</script>
@stop

@section('title')
{{ $back }} <small>{{ trans('actions.showing', ['type' => trans('processes.singular'), 'name' => $process->certificate]) }}</small>
@stop

@section('main')
@if(count($errors->all()) > 0)
<div class="alert alert-block alert-danger fade in">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <h4 class="alert-heading"><i class="fa fa-times-circle"></i> {{ trans('actions.error') }}!</h4>
    <p>{{ HTML::ul($errors->all()) }}</p>
</div>
@endif
<div class="col-lg-12 text-right">
    @if($process->status_id == 1)
    <button id="remove-button-charge" data-title="{{ trans('actions.charging', ['name' => $process->certificate, 'type' => trans('processes.singular')]) }}" class="btn btn-success tooltips chargeModalAction" data-placement="top" data-original-title="{{ trans('actions.charge') }}">
        <i class="clip-checkmark-2"></i>
    </button>
    @endif
    @if(Check::canManageProcess($process))
    <div class="btn-group">
        <a class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" href="#">
            <i class="fa fa-cog"></i> <span class="caret"></span>
        </a>
        <ul role="menu" class="dropdown-menu pull-right">
            @if(Check::canEditProcess($process))
            @if(Check::isAdmin())
            <li role="presentation">
                <a href="{{ route('processes.edit', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="fa fa-edit"></i> {{ trans('actions.update') }}
                </a>
            </li>
            @elseif(Check::isExpert())
            <li role="presentation">
                <a href="{{ route('experts.processes.edit', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="fa fa-edit"></i> {{ trans('actions.update') }}
                </a>
            </li>
            @endif
            @endif
            @if(Check::canUpgradeProcess($process))
            <li role="presentation">
                <a target="_blank" href="{{ route('processes.preliminar', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                </a>
            </li>
            @if($process->status_id == 2)
            <li role="presentation">
                <a class="confirm-btn cursor" data-type="async" data-title="{{ trans('actions.sending', ['name' => strtolower(trans('processes.singular')) . ' ' . $process->certificate, 'type' => trans('processes.preliminar_report')]) }}" data-body="{{ trans('actions.are_sure_send', ['name' => strtolower(trans('processes.singular')) . ' ' . $process->certificate, 'type' => strtolower(trans('processes.preliminar_report'))]) }}" data-url="{{ route('processes.sendPreliminar') }}" data-values='{"id":{{ $process->id }}}' data-method="GET" role="menuitem" tabindex="-1">
                    <i class="fa fa-mail-forward"></i> {{ trans('actions.send_preliminar') }}
                </a>
            </li>
            @endif
            <li role="presentation">
                <a target="_blank" href="{{ route('processes.survey', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $process->type->code]) }}
                </a>
            </li>
            <li role="presentation">
                <a href="{{ route('processes.downloadProcess', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </li>
            @if(Check::isAdmin())
            @if($process->status_id == 2)
            <li role="presentation">
                <a role="menuitem" tabindex="-1" data-title="{{ trans('actions.completing', ['type' => strtolower(trans('processes.singular')), 'name' => $process->certificate]) }}" data-id="{{ $process->id }}" class="cursor completeProcessAction">
                    <i class="fa fa-check fa fa-white"></i> {{ trans('actions.complete') }}
                </a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" data-title="{{ trans('actions.canceling', ['type' => strtolower(trans('processes.singular')), 'name' => $process->certificate]) }}" data-id="{{ $process->id }}" class="cursor cancelProcessAction">
                    <i class="fa fa-times fa fa-white"></i> {{ trans('actions.cancel') }}
                </a>
            </li>
            @else
            <li role="presentation">
                <?php $invoicing = Helper::isNull($process->invoice) ? 'actions.adding_invoice' : 'actions.replacing_invoice' ?>
                <a role="menuitem" tabindex="-1" data-title="{{ trans($invoicing, ['type' => strtolower(trans('processes.singular')), 'name' => $process->certificate]) }}" data-id="{{ $process->id }}" class="cursor invoiceProcessAction">
                    <i class="clip-file-2"></i> {{ Helper::isNull($process->invoice) ? trans('actions.add_invoice') : trans('actions.replace_invoice') }}
                </a>
            </li>
            @endif
            @endif
            @endif
        </ul>
    </div>
    @endif
</div>

@if(Check::canManageProcess($process))
<div class="col-lg-12" style="margin-top: 15px;">
    {{ Helper::processDeadlines($process) }}
</div>
@endif

<table class="table table-condensed table-hover">
    <thead>
        <tr>
            <th colspan="2">{{ trans('processes.singular') }} {{ $process->name }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ trans('processes.certificate') }}</b></td>
            <td>{{ $process->certificate }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.reference') }}</b></td>
            <td>{{ $process->reference }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.client_id') }}</b></td>
            <td><a data-title="{{ $process->client->name }}" data-image="{{ asset($process->client->photo) }}" data-subtitle="{{ $process->client->email }}" class="popover-style">{{ $process->client->name }}</a></td></td>
        </tr>
        @if($process->status_id != 1)
        <tr>
            <td><b>{{ trans('processes.insured_id') }}</b></td>
            <td>{{ Helper::isNull($process->insured) ? '' : $process->insured->name }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.taker_id') }}</b></td>
            <td>{{ Helper::isNull($process->taker) ? '' : $process->taker->name }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.expert_id') }}</b></td>
            @if(!is_null($process->expert)) 
            <td><a data-title="{{ $process->expert->name }}" data-image="{{ asset($process->expert->photo) }}" data-subtitle="{{ $process->expert->email }}<br>{{ $process->expert->function }}" class="popover-style">{{ $process->expert->name }}</a></td>
            @else
            <td></td>
            @endif
        </tr>
        @endif
        <tr>
            <td><b>{{ trans('processes.status_id') }}</b></td>
            <td>{{ $process->status->name }}</td>
        </tr>
        @if(!Check::isClient())
        <tr>
            <td><b>{{ trans('actions.begin_report') }}</b></td>
            <td>
                <a href="{{ route('processes.downloadBegin', $process->id) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </td>
        </tr>
        @endif
        @if($process->status_id == 3)
        @if(!Helper::isNull($process->complete_report))
        <tr>
            <td><b>{{ trans('processes.complete_report') }}</b></td>
            <td>
                <a href="{{ route('processes.downloadFinal', $process->id) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </td>
        </tr>
        @endif
        @if(!Helper::isNull($process->invoice))
        <tr>
            <td><b>{{ trans('processes.invoice') }}</b></td>
            <td>
                <a href="{{ route('processes.downloadInvoice', $process->id) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </td>
        </tr>
        @endif
        @if($process->preliminar_sent)
        <tr>
            <td><b>{{ trans('processes.preliminar_report') }}</b></td>
            <td>
                <a href="{{ route('processes.downloadPreliminar', $process->id) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </td>
        </tr>
        @endif
        @endif
        @if(!Check::isClient() && $process->status_id == 4)
        <tr>
            <td><b>{{ trans('processes.cancel_reason') }}</b></td>
            <td>{{ $process->cancel_reason }}</td>
        </tr>
        @endif
        <tr>
            <td><b>{{ trans('processes.email') }}</b></td>
            <td>{{ $process->email }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.apolice') }}</b></td>
            <td>{{ $process->apolice }}</td>
        </tr>
        @if($process->status_id != 1)
        <tr>
            <td><b>{{ trans('processes.type_id') }}</b></td>
            <td>{{ Helper::isNull($process->type) ? '' : $process->type->code . ' (' . $process->type->name . ')' }}</td>
        </tr>
        @endif
        @if(!Check::isClient() && $process->status_id > 1)
        <tr>
            <td><b>{{ trans('processes.deadline_preliminar') }}</b></td>
            <td>{{ $process->deadline_preliminar }} {{ trans_choice('carbon.day', $process->deadline_preliminar) }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.deadline_complete') }}</b></td>
            <td>{{ $process->deadline_complete }} {{ trans_choice('carbon.day', $process->deadline_complete) }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.preliminar_date') }}</b></td>
            <td>{{ $process->preliminar_date }}</td>
        </tr>
        @endif
        @if(!Check::isClient())
        <tr>
            <td><b>{{ trans('processes.process_attachments') }}</b></td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                        <i class="clip-file"></i> {{ trans('processes.process_attachments') }} <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu pull-right">
                        @foreach($process->processAttachs as $a)
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadProcessAttach', ['id' => $a->id]) }}">
                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </td>
        </tr>
        @endif
        <tr>
            <td><b>{{ trans('processes.client_attachments') }}</b></td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                        <i class="clip-file"></i> {{ trans('processes.client_button') }} <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu pull-right">
                        @foreach($process->clientAttachs as $a)
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadClientAttach', ['id' => $a->id]) }}">
                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </td>
        </tr>
        @if(!Check::isClient() && $process->status_id > 1)
        <tr>
            <td><b>{{ trans('processes.extra') }}</b></td>
            <td>
                <div class="btn-group">
                    <a class="btn btn-dropdown-toggle btn-sm btn-danger" data-toggle="dropdown" href="#">
                        <i class="fa fa-paperclip"></i> {{ trans('processes.extra') }} <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu pull-right">
                        @foreach($process->fields as $a)
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1">
                                <b>{{ $a->key }}</b>: {{ $a->value }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </td>
        </tr>
        @endif
        <tr>
            <td><b>{{ trans('processes.client_insureds_info') }}</b></td>
            <td>{{ $process->client_insureds_info }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.client_others_info') }}</b></td>
            <td>{{ $process->client_others_info }}</td>
        </tr>     
        @if($process->status_id != 1)
        <tr>
            <td><b>{{ trans('processes.situation_losts') }}</b></td>
            <td>{{ Helper::isNull($process->situation_losts) ? '' : 'Eur. ' . $process->situation_losts . ' €' }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.situation_observations') }}</b></td>
            <td>{{ $process->situation_observations }}</td>
        </tr>
        @if(!Check::isClient())
        <tr>
            <td><b>{{ trans('processes.created_at') }}</b></td>
            <td>{{ $process->created_at }} ({{ Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->diffForHumans() }})</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.updated_at') }}</b></td>
            <td>{{ $process->updated_at }} ({{ Carbon::createFromFormat('Y-m-d H:i:s', $process->updated_at)->diffForHumans() }})</td>
        </tr>
        @endif
        @endif

    </tbody>
</table>

<!-- start: INVOICE PROCESS MODAL -->
<div id="invoiceModal" class="modal fade" tabindex="-1" data-width="700" style="display: none;">
    <form id="invoiceModalForm" accept-charset="UTF-8" action="{{ route('processes.invoice') }}" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="invoiceModalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <input id="invoiceModalId" name="id" type="hidden" />
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.invoice') }} 
                </label>
                <div class="col-sm-7">
                    <input type="file" name="invoice" class="file-input">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button class="btn btn-blue btn-md ladda-button" data-style="expand-right" type="submit" id="invoiceModalButton">
                <span class="ladda-label"> {{ trans('actions.confirm') }} </span>
                <i class="fa fa-arrow-circle-right"></i>
                <span class="ladda-spinner"></span>
                <span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
            </button>
        </div>
    </form>
</div>
<!-- end: INVOICE PROCESS MODAL --> 

<!-- start: COMPLETE PROCESS MODAL -->
<div id="completeModal" class="modal fade" tabindex="-1" data-width="700" style="display: none;">
    <form id="completeModalForm" accept-charset="UTF-8" action="{{ route('processes.complete') }}" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="completeModalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <input id="completeModalId" name="id" type="hidden" />
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.complete_report') }} 
                </label>
                <div class="col-sm-7">
                    <input type="file" name="file" class="file-input">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.invoice') }} 
                </label>
                <div class="col-sm-7">
                    <input type="file" name="invoice" class="file-input">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button class="btn btn-blue btn-md ladda-button" data-style="expand-right" type="submit" id="completeModalButton">
                <span class="ladda-label"> {{ trans('actions.confirm') }} </span>
                <i class="fa fa-arrow-circle-right"></i>
                <span class="ladda-spinner"></span>
                <span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
            </button>
        </div>
    </form>
</div>
<!-- end: COMPLETE PROCESS MODAL --> 

<!-- start: CANCEL PROCESS MODAL -->
<div id="cancelModal" class="modal fade" tabindex="-1" data-width="700" style="display: none;">
    <form id="cancelModalForm" accept-charset="UTF-8" action="{{ route('processes.cancel') }}" method="POST" class="form-horizontal" role="form">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="cancelModalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <input id="cancelModalId" name="id" type="hidden" />
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.cancel_reason') }} 
                </label>
                <div class="col-sm-7">
                    <textarea maxlength="500" name="cancel_reason" cols="55" row="2"></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button class="btn btn-blue btn-md ladda-button" data-style="expand-right" type="submit" id="cancelModalButton">
                <span class="ladda-label"> {{ trans('actions.confirm') }} </span>
                <i class="fa fa-arrow-circle-right"></i>
                <span class="ladda-spinner"></span>
                <span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
            </button>
        </div>
    </form>
</div>
<!-- end: CANCEL PROCESS MODAL --> 

@if($process->status_id == 1)
<!-- start: CHARGE MODAL -->
<div id="chargeModal" class="modal fade" tabindex="-1"  data-width="700" style="display: none;">
    <form accept-charset="UTF-8" id="chargeModalForm" class="form-horizontal" role="form">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="chargeModalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <input id="chargeModalId" name="id" type="hidden" value="{{ $process->id }}" />
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.expert_id') }} 
                </label>
                <div class="col-sm-7">
                    {{ Form::select('expert_id', Expert::dropdown(), null, ['class' => 'form-control search-select', 'id' => 'chargeModalSelect']) }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button data-style="expand-right"  id="chargeModalSubmit" type="button" class="btn btn-blue btn-md ladda-button">
                <span class="ladda-label"> {{ trans('actions.confirm') }}  </span>
                <i class="fa fa-arrow-circle-right"></i>
                <span class="ladda-spinner"></span>
                <span class="ladda-spinner"></span>
            </button>
        </div>

    </form>
</div>
<!-- end: CHARGE MODAL --> 
@endif

@stop
