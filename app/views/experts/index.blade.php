@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
        TableData.static();
        Modal.confirmListener();
    });</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-user-5"></i> {{ trans('navigation.experts') }}</li>
@stop

@section('title')
{{ trans('navigation.experts') }} <small>{{ trans('actions.list', ['type' => trans('experts.plural')]) }}</small>
@stop

@section('main')
<div class="col-md-12 space20">
    <a href="{{ route('experts.create') }}" class="btn btn-green add-row">
        <i class="fa fa-plus"></i> {{ trans('actions.create', ['type' => trans('experts.singular')]) }} 
    </a>
</div>
<div class="table-responsive">               
    <table class="table table-striped table-bordered table-hover table-full-width static-table">
        <thead>
            <tr>
                <th>{{ trans('users.photo') }}</th>
                <th class="hidden-xs">{{ trans('users.username') }}</th>
                <th>{{ trans('users.name') }}</th>
                <th class="hidden-xs">{{ trans('users.email') }}</th>
                <th class="hidden-xs">{{ trans('experts.function') }}</th>
                <th class="hidden-xs">{{ trans('experts.birthday') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($experts as $e)
            <tr>
                <td align="center"><div class="thumbnail" style="width: 100px; height: 100px;">{{ HTML::image($e->photo, null, ['width' => 100, 'height' => 100]) }}</div></td>
                <td  class="hidden-xs">{{ $e->username }}</td>
                <td>{{ $e->name }}</td>
                <td  class="hidden-xs">{{ $e->email }}</td>
                <td  class="hidden-xs">{{ $e->function }}</td>
                <td  class="hidden-xs">{{ $e->birthage}}</td>
                <td class="center">
                    <a href="{{ route('experts.show', ['id' => $e->user_id]) }}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="{{ trans('actions.show') }}"><i class="clip-info-2"></i></a>
                    <a href="{{ route('experts.edit', ['id' => $e->user_id]) }}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="{{ trans('actions.update') }}"><i class="fa fa-edit"></i></a>
                    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.password_reset') }}" data-title="{{ trans('actions.reseting', ['name' => $e->name, 'type' => trans('experts.singular')]) }}" data-body="{{ trans('actions.are_sure_reset', ['name' => $e->name, 'type' => trans('experts.singular')]) }}" data-url="{{ route('experts.reset', ['id' => $e->user_id]) }}" data-method="GET" type="button" class="btn btn-info btn-xs confirm-btn tooltips"><i class="fa fa-key fa fa-white"></i></button>
                    <button data-placement="top" data-type="sync" data-original-title="{{ trans('actions.delete') }}" data-title="{{ trans('actions.deleting', ['name' => $e->name, 'type' => trans('experts.singular')]) }}" data-body="{{ trans('actions.are_sure_delete', ['name' => $e->name, 'type' => trans('experts.singular')]) }}" data-url="{{ route('experts.destroy', ['id' => $e->user_id]) }}" data-method="DELETE" type="button" class="btn btn-bricky btn-xs confirm-btn tooltips"><i class="fa fa-times fa fa-white"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

