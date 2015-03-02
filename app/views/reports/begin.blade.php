@extends('reports.layout')

@section('title')
{{ trans('reports.begin_title') }}
@stop

@section('certificate')
{{ $process->certificate }}</span> - <span class="title">Data de Abertura:</span> <span class="text">{{ Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->format('d/m/Y') }}</span>
@stop

@section('content')
<tr>
    <td colspan="2" class="content" style="padding-top: 20px;padding-bottom: 250px;">
        <p class="field">
            <span class="title"> {{ trans('processes.client_id') }}: </span>
            <span class="text"> {{ $process->client->name }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('clients.nif') }}: </span>
            <span class="text"> {{ $process->client->nif }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('clients.city') }}: </span>
            <span class="text"> {{ $process->client->city }} </span> <span style="padding-left: 5px; padding-right:5px;">//</span>
            <span class="title"> {{ trans('clients.address') }}: </span>
            <span class="text"> {{ $process->client->address }} </span> <span style="padding-left: 5px; padding-right:5px;">//</span>
            <span class="title"> {{ trans('clients.zipcode') }}: </span>
            <span class="text"> {{ $process->client->zipcode }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.preliminar_date') }}: </span>
            <span class="text"> {{ $process->preliminar_date }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.complete_date') }}: </span>
            <span class="text"> {{ Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->addDays($process->deadline_complete)->format('d/m/Y') }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.insured_id') }}: </span>
            <span class="text"> {{ $process->insured->name }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.apolice') }}: </span>
            <span class="text"> {{ $process->apolice }} </span> // 
            <span class="title"> {{ trans('processes.type_id') }}: </span>
            <span class="text"> {{ $process->type->name }} </span>
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.reference') }}: </span>
            <span class="text"> {{ $process->reference}} </span> 
        </p>       
        <p class="field">
            <span class="title"> {{ trans('processes.expert_id') }}: </span>
            <span class="text"> {{ $process->expert->name }} </span> 
        </p>
        <p class="field">
            <span class="title"> {{ trans('processes.status_id') }}: </span>
            <span class="text"> {{ $process->status->name }} </span> 
        </p>
    </td>
</tr>

@stop



