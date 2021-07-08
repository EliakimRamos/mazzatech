@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Gestão de consultas</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('schedules.create') }}"> Novo agendamento</a>
        </div>
    </div>
</div>


@if ($message = Session::get('sucesso'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif


<table class="table">
 <tr>
   <th>No</th>
   <th>Nome do médico</th>
   <th>Nome do parciênte</th>
   <th>data/hora</th>
   <th>Status</th>
   <th width="280px">Ação</th>
 </tr>
 @foreach ($data as $key => $schedule)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $schedule->doctor->user->name }}</td>
    <td>{{ $schedule->patient->user->name }}</td>
    <td>{{ $schedule->scheduling }}</td>
    <td>
        @php
            switch ($schedule->status) {
                case 'Agendado':
                    echo'<label class="badge badge-info">'.$schedule->status.'</label>';
                    break;
                case 'Confirmado':
                    echo'<label class="badge badge-primary">'.$schedule->status.'</label>';
                    break;
                case 'Realizado':
                    echo'<label class="badge badge-success">'.$schedule->status.'</label>';
                    break;
                case 'Cancelado':
                    echo'<label class="badge badge-danger">'.$schedule->status.'</label>';
                    break;
            }
        @endphp
        
        {{-- <label class="badge badge-success">{{ $schedule->status }}</label> --}}
    </td>
    <td>
        {!! Form::open(['method' => 'PATCH','route' => ['schedules.update', $schedule->id],'style'=>'display:inline']) !!}
            {!! Form::hidden('status','2') !!}
            {!! Form::submit('Confirmar', ['class' => 'btn btn-info']) !!}
        {!! Form::close() !!}
        {!! Form::open(['method' => 'PATCH','route' => ['schedules.update', $schedule->id],'style'=>'display:inline']) !!}
            {!! Form::hidden('status','3') !!}
            {!! Form::submit('Realizado', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
        {!! Form::open(['method' => 'PATCH','route' => ['schedules.update', $schedule->id],'style'=>'display:inline']) !!}
            {!! Form::hidden('status','4') !!}
            {!! Form::submit('Cancelado', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>

@endsection