@extends('layout')

@section('breadcrumb')
<li><i class="clip-users-5"></i> <a href="{{ route('experts.index') }}">{{ trans('navigation.experts') }}</a></li>
<li class="active"><i class="clip-user"></i> {{ trans('actions.showing', ['type' => trans('experts.singular'), 'name' => $expert->name]) }}</li>
@stop

@section('js-required')
<script>
    $(document).ready(function() {
        Modal.confirmListener();
    });
</script>
@stop

@section('title')
{{ trans('navigation.experts') }} <small>{{ trans('actions.showing', ['type' => trans('experts.singular'), 'name' => $expert->name]) }}</small>
@stop

@section('main')
@if(Check::isAdmin())
<div class="col-lg-12 text-right">
    <a href="{{ route('experts.edit', ['id' => $expert->user_id]) }}" class="btn btn-md btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.password_reset') }}" data-title="{{ trans('actions.reseting', ['name' => $expert->name, 'type' => trans('experts.singular')]) }}" data-body="{{ trans('actions.are_sure_reset', ['name' => $expert->name, 'type' => trans('experts.singular')]) }}" data-url="{{ route('experts.reset', ['id' => $expert->user_id]) }}" data-method="GET" type="button" class="btn btn-info btn-md confirm-btn tooltips"><i class="fa fa-key fa fa-white"></i></button>
    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $expert->name, 'type' => trans('experts.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $expert->name, 'type' => trans('experts.singular')]) }}" data-url="{{ route('experts.destroy', ['id' => $expert->user_id]) }}" data-method="DELETE" type="button" class="btn btn-bricky btn-md confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
</div>
@endif
{{ HTML::image($expert->photo, null, ['width' => 200, 'height' => 200, 'class' => 'thumbnail']) }}

<table class="table table-condensed table-hover">
    <thead>
        <tr>
            <th colspan="2">{{ trans('experts.singular') }} {{ $expert->name }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>{{ trans('users.username') }}</b></td>
            <td>{{ $expert->username }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('users.email') }}</b></td>
            <td>{{ $expert->email }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('experts.birthday') }}</b></td>
            <td>{{ $expert->birthday }}</td>
        </tr>
        <tr>
            <td><b>{{ trans('experts.function') }}</b></td>
            <td>{{ $expert->function }}</td>
        </tr>
    </tbody>
</table>

@stop
