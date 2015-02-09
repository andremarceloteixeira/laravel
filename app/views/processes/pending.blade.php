@extends('layout')

@section('js-required')
<script>
    $(document).ready(function() {
        PopOver.init();
        TableData.dynamic('{{ route('pending.pending') }}', 30000);
        Modal.confirmListener();

        $(document).on('click', '.chargeModalAction', function() {
            $('#chargeModalId').attr('value', $(this).data('id'));
            $('#chargeModalTitle').text($(this).data('title'));
            $('#chargeModalSubmit').attr('data-remove-id', $(this).attr('id'));
            $('#chargeModal').modal('show');
        });

        $(document).on('click', '#chargeModalSubmit', function() {
            var ele = $(this);
            var l = Ladda.create(this);
            l.start();
            $.get('<?php echo route('pending.charge') ?>', {id: $('#chargeModalId').val(), expert_id: $('#chargeModalSelect').val()}, function(data) {
                $('#' + ele.data('remove-id')).remove();
                l.stop();
                $('#chargeModal').modal('hide');
                if (data['status'] == "success") {
                    Notification.success(data['title'], data['message']);
                } else if (data['status'] == "error") {
                    Notification.error(data['title'], data['message']);
                }
            });
        });
    });
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-bubbles"></i> {{ trans('navigation.pending.pending') }}</li>
@stop

@section('title')
{{ trans('navigation.pending.pending') }}
@stop

@section('main')
<div class="table-responsive">               
    <table id="pendingTable" class="table table-striped table-bordered table-hover table-full-width dynamic-table">
        <thead>
            <tr>
                <th>{{ trans('processes.reference') }}</th>
                <th>{{ trans('processes.client_id') }}</th>
                <th>{{ trans('processes.apolice') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<!-- start: CHARGE MODAL -->
<div id="chargeModal" class="modal fade" tabindex="-1"  data-width="700" style="display: none;">
    <form accept-charset="UTF-8" id="chargeModalForm" class="form-horizontal" role="form">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 id="chargeModalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <input id="chargeModalId" name="id" type="hidden" />
            <div class="form-group">
                <label class="col-sm-2 control-label label_blue">
                    {{ trans('processes.expert_id') }} 
                </label>
                <div class="col-sm-7">
                    {{ Form::select('expert_id', $experts, null, ['class' => 'form-control search-select', 'id' => 'chargeModalSelect']) }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-blue">
                {{ trans('actions.close') }}
            </button>
            <button data-style="expand-right"  id="chargeModalSubmit" type="button" class="btn btn-blue btn-md ladda-button">
                <span class="ladda-label"> {{ trans('actions.confirm') }}  </span>
                <i class="fa fa-arrow-circle-right"></i>
                <span class="ladda-spinner"></span>
                <span class="ladda-spinner"></span>
            </button>
        </div>

    </form>
</div>
<!-- end: CHARGE MODAL --> 

@stop

