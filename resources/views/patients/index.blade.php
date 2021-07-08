@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Gestão de paciênte</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('patients.create') }}"> Novo parciênte</a>
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
   <th>Nome</th>
   <th>Email</th>
   <th>Telefone</th>
   <th>Plano de Saúde</th>
   <th>Roles</th>
   <th width="280px">Ação</th>
 </tr>
 @foreach ($data as $key => $patient)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $patient->user->name }}</td>
    <td>{{ $patient->user->email }}</td>
    <td>{{ $patient->phone }}</td>
    <td>{{ $patient->health_plan }}</td>
    <td>
      @if(!empty($patient->user->getRoleNames()))
        @foreach($patient->user->getRoleNames() as $v)
           <label class="badge badge-success">{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>
       <a class="btn btn-info" href="{{ route('patients.show',$patient->id) }}">Visualizar</a>
       <a class="btn btn-primary" href="{{ route('patients.edit',$patient->id) }}">Editar</a>
        {!! Form::open(['method' => 'DELETE','route' => ['patients.destroy', $patient->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>

@endsection