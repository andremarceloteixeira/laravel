@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
        TableData.static();
        Modal.confirmListener();
    });</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-users"></i> {{ trans('navigation.clients') }}</li>
@stop

@section('title')
{{ trans('navigation.clients') }} <small>{{ trans('actions.list', ['type' => trans('clients.plural')]) }}</small>
@stop

@section('main')
<div class="col-md-12 space20">
    <a href="{{ route('clients.create') }}" class="btn btn-green add-row">
        <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('clients.singular')]) }} 
    </a>
</div>
<div class="table-responsive">               
    <table class="table table-striped table-bordered table-hover table-full-width static-table">
        <thead>
            <tr>
                <th>{{ trans('users.photo') }}</th>
                <th class="hidden-xs">{{ trans('clients.reference') }}</th>
                <th class="hidden-xs">{{ trans('users.username') }}</th>
                <th>{{ trans('users.name') }}</th>
                <th class="hidden-xs">{{ trans('users.email') }}</th>
                <th class="hidden-xs">{{ trans('clients.nif') }}</th>
                <th class="hidden-xs hidden-md">{{ trans('clients.city') }}</th>
                <th class="hidden-xs hidden-md">{{ trans('clients.zipcode') }}</th>
                <th class="hidden-xs hidden-md">{{ trans('clients.country_id') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $c)
            <tr>
                <td align="center"><div class="thumbnail" style="width: 100px; height: 100px;">{{ HTML::image($c->photo, null, ['width' => 100, 'height' => 100]) }}</div></td>
                <td class="hidden-xs">{{ $c->reference }}</td>
                <td class="hidden-xs">{{ $c->username }}</td>
                <td>{{ $c->name }}</td>
                <td class="hidden-xs">{{ $c->email }}</td>
                <td class="hidden-xs">{{ $c->nif }}</td>
                <td class="hidden-xs hidden-md">{{ $c->city }}</td>
                <td class="hidden-xs hidden-md">{{ $c->zipcode }}</td>
                <td class="hidden-xs hidden-md" >{{ $c->country->name }}</td>
                <td class="center" style="width: 8%; width=25%">
                    <a href="{{ route('clients.show', ['id' => $c->user_id]) }}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.show') }}"><i class="clip-info-2"></i></a>
                    <a href="{{ route('clients.edit', ['id' => $c->user_id]) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
                    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.password_reset') }}" data-title="{{ trans('actions.reseting', ['name' => $c->name, 'type' => trans('clients.singular')]) }}" data-body="{{ trans('actions.are_sure_reset', ['name' => $c->name, 'type' => trans('clients.singular')]) }}" data-url="{{ route('clients.reset', ['id' => $c->user_id]) }}" data-method="GET" type="button" class="btn btn-info btn-xs confirm-btn tooltips"><i class="fa fa-key fa fa-white"></i></button>
                    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $c->name, 'type' => trans('clients.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $c->name, 'type' => trans('clients.singular')]) }}" data-url="{{ route('clients.destroy', ['id' => $c->user_id]) }}" data-method="DELETE" type="button" class="btn btn-bricky btn-xs confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

