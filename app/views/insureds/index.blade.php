@extends('layout')

@section('js-required')
<script>
$(document).ready(function() {
    TableData.static();
    Modal.confirmListener();
});
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-users"></i> {{ trans('navigation.insureds') }}</li>
@stop

@section('title')
{{ trans('navigation.insureds') }} <small>{{ trans('actions.list', ['type' => trans('insureds.plural')]) }}</small>
@stop

@section('main')

<div class="col-md-12 space20">
    <a href="{{ route('insureds.create') }}" class="btn btn-green add-row">
        <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('insureds.singular')]) }} 
    </a>
</div>
<div class="table-responsive">               
    <table class="table table-striped table-bordered table-hover table-full-width static-table">
        <thead>
            <tr>
                <th class="hidden-xs">{{ trans('insureds.reference') }}</th>
                <th>{{ trans('insureds.name') }}</th>
                <th class="hidden-xs">{{ trans('insureds.nif') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($insureds as $i)
            <tr>
                <td class="hidden-xs">{{ $i->reference }}</td>
                <td>{{ $i->name }}</td>
                <td class="hidden-xs">{{ $i->nif }}</td>
                <td class="center">
                    <a href="{{ route('insureds.show', ['id' => $i->id]) }}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.show') }}"><i class="clip-info-2"></i></a>
                        <a href="{{ route('insureds.edit', ['id' => $i->id]) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
                        <button data-placement="top" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $i->name, 'type' => trans('insureds.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $i->name, 'type' => trans('insureds.singular')]) }}" data-url="{{ route('insureds.destroy', ['id' => $i->id]) }}" data-type="sync" data-method="DELETE" type="button" class="btn btn-bricky btn-xs confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

