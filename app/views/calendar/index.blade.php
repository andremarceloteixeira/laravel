@extends('layout')

@section('css-required')
<link rel="stylesheet" href="{{ asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}">
@stop

@section('js-required')
<script src="{{ asset('assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.js') }}"></script>
<script>
$(document).ready(function() {
    Calendar.init(<?php echo CalendarEvent::JSON(); ?>, <?php echo EventType::JSON(); ?>, '<?php echo route('calendar.store') ?>', '<?php echo route('calendar.update') ?>', '<?php echo route('calendar.destroy') ?>', '<?php echo route('calendar.move') ?>');
});
</script>
@stop

@section('breadcrumb')
<li class="active"><i class="clip-calendar-3"></i> {{ trans('navigation.calendar') }}</li>
@stop

@section('title')
{{ trans('navigation.calendar') }} 
@stop

@section('main')
<div class="row">
    <div class="col-sm-9">
        <div id='calendar'></div>
    </div>
    <div class="col-sm-3">
        <h3>{{ trans('carbon.today') }}</h3>
        {{ $today }}
    </div>
</div>

<div id="event-management" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title">{{ trans('events.plural') }}</h4>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn btn-default">{{ trans('actions.close') }}</button>
        <button type="button" class="btn btn-danger remove-event no-display">
            <i class='fa fa-trash-o'></i> {{ trans('actions.remove') }}
        </button>
        <button class="btn btn-blue btn-md ladda-button save-event" data-style="expand-right" type="button">
            <span class="ladda-label"> {{ trans('actions.confirm') }} </span>
            <i class="fa fa-arrow-circle-right"></i>
            <span class="ladda-spinner"></span>
            <span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
        </button>
    </div>
</div>
@stop
