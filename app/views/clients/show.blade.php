@extends('layout')

@section('breadcrumb')
<li><i class="clip-users"></i> <a href="{{ route('clients.index') }}">{{ trans('navigation.clients') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.showing', ['type' => trans('clients.singular'), 'name' => $client->name]) }}</li>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        Modal.confirmListener();
    });
</script>
@stop

@section('title')
{{ trans('navigation.clients') }} <small>{{ trans('actions.showing', ['type' => trans('clients.singular'), 'name' => $client->name]) }}</small>
@stop

@section('main')
@if(Check::isAdmin() || (isset($isvalid) && $isvalid))
<div class="col-lg-12 text-right">
    <a href="{{ route('clients.edit', ['id' => $client->user_id, 'active' => true]) }}" class="btn btn-md btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.password_reset') }}" data-title="{{ trans('actions.reseting', ['name' => $client->name, 'type' => trans('clients.singular')]) }}" data-body="{{ trans('actions.are_sure_reset', ['name' => $client->name, 'type' => trans('clients.singular')]) }}" data-url="{{ route('clients.reset', ['id' => $client->user_id]) }}" data-method="GET" type="button" class="btn btn-info btn-md confirm-btn tooltips"><i class="fa fa-key fa fa-white"></i></button>
    @if(Check::isAdmin())
         <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $client->name, 'type' => trans('clients.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $client->name, 'type' => trans('clients.singular')]) }}" data-url="{{ route('clients.destroy', ['id' => $client->user_id]) }}" data-method="DELETE" type="button" class="btn btn-bricky btn-md confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
    @endif
</div>
@endif
{{ HTML::image($client->photo, null, ['width' => 200, 'height' => 200, 'class' => 'thumbnail']) }}

<table class="table table-condensed table-hover">
    <thead>
        <tr>
            <th colspan="2">{{ trans('clients.singular') }} {{ $client->name }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ trans('clients.reference') }}</b></td>
            <td>{{ $client->reference }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('users.username') }}</b></td>
            <td>{{ $client->username }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('users.email') }}</b></td>
            <td>{{ $client->email }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('clients.nif') }}</b></td>
            <td>{{ $client->nif }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('clients.city') }}</b></td>
            <td>{{ $client->city }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('clients.address') }}</b></td>
            <td>{{ $client->address }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('clients.zipcode') }}</b></td>
            <td>{{ $client->zipcode }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('clients.country_id') }}</b></td>
            <td>{{ $client->country->name }}</td>
        </tr>

    </tbody>
</table>

@stop
