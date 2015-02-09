@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
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


        TableData.static();
        PopOver.init();
        Modal.confirmListener();
    });
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="fa fa-tasks"></i> {{ trans('navigation.me.processes') }}</li>
@stop

@section('title')
{{ trans('navigation.me.processes') }}
@stop

@section('main')
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
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.taker_id') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.apolice') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.type_id') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.preliminar_date') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.attachments') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.extra') }}</th>
                            <th>{{ trans('processes.deadlines') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processing as $p)
                        <tr id="element-remove-{{ $p->id }}">
                            <td>{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ $p->client->photo }}" data-subtitle="Ref.: {{ $p->client->reference }}" class="popover-style">{{ $p->client->name }} (Ref. {{ $p->client->reference }})</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->taker))
                            <td  class="hidden-sm hidden-xs"><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                            @else
                            <td  class="hidden-sm hidden-xs"></td>
                            @endif
                            <td class="hidden-sm hidden-xs">{{ $p->apolice }}</td>
                            <td class="hidden-sm hidden-xs">{{ Helper::isNull($p->type) ? '' : $p->type->title }}</td>
                            <td class="hidden-sm hidden-xs">{{ $p->preliminar_date }}</td>
                            <td class="hidden-sm hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                                        <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->attachs as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadAttach', ['id' => $a->id]) }}">
                                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="hidden-sm hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-danger" data-toggle="dropdown" href="#">
                                        <i class="fa fa-paperclip"></i> {{ trans('processes.extra') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->fields as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1">
                                                <b>{{ $a->key }}</b>: {{ $a->value }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center">
                                {{ Helper::processDeadlinesTooltips($p) }}
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('me.processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('me.processes.edit', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
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
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->title]) }}
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
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th>{{ trans('processes.taker_id') }}</th>
                            <th>{{ trans('processes.apolice') }}</th>
                            <th>{{ trans('processes.type_id') }}</th>
                            <th class="hidden-xs">{{ trans('processes.preliminar_date') }}</th>
                            <th class="hidden-xs">{{ trans('processes.attachments') }}</th>
                            <th class="hidden-xs">{{ trans('processes.extra') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="complete-table-body">
                        @foreach($complete as $p)
                        <tr>
                            <td>{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ $p->client->photo }}" data-subtitle="Ref.: {{ $p->client->reference }}" class="popover-style">{{ $p->client->name }} (Ref. {{ $p->client->reference }})</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->taker))
                            <td><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $p->apolice }}</td>
                            <td>{{ Helper::isNull($p->type) ? '' : $p->type->title }}</td>
                            <td class="hidden-xs">{{ $p->preliminar_date }}</td>
                            <td class="hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                                        <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->attachs as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadAttach', ['id' => $a->id]) }}">
                                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-danger" data-toggle="dropdown" href="#">
                                        <i class="fa fa-paperclip"></i> {{ trans('processes.extra') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->fields as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1">
                                                <b>{{ $a->key }}</b>: {{ $a->value }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('me.processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        @if(Check::canUpgradeProcess($p))
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.preliminar', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.survey', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->title]) }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.downloadProcess', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-folder-download"></i> {{ trans('actions.download') }}
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
        <div class="tab-pane" id="panel_tab_cancelled">
            <div class="table-responsive">               
                <table class="table table-striped table-bordered table-hover table-full-width static-table">
                    <thead>
                        <tr>
                            <th>{{ trans('processes.certificate') }}</th>
                            <th>{{ trans('processes.client_id') }}</th>
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th>{{ trans('processes.taker_id') }}</th>
                            <th>{{ trans('processes.apolice') }}</th>
                            <th>{{ trans('processes.type_id') }}</th>
                            <th class="hidden-xs">{{ trans('processes.preliminar_date') }}</th>
                            <th class="hidden-xs">{{ trans('processes.attachments') }}</th>
                            <th class="hidden-xs">{{ trans('processes.extra') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelled as $p)
                        <tr>
                            <td>{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ $p->client->photo }}" data-subtitle="Ref.: {{ $p->client->reference }}" class="popover-style">{{ $p->client->name }} (Ref. {{ $p->client->reference }})</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->insured->nif .'<br>Ref.: '. $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            @if(!is_null($p->taker))
                            <td><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                            @else
                            <td></td>
                            @endif
                            <td>{{ $p->apolice }}</td>
                            <td>{{ Helper::isNull($p->type) ? '' : $p->type->title }}</td>
                            <td class="hidden-xs">{{ $p->preliminar_date }}</td>
                            <td class="hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                                        <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->attachs as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadAttach', ['id' => $a->id]) }}">
                                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="hidden-xs center">
                                <div class="btn-group">
                                    <a class="btn btn-dropdown-toggle btn-sm btn-danger" data-toggle="dropdown" href="#">
                                        <i class="fa fa-paperclip"></i> {{ trans('processes.extra') }} <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        @foreach($p->fields as $a)
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1">
                                                <b>{{ $a->key }}</b>: {{ $a->value }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#">
                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                    </a>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li role="presentation">
                                            <a href="{{ route('me.processes.show', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-eye"></i> {{ trans('actions.details') }}
                                            </a>
                                        </li>
                                        @if(Check::canUpgradeProcess($p))
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.preliminar', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a target="_blank" href="{{ route('processes.survey', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $p->type->title]) }}
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="{{ route('processes.downloadProcess', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
                                                <i class="clip-folder-download"></i> {{ trans('actions.download') }}
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
    </div>
</div>

<!-- start: COMPLETE PROCESS MODAL -->
<div id="completeModal" class="modal fade" tabindex="-1" data-width="700" style="display: none;">
    <form accept-charset="UTF-8" action="{{ route('processes.complete') }}" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
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
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-group">
                            <div class="form-control uneditable-input">
                                <i class="fa fa-file fileupload-exists"></i>
                                <span class="fileupload-preview"></span>
                            </div>
                            <div class="input-group-btn">
                                <div class="btn btn-blue btn-file">
                                    <span class="fileupload-new"><i class="fa fa-folder-open-o"></i>  {{ trans('actions.select') }}</span>
                                    <span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> {{ trans('actions.change') }}</span>
                                    <input type="file" name="file" class="file-input">
                                </div>
                                <a href="#" class="btn btn-blue fileupload-exists" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i> {{ trans('actions.remove') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button class="btn btn-blue btn-md ladda-button" data-style="expand-right" type="submit">
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
    <form accept-charset="UTF-8" action="{{ route('processes.cancel') }}" method="POST" class="form-horizontal" role="form">
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
            <button class="btn btn-blue btn-md ladda-button" data-style="expand-right" type="submit">
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

