@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
        TableData.static();
        PopOver.init();
        Modal.confirmListener();
    });
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="fa fa-tasks"></i> {{ trans('navigation.clients.processes') }}</li>
@stop

@section('title')
{{ trans('navigation.clients.processes') }}
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
        <a href="{{ route('clients.processes.create') }}" class="btn btn-green">
            <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('processes.singular')]) }}
        </a>
    </div>
</div>
<div class="tabbable">
<ul id="myTab" class="nav nav-tabs tab-blue">
    <li class="active">
        <a href="#panel_tab_pending" data-toggle="tab">
            <i class="green clip-clock-2"></i> {{ trans('status.pending') }}
        </a>
    </li>
    <li>
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
<div class="tab-pane active" id="panel_tab_pending">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-full-width static-table">
            <thead>
            <tr>
                <th>{{ trans('processes.reference') }}</th>
                <th>{{ trans('processes.email') }}</th>
                <th>{{ trans('processes.apolice') }}</th>
                <th class="visible-lg">{{ trans('processes.client_attachments') }}</th>
                <th>{{ trans('processes.client_insureds_info') }}</th>
                <th>{{ trans('processes.client_others_info') }}</th>
                <th>{{ trans('processes.status_id') }}</th>
            </tr>
            </thead>
            <tbod
            @foreach($pending as $p)
            <tr>
                <td>{{ $p->reference }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->apolice }}</td>
                <td class="visible-lg center">
                    <div class="btn-group">
                        <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                            <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu pull-right">
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadProcessAttach', ['id' => $p->processId]) }}">
                                    <img src="{{ $p->name }}" width="16" height="16" /> {{ $p->name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
                <td>{{ $p->client_insureds_info }}</td>
                <td>{{ $p->client_others_info }}</td>
                <td>{{ $p->status->name }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="tab-pane" id="panel_tab_processing">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-full-width static-table">
            <thead>
            <tr>
                <th>{{ trans('processes.certificate') }}</th>
                <th>{{ trans('processes.insured_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.taker_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.apolice') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.type_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.client_attachments') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($processing as $p)
            <tr id="element-remove-{{ $p->id }}">
                <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                @if(!is_null($p->insured))
                <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                @else
                <td></td>
                @endif
                @if(!is_null($p->taker))
                <td  class="hidden-sm hidden-xs"><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                @else
                <td  class="hidden-sm hidden-xs"></td>
                @endif
                <td class="hidden-sm hidden-xs">{{ $p->apolice }}</td>
                <td class="hidden-sm hidden-xs">{{ Helper::isNull($p->type) ? '' : $p->type->name }}</td>
                <td class="hidden-sm hidden-xs center">
                    <div class="btn-group">
                        <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                            <i class="clip-file"></i> {{ trans('processes.client_attachments') }} <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu pull-right">
                            @foreach($p->clientAttachs as $a)
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadClientAttach', ['id' => $a->id]) }}">
                                    <img src="{{ asset(Helper::getFileTypeImg(explode('.', $a->name)[1])) }}" width="16" height="16" /> {{ $a->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </td>
                <td class="center">
                    <a href="{{  route('processes.show', ['id' => $p->id]) }}" class="btn btn-xs btn-block btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.details') }}"><i class="clip-info-2"></i></a>
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
                <th>{{ trans('processes.insured_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.taker_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.apolice') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.type_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.client_attachments') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="complete-table-body">
            @foreach($complete as $p)
            <tr>
                <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                @if(!is_null($p->insured))
                <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                @else
                <td></td>
                @endif
                @if(!is_null($p->taker))
                <td class="hidden-sm hidden-xs"><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                @else
                <td class="hidden-sm hidden-xs"></td>
                @endif
                <td class="hidden-sm hidden-xs">{{ $p->apolice }}</td>
                <td class="hidden-sm hidden-xs">{{ Helper::isNull($p->type) ? '' : $p->type->code }}</td>
                <td class="hidden-sm hidden-xs center">
                    <div class="btn-group">
                        <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                            <i class="clip-file"></i> {{ trans('processes.client_attachments') }} <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu pull-right">
                            @foreach($p->clientAttachs as $a)
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadClientAttach', ['id' => $a->id]) }}">
                                    <img src="{{ asset(Helper::getFileTypeImg(explode('.', $a->name)[1])) }}" width="16" height="16" /> {{ $a->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </td>
                <td class="center">
                    <a href="{{  route('processes.show', ['id' => $p->id]) }}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.details') }}"><i class="clip-info-2"></i></a>
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
                <th>{{ trans('processes.insured_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.taker_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.apolice') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.type_id') }}</th>
                <th class="hidden-sm hidden-xs">{{ trans('processes.attachments') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cancelled as $p)
            <tr>
                <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                @if(!is_null($p->insured))
                <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
                @else
                <td></td>
                @endif
                @if(!is_null($p->taker))
                <td class="hidden-sm hidden-xs"><a data-title="{{ $p->taker->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="{{ $p->taker->nif .'<br>Ref.: '. $p->taker->reference }}" class="popover-style">{{ $p->taker->name }}</a></td>
                @else
                <td class="hidden-sm hidden-xs"></td>
                @endif
                <td class="hidden-sm hidden-xs">{{ $p->apolice }}</td>
                <td class="hidden-sm hidden-xs">{{ Helper::isNull($p->type) ? '' : $p->type->code }}</td>
                <td class="hidden-sm hidden-xs center">
                    <div class="btn-group">
                        <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                            <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu pull-right">
                            <?php if(!empty($p->attachs)) : ?>
                                @foreach($p->attachs as $a)
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadAttach', ['id' => $a->id]) }}">
                                        <img src="{{ asset(Helper::getFileTypeImg(explode('.', $a->name)[1])) }}" width="16" height="16" /> {{ $a->name }}
                                    </a>
                                </li>
                                @endforeach
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
                <td class="center">
                    <a href="{{  route('processes.show', ['id' => $p->id]) }}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.details') }}"><i class="clip-info-2"></i></a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</div>


@stop

