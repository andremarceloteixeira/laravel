@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
    TableData.static();
            Modal.confirmListener();
    });</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-tree"></i> {{ trans('navigation.types') }}</li>
@stop

@section('title')
{{ trans('navigation.types') }} <small>{{ trans('actions.list', ['type' => trans('types.plural')]) }}</small>
@stop

@section('main')

<div class="col-md-12 space20">
    <a href="{{ route('types.create') }}" class="btn btn-green add-row">
        <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('types.singular')]) }} 
    </a>
</div>
<div class="table-responsive">               
    <table class="table table-striped table-bordered table-hover table-full-width static-table">
        <thead>
            <tr>
                <th>{{ trans('types.code') }}</th>
                <th class="hidden-xs">{{ trans('types.name') }}</th>
                <th class="hidden-xs">{{ trans('types.title') }}</th>
                <th class="hidden-xs">{{ trans('types.count_process') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $t)
            <tr>
                <td>{{ $t->code }}</td>
                <td  class="hidden-xs">{{ $t->name }}</td>
                <td  class="hidden-xs">"{{ $t->title }}<b>"</td>
                <td  class="hidden-xs">{{ $t->processes()->count() }}</td>
                <td class="center">
                    <a href="{{ route('types.show', ['id' => $t->id]) }}" target="_blank" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.show') }}"><i class="clip-info-2"></i></a>
                    <a href="{{ route('types.edit', ['id' => $t->id]) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

