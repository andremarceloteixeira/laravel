@extends('layout')

@section('breadcrumb')
<li><i class="clip-users-5"></i> <a href="{{ route('insureds.index') }}">{{ trans('navigation.insureds') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.showing', ['type' => trans('insureds.singular'), 'name' => $insured->name]) }}</li>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        Modal.confirmListener();
    });
</script>
@stop

@section('title')
{{ trans('navigation.insureds') }} <small>{{ trans('actions.showing', ['type' => trans('insureds.singular'), 'name' => $insured->name]) }}</small>
@stop

@section('main')
@if(Check::isAdmin())
<div class="col-lg-12 text-right">
    <a href="{{ route('insureds.edit', ['id' => $insured->id]) }}" class="btn btn-md btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $insured->name, 'type' => trans('insureds.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $insured->name, 'type' => trans('insureds.singular')]) }}" data-url="{{ route('insureds.destroy', ['id' => $insured->id]) }}" data-method="DELETE" type="button" class="btn btn-bricky btn-md confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
</div>
@endif

<table class="table table-condensed table-hover">
    <thead>
        <tr>
            <th colspan="2">{{ trans('insureds.singular') }} {{ $insured->name }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ trans('insureds.reference') }}</b></td>
            <td>{{ $insured->reference }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('insureds.insured_type_id') }}</b></td>
            <td>{{ $insured->type->name }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('insureds.nif') }}</b></td>
            <td>{{ $insured->nif }}</td>
        </tr>
    </tbody>
</table>

@stop
