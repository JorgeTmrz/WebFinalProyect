@extends('master')
@section('contenido')

<div class="container m-2">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <h2>Editar Cita</h2>
    <form action="/editedc" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <small>Duracion</small>
          <input value="{{$cita->Duracion}}" type="number" name="duracion" class="form-control" autofocus>
          </div>
          <div class="form-group">
            <small>Hora</small>
            <input value="{{$cita->Hora}}" type="time" name="hora" class="form-control" placeholder="Hora">
        </div>
        <div class="form-group">
            <small>Fecha</small>
            <input value="{{$cita->Fecha}}" type="date" name="fecha" class="form-control">
        </div>
        <div class="form-group">
            <small>Doctor/a</small>
            <select value="{{$cita->Doctor_Asigando}}" type="text" name="doctor" class="form-control">
                @foreach ($doctores as $item)
                    <option value="{{$item->Id}}">{{$item->Nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <small>Paciente</small>
            <select value="{{$cita->Paciente_asignado}}" type="text" name="paciente" class="form-control">
                @foreach ($pacientes as $item)
                    <option value="{{$item->Cedula}}">{{$item->nombre}}</option>
                @endforeach
            </select>
        <input type="number" hidden name = "id" value="{{$cita->Id}}">
        </div>
        {{csrf_field()}}
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
        </form>
</div>
@endsection
