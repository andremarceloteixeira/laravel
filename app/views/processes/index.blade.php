@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
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

        TableData.static();
        PopOver.init();
        TextArea.init();
        Modal.confirmListener();
    });
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="fa fa-tasks"></i> {{ trans('navigation.processes') }}</li>
@stop

@section('title')
{{ trans('navigation.processes') }}
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
<div class="row">
    <div class="col-md-12 space20">
        <a href="{{ route('processes.create') }}" class="btn btn-green">
            <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('processes.singular')]) }} 
        </a>
    </div>
</div>

<div class="tabbable">
    <ul id="myTab" class="nav nav-tabs tab-blue">
        <li class="active">
            <a href="#panel_tab_processing" data-toggle="tab">
                <i class="green clip-settings"></i> {{ trans('status.processing') }}
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_complete" data-toggle="tab">
                <i class="green clip-checkmark-circle"></i> {{ trans('status.completed') }} 
            </a>
        </li>
        <li class="">
            <a href="#panel_tab_cancelled" data-toggle="tab">
                <i class="green clip-cancel-circle"></i> {{ trans('status.cancelled') }} 
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="panel_tab_processing">
            <div class="table-responsive">               
                <table class="table table-striped table-bordered table-hover table-full-width static-table">
                    <thead>
                        <tr>
                            <th>{{ trans('processes.certificate') }}</th>
                            <th>{{ trans('processes.client_id') }}</th>
                            <th>{{ trans('processes.expert_id') }}</th>
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th>{{ trans('processes.apolice') }}</th>
                            <th class="visible-lg">{{ trans('processes.type_id') }}</th>
                            <th class="visible-md visible-lg">{{ trans('processes.deadlines') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processing as $p)
                        <tr>
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->expert)) 
                            <td><a data-title="{{ $p->expert->name }}" data-image="{{ asset($p->expert->photo) }}" data-subtitle="{{ $p->expert->email }}<br>{{ $p->expert->function }}" class="popover-style">{{ $p->expert->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $p->apolice }}</td>
                            <td class="visible-lg">{{ Helper::isNull($p->type) ? '' : $p->type->code }}</td>
                            <td class="visible-md visible-lg text-center">
                                {{ Helper::processDeadlinesTooltips($p) }}
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.edit', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="fa fa-edit"></i> {{ trans('actions.update') }}
                                            </a>
                                        </li>
                                        @if(Check::canUpgradeProcess($p))
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.preliminar', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a class="confirm-btn cursor" data-type="async" data-title="{{ trans('actions.sending', ['name' => strtolower(trans('processes.singular')) . ' ' . $p->certificate, 'type' => trans('processes.preliminar_report')]) }}" data-body="{{ trans('actions.are_sure_send', ['name' => strtolower(trans('processes.singular')) . ' ' . $p->certificate, 'type' => strtolower(trans('processes.preliminar_report'))]) }}" data-url="{{ route('processes.sendPreliminar') }}" data-values='{"id":{{ $p->id }}}' data-method="GET" role="menuitem" tabindex="-1">
                                                <i class="fa fa-mail-forward"></i> {{ trans('actions.send_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.survey', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->code]) }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.downloadProcess', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" data-title="{{ trans('actions.completing', ['type' => strtolower(trans('processes.singular')), 'name' => $p->certificate]) }}" data-id="{{ $p->id }}" class="cursor completeProcessAction">
                                                <i class="fa fa-check fa fa-white"></i> {{ trans('actions.complete') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" data-title="{{ trans('actions.canceling', ['type' => strtolower(trans('processes.singular')), 'name' => $p->certificate]) }}" data-id="{{ $p->id }}" class="cursor cancelProcessAction">
                                                <i class="fa fa-times fa fa-white"></i> {{ trans('actions.cancel') }}
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_complete">
            <div class="table-responsive">               
                <table class="table table-striped table-bordered table-hover table-full-width static-table">
                    <thead>
                        <tr>
                            <th>{{ trans('processes.certificate') }}</th>
                            <th>{{ trans('processes.client_id') }}</th>
                            <th>{{ trans('processes.expert_id') }}</th>
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th>{{ trans('processes.apolice') }}</th>
                            <th class="visible-lg ">{{ trans('processes.type_id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="complete-table-body">
                        @foreach($complete as $p)
                        <tr>
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->expert)) 
                            <td><a data-title="{{ $p->expert->name }}" data-image="{{ asset($p->expert->photo) }}" data-subtitle="{{ $p->expert->email }}<br>{{ $p->expert->function }}" class="popover-style">{{ $p->expert->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $p->apolice }}</td>
                            <td class="visible-lg ">{{ Helper::isNull($p->type) ? '' : $p->type->code }}</td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <?php $invoicing = Helper::isNull($p->invoice) ? 'actions.adding_invoice' : 'actions.replacing_invoice' ?>
                                            <a role="menuitem" tabindex="-1" data-title="{{ trans($invoicing, ['type' => strtolower(trans('processes.singular')), 'name' => $p->certificate]) }}" data-id="{{ $p->id }}" class="cursor invoiceProcessAction">
                                                <i class="clip-file-2"></i> {{ Helper::isNull($p->invoice) ? trans('actions.add_invoice') : trans('actions.replace_invoice') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.preliminar', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.survey', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->code]) }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.downloadProcess', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="panel_tab_cancelled">
            <div class="table-responsive">               
                <table class="table table-striped table-bordered table-hover table-full-width static-table">
                    <thead>
                        <tr>
                            <th>{{ trans('processes.certificate') }}</th>
                            <th>{{ trans('processes.client_id') }}</th>
                            <th>{{ trans('processes.expert_id') }}</th>
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th>{{ trans('processes.apolice') }}</th>
                            <th class="visible-lg ">{{ trans('processes.type_id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelled as $p)
                        <tr>
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->expert)) 
                            <td><a data-title="{{ $p->expert->name }}" data-image="{{ asset($p->expert->photo) }}" data-subtitle="{{ $p->expert->email }}<br>{{ $p->expert->function }}" class="popover-style">{{ $p->expert->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $p->apolice }}</td>
                            <td class="visible-lg">{{ Helper::isNull($p->type) ? '' : $p->type->code }}</td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.preliminar', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.survey', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->code]) }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.downloadProcess', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


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

@stop

