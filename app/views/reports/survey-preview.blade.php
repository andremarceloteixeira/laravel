@extends('reports.layout')

@section('title')
Pré-visualizar o Ramo {{ $type->code }}
@stop

@section('title')
{{ $type->title }}
@stop

@section('certificate')
12345/14
@stop

@section('content')
@if(!Helper::isNull($type->first_notes))
<tr>
    <td colspan="2" class="content field" style="padding-top: 25px;">
        {{ $type->first_notes }}:
    </td>
</tr>
@endif
<tr>
    <td colspan="2" class="content">
        <p class="field">
            <span class="title"> {{ trans('processes.client_id') }}: </span>
            <span class="text"> Cliente XPTO </span>
        </p>

        <p class="field">
            <span class="title"> {{ trans('processes.apolice') }}: </span>
            <span class="text"> 15126123123 </span> // 
            <span class="title"> {{ trans('processes.type_id') }}: </span>
            <span class="text"> {{ $type->name }} </span>
        </p>

        <p class="field">
            <span class="title"> Segurado/Tomador do Segurado: </span>
            <span class="text"> Segurado XPTO</span>
        </p>

        @foreach($type->fields as $f)
        <p class="field">
            <span class="title"> {{ $f->value }}: </span>
            <span class="text"> ________________________________________ </span> 
        </p>
        @endforeach


        @foreach($type->checkboxes as $f)
        <p class="field">
            <img src="{{ asset('assets/images/chkbox.png') }}" width="12" height="12"  /> <span class="text">  {{ $f->value }} </span> 
        </p>
        @endforeach
        <p class="field">
            <span class="title"> Outros: </span>
            <span class="text"> _________________________________________________________________________________________________________<br>__________________________________________________________________________________________________________________<br>__________________________________________________________________________________________________________________</span> 
        </p>
    </td>
</tr>
<tr>
    <td colspan="2" class="content">
        @if(!Helper::isNull($type->last_notes))
        <b style="font-size: 8.5pt;">{{ trans('types.note') }}: </b><span style="font-size: 8.5pt">{{ $type->last_notes }}</span><br><br><br>
        @endif

        <table style="width: 100%;">
            <tr>
                <td valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.date') }}</b><br>
                    ___ / ___ / _____
                </td> 
                <td  valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.insured') }}</b><br>
                    ________________________<br>
                    <b style="font-size:7pt;">{{ trans('types.contact') }}: </b>__________________<br>
                    <b style="font-size:7pt;">Email: </b>_____________________
                </td> 
                <td  valign="top" class="text-center">
                    <b style="font-size: 8.5pt;">{{ trans('types.expert') }}</b><br>
                    ________________________<br>
                    <b style="font-size:7pt;">{{ trans('types.contact') }}: </b>__________________<br>
                    <b style="font-size:7pt;">Email: </b>_____________________
                </td> 
            </tr>
        </table>
        <br><br><br>
    </td>
</tr>
@stop



