@extends('emails.layout')

@section('main')
{{ trans('emails.welcome', ['name' => $name]) }}

<?php if($complete) :?>
<p>
    {{ trans('emails.charge-content-complete', ['certificate' => $certificate]) }}
</p>
<?php else :?>
<p>
    {{ trans('emails.charge-content', ['certificate' => $certificate]) }}
</p>
<?php endif;?>
<p>
    {{ trans('emails.regards', ['name' => $signature]) }}
</p>
@stop