@extends('reports.layout')

@section('title')
{{ trans('reports.preliminar_title') }}
@stop

@section('title')
{{ trans('processes.preliminar_report') }}
@stop

@section('certificate')
{{ $process->certificate }}
@stop

@section('content')
<tr>
    <td colspan="2" class="content">
        <div class="welcome_regards">
            {{ trans('reports.preliminar_welcome', ['date' => Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->format('d/m/Y')]) }}
        </div>

        <p class="field">
            <span class="title"> {{ trans('processes.client_id') }}: </span>
            <span class="text"> {{ $process->client->name }} </span> 
        </p>
        
        <p class="field">
            <span class="title"> {{ trans('insureds.insured') }}: </span>
            <span class="text"> {{ Helper::isNull($process->insured) ? '_________________________________________________________' : $process->insured->name }} </span> 
        </p>

        @if(!Helper::isNull($process->taker))
        <p class="field">
            <span class="title"> {{ trans('insureds.taker') }}: </span>
            <span class="text"> {{ Helper::isNull($process->taker) ? '_________________________________________________________' : $process->taker->name }} </span> 
        </p>
        @endif

        <p class="field">
            @if(!Helper::isNull($process->reference))
            <span class="title"> {{ trans('processes.reference') }}: </span>
            <span class="text"> {{ $process->reference }} </span> <span style="margin-left: 8px; margin-right: 8px;">//</span>
            @endif
            <span class="title"> {{ trans('processes.apolice') }}: </span>
            <span class="text"> {{ $process->apolice }} </span> <span style="margin-left: 8px; margin-right: 8px;">//</span> 
            <span class="title"> {{ trans('processes.type_id') }}: </span>
            <span class="text"> {{ Helper::isNull($process->type) ? '' : $process->type->name }} </span>
        </p>


    </td>
</tr>

<tr>
    <td colspan="2" class="content" style="padding-top: 0px; margin-top: 0px;">
        @foreach($process->fields as $f)
        <p class="field">
            <span class="title"> {{ $f->key }}: </span>
            <span class="text"> {{ $f->value }} </span> 
        </p>
        @endforeach

        <p class="field">
            <span class="title"> {{ trans('processes.situation_losts') }}: </span>
            @if(!Helper::isNull($process->situation_losts))
            <span class="text"> Eur. {{ $process->situation_losts }} € </span> 
            @endif
        </p>

        <p class="field">
            <span class="title"> {{ trans('processes.situation_observations') }}: </span>
            <span class="text"> {{ $process->situation_observations }} </span> 
        </p>

        <div class='welcome_regards'>
            <b class="title">{{ trans('reports.preliminar_regards') }}</b><br>
            @if(Helper::isNull($process->expert))
            {{ Config::get('settings.title') }}
            @else
            {{ $process->expert->name }}
            @endif
        </div>
    </td>
</tr>
@stop



