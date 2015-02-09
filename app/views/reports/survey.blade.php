@extends('reports.layout')

@section('title')
{{ $process->type->title }}
@stop

@section('certificate')
{{ $process->certificate }}
@stop

@section('content')
<tr>
    <td colspan="2" class="content" valign="top" style='padding-top: 20px;'>
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
            <span class="text"> {{ $process->type->name }} </span>
        </p>



        @foreach($process->type->fields as $f)
        <p class="field">
            <span class="title"> {{ $f->value }}: </span>
            <span class="text"> ________________________________________ </span> 
        </p>
        @endforeach
    </td>
</tr>
<tr>
    <td colspan="2" class="content field"  style="padding-top: 10px;padding-bottom: 20px;">
        {{ $process->type->first_notes }}:
    </td>
</tr>
<tr>
    <td colspan="2" class="content" valign="top">
        <?php $till = 4;
        $i = 0; ?>
        @foreach($process->type->checkboxes as $f)
        @if($i % 4 == 0)
        <p class="field" style="padding-top:0; margin-top:0;">
            @else
        <p class="field">
            @endif
            <img src="{{ asset('assets/images/chkbox.png') }}" width="12" height="12"  /> <span class="text">  {{ $f->value }};</span> 
        </p>
<?php $i++; ?>
        @if($i % 4 == 0)
    </td>
</tr>
<tr>
    <td colspan="2" class="content" valign="top">
        @endif
        @endforeach
    </td>
</tr>
<tr>
    <td colspan="2" class="content" valign="top">
        <p class="field" style="padding-top: 0; margin-top: 0;">
            <img src="{{ asset('assets/images/chkbox.png') }}" width="12" height="12"  />
            <span class="text"> Outros: _________________________________________________________________________________<br>__________________________________________________________________________________________<br>__________________________________________________________________________________________</span> 
        </p>
    </td>
</tr>
<tr>
    <td colspan="2" class="content" valign="top">
        @if(!Helper::isNull($process->type->last_notes))
        <b style="font-size: 8.5pt;">{{ trans('types.note') }}: </b><span style="font-size: 8.5pt">{{ $process->type->last_notes }}</span><br><br><br>
        @endif

        <table style="width: 100%;">
            <tr>
                <td valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.date') }}</b><br>
                    ___ / ___ / _____
                </td> 
                <td  valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.insured') }}</b><br>
                    _____________________<br>
                    <b style="font-size:7pt;">{{ trans('types.contact') }}: </b>__________________<br>
                    <b style="font-size:7pt;">Email: </b>_____________________
                </td> 
                <td  valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.expert') }}</b><br>
                    _____________________<br>
                    <b style="font-size:7pt;">{{ trans('types.contact') }}: </b>__________________<br>
                    <b style="font-size:7pt;">Email: </b><span style="font-size: 7pt;">_____________________</span>
                </td> 
            </tr>
        </table>
        <br><br><br>
    </td>
</tr>
@stop



