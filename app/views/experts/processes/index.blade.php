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
<li class="active"><i class="fa fa-tasks"></i> {{ trans('navigation.experts.processes') }}</li>
@stop

@section('title')
{{ trans('navigation.experts.processes') }}
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
                            <th>{{ trans('processes.apolice') }}</th>
                            <th class="visible-lg">{{ trans('processes.type_id') }}</th>
                            <th class="visible-md visible-lg">{{ trans('processes.deadlines') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processing as $p)
                        <tr id="element-remove-{{ $p->id }}">
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
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
                                            <a href="{{ route('experts.processes.edit', ['id' => $p->id]) }}" role="menuitem" tabindex="-1">
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
                            <th>{{ trans('processes.apolice') }}</th>
                            <th class="visible-lg">{{ trans('processes.type_id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="complete-table-body">
                        @foreach($complete as $p)
                        <tr>
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
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
                                        @if(Check::canUpgradeProcess($p))
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
                            <th>{{ trans('processes.apolice')}}</th>
                            <th class="visible-lg">{{ trans('processes.type_id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelled as $p)
                        <tr>
                            <td data-order="{{ $p->id }}">{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ asset($p->client->photo) }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->insured))
                            <td><a data-title="{{ $p->insured->name }}" data-image="{{ asset(Config::get('settings.photo_default')) }}" data-subtitle="Ref.: {{ $p->insured->reference }}" class="popover-style">{{ $p->insured->name }}</a></td>
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
                                        @if(Check::canUpgradeProcess($p))
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


@stop

