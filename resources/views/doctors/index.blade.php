@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Gestão dos medicos</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('doctors.create') }}"> Novo Medico</a>
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
   <th>Especialidade</th>
   <th>Roles</th>
   <th width="280px">Ação</th>
 </tr>
 @foreach ($data as $key => $doctor)
  <tr>
    <td>{{ ++$i }}</td>
    <td>{{ $doctor->user->name }}</td>
    <td>{{ $doctor->user->email }}</td>
    <td>{{ $doctor->phone }}</td>
    <td>{{ $doctor->specialty }}</td>
    <td>
      @if(!empty($doctor->user->getRoleNames()))
        @foreach($doctor->user->getRoleNames() as $v)
           <label class="badge badge-success">{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>
       <a class="btn btn-info" href="{{ route('doctors.show',$doctor->id) }}">Visualizar</a>
       <a class="btn btn-primary" href="{{ route('doctors.edit',$doctor->id) }}">Editar</a>
        {!! Form::open(['method' => 'DELETE','route' => ['doctors.destroy', $doctor->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </td>
  </tr>
 @endforeach
</table>

@endsection