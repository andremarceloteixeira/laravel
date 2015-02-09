@extends('layout')

@section('breadcrumb')
<li><i class="fa fa-tasks"></i> <a href="{{ route('me.processes.index') }}">{{ trans('navigation.me.processes') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.showing', ['type' => trans('processes.singular'), 'name' => $process->name]) }}</li>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        Modal.confirmListener();
    });
</script>
@stop

@section('title')
{{ trans('navigation.me.processes') }} <small>{{ trans('actions.showing', ['type' => trans('processes.singular'), 'name' => $process->name]) }}</small>
@stop

@section('main')
<div class="col-lg-12 text-right">
    <div class="btn-group">
        <a class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" href="#">
            <i class="fa fa-cog"></i> <span class="caret"></span>
        </a>
        @if(Check::canManageProcess($process))
        <ul role="menu" class="dropdown-menu pull-right">
            @if(Check::canEditProcess($process))
            <li role="presentation">
                <a href="{{ route('me.processes.edit', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="fa fa-edit"></i> {{ trans('actions.update') }}
                </a>
            </li>
            @endif
            @if(Check::canUpgradeProcess($process))
            <li role="presentation">
                <a target="_blank" href="{{ route('processes.preliminar', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-copy"></i> {{ trans('actions.view_preliminar') }}
                </a>
            </li>
            <li role="presentation">
                <a class="confirm-btn cursor" data-type="async" data-title="{{ trans('actions.sending', ['name' => strtolower(trans('processes.singular')) . ' ' . $process->certificate, 'type' => trans('processes.preliminar_report')]) }}" data-body="{{ trans('actions.are_sure_send', ['name' => strtolower(trans('processes.singular')) . ' ' . $process->certificate, 'type' => strtolower(trans('processes.preliminar_report'))]) }}" data-url="{{ route('processes.sendPreliminar') }}" data-values='{"id":{{ $process->id }}}' data-method="GET" role="menuitem" tabindex="-1">
                    <i class="fa fa-mail-forward"></i> {{ trans('actions.send_preliminar') }}
                </a>
            </li>
            <li role="presentation">
                <a target="_blank" href="{{ route('processes.survey', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-copy"></i> {{ trans('actions.view_survey', ['name' => $process->type->title]) }}
                </a>
            </li>
            <li role="presentation">
                <a href="{{ route('processes.downloadProcess', ['id' => $process->id]) }}" role="menuitem" tabindex="-1">
                    <i class="clip-folder-download"></i> {{ trans('actions.download') }}
                </a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" data-type="sync" data-title="{{ trans('actions.completing', ['name' => $process->certificate, 'type' => strtolower(trans('processes.singular'))]) }}" data-body="{{ trans('actions.are_sure_complete', ['name' => $process->certificate, 'type' => strtolower(trans('processes.singular'))]) }}" data-url="{{ route('processes.complete', ['id' => $process->id]) }}" data-method="GET" class="confirm-btn cursor">
                    <i class="fa fa-check fa fa-white"></i> {{ trans('actions.complete') }}
                </a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" data-type="sync" data-title="{{ trans('actions.canceling', ['name' => $process->certificate, 'type' => strtolower(trans('processes.singular'))]) }}" data-body="{{ trans('actions.are_sure_cancel', ['name' => $process->certificate, 'type' => strtolower(trans('processes.singular'))]) }}" data-url="{{ route('processes.cancel', ['id' => $process->id]) }}" data-method="GET" class="confirm-btn cursor">
                    <i class="fa fa-times fa fa-white"></i> {{ trans('actions.cancel') }}
                </a>
            </li>
            @endif
        </ul>
        @endif
    </div>
</div>

<div class="col-lg-12" style="margin-top: 15px;">
{{ Helper::processDeadlines($process) }}
</div>

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
            <td><b>{{ trans('processes.client_id') }}</b></td>
            <td>{{ $process->client->name }}</td>
        </tr>
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
            <td>{{ Helper::isNull($process->expert) ? '' : $process->expert->name }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.status_id') }}</b></td>
            <td>{{ $process->status->name }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.email') }}</b></td>
            <td>{{ $process->email }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.apolice') }}</b></td>
            <td>{{ $process->apolice }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.type_id') }}</b></td>
            <td>{{ Helper::isNull($process->type) ? '' : $process->type->title }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.deadline_preliminar') }}</b></td>
            <td>{{ $process->deadline_preliminar }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.deadline_complete') }}</b></td>
            <td>{{ $process->deadline_complete }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.preliminar_date') }}</b></td>
            <td>{{ $process->preliminar_date }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.attachments') }}</b></td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#">
                        <i class="clip-file"></i> {{ trans('processes.attachments') }} <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu pull-right">
                        @foreach($process->attachs as $a)
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ route('processes.downloadAttach', ['id' => $a->id]) }}">
                                <img src="{{ asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') }}" width="16" height="16" /> {{ $a->name }} 
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </td>
        </tr>
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
        <tr>
            <td><b>{{ trans('processes.situation_losts') }}</b></td>
            <td>{{ $process->situation_losts }}€</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.situation_observations') }}</b></td>
            <td>{{ $process->situation_observations }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.created_at') }}</b></td>
            <td>{{ Carbon::createFromFormat('Y-m-s H:i:s', $process->created_at)->diffForHumans() }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('processes.updated_at') }}</b></td>
            <td>{{ Carbon::createFromFormat('Y-m-s H:i:s', $process->updated_at)->diffForHumans() }}</td>
        </tr>
    </tbody>
</table>

@stop
