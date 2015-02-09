@extends('layout')

@section('js-required')
<script src="{{ asset('assets/plugins/Chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.sparkline/jquery.sparkline.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
<script>
$(document).ready(function() {
   PopOver.init();
   Modal.confirmListener();
   Plot.init('<?php echo route('dashboard.expertsProcesses'); ?>');
});
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="fa fa-dashboard"></i> {{ trans('navigation.dashboard') }}</li>
@stop

@section('title')
{{ trans('navigation.dashboard') }} 
@stop

@section('main')
<div class="row">
    <div class="col-sm-3 text-center">
        <div class="core-box">
            <div class="heading">
                <i class="clip-clock-2 circle-icon circle-yellow"></i>
                <h2>{{ $pending }} {{ trans('status.pending') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-sm-3  text-center">
        <div class="core-box">
            <div class="heading">
                <i class="clip-settings circle-icon circle-info"></i>
                <h2>{{ $processing }} {{ trans('status.processing') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-sm-3  text-center">
        <div class="core-box">
            <div class="heading">
                <i class="clip-checkmark-2 circle-icon circle-green"></i>
                <h2>{{ $completed }} {{ trans('status.completed') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-sm-3  text-center">
        <div class="core-box">
            <div class="heading">
                <i class="clip-close circle-icon circle-bricky"></i>
                <h2>{{ $cancelled }} {{ trans('status.cancelled') }}</h2>
            </div>
        </div>
    </div>
</div>
@if(count($processes_deadlines) > 0)
<div class="row">
    <div class="col-md-12 text-center">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="clip-alarm"></i>
                Processos com Atrasos
                <div class="panel-tools">
                    <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                    </a>
                </div>
            </div>
            <div class="panel-body panel-scroll" style="height:400px">
                <table class="table table-striped table-hoverstatic-table">
                    <thead>
                        <tr>
                            <th>{{ trans('processes.certificate') }}</th>
                            <th>{{ trans('processes.client_id') }}</th>
                            <th>{{ trans('processes.expert_id') }}</th>
                            <th>{{ trans('processes.insured_id') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.taker_id') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.apolice') }}</th>
                            <th class="hidden-sm hidden-xs">{{ trans('processes.type_id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processes_deadlines as $p)
                        <tr>
                            <td>{{ $p->certificate }}</td>
                            <td><a data-title="{{ $p->client->name }}" data-image="{{ $p->client->photo }}" data-subtitle="{{ $p->client->email }}" class="popover-style">{{ $p->client->name }}</a></td>
                            @if(!is_null($p->expert)) 
                            <td><a data-title="{{ $p->expert->name }}" data-image="{{ $p->expert->photo }}" data-subtitle="{{ $p->expert->email }}<br>{{ $p->expert->function }}" class="popover-style">{{ $p->expert->name }}</a></td>
                            @else
                            <td></td>
                            @endif
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
                            <td  class="hidden-sm hidden-xs">{{ $p->apolice }}</td>
                            <td  class="hidden-sm hidden-xs">{{ Helper::isNull($p->type) ? '' : $p->type->name }}</td>
                            <td class="center">
                                <a href="{{ route('processes.show', ['id' => $p->id]) }}" class="btn btn-xs btn-block btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.show') }}"><i class="clip-info-2"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="col-md-11 text-left">
    <canvas id="experts-processes"></canvas> 
</div>

@stop
