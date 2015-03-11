@extends('emails.layout')

@section('main')

<p>
    Recebeu o seguinte contacto de  <?php  echo $fristName . ' ' . $lastName . ' ';  ?>
</p>
<p>
    Email:  <?php  echo $email;  ?>
</p>
<p>
    Nome:    <?php  echo $fristName . ' ' .$lastName . ' ';  ?>
<p>
    Assunto:   <?php  echo $assunto;  ?>
</p>
<p>
    Mensagem:    <?php  echo $assunto;  ?>
</p>
@stop