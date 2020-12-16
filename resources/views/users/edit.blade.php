@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Editar Usuario</h3>

<form method="POST" class="form-horizontal" action="{{ route('users.update',$user) }}">
    @csrf
    @method('PUT')
    <div class="card mb-3">
        <div class="card-body">

            <div class="form-row">

                <fieldset class="form-group col-8 col-md-2">
                    <label for="for_run">Run</label>
                    <input type="number" class="form-control" name="run" id="for_run"
                        value="{{ $user->run }}" required>
                </fieldset>

                <fieldset class="form-group col-4 col-md-1">
                    <label for="for_dv">Dv</label>
                    <input type="text" class="form-control" name="dv" id="for_dv"
                        value="{{ $user->dv }}" required>
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_name">Nombre y Apellido</label>
                    <input type="text" class="form-control" name="name" id="for_name"
                        value="{{ $user->name }}" required>
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_email">Email</label>
                    <input type="email" class="form-control" name="email" id="for_email"
                        value="{{ $user->email }}" required style="text-transform: lowercase;">
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_laboratory_id">Laboratorio</label>
                    <select name="laboratory_id" id="for_laboratory_id" class="form-control">
                        <option value=""></option>
                        @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}" {{ ($user->laboratory_id == $lab->id)?'selected':'' }}>{{ $lab->alias }}</option>
                        @endforeach
                    </select>
                </fieldset>

            </div>

            <div class="form-row">

                <fieldset class="form-group col-12 col-md-6">
                    <label for="for_establishment_id">Establecimiento *</label>
                    <select name="establishment_id[]" id="for_establishment_id" class="form-control selectpicker" data-live-search="true" multiple="" data-size="10" title="Seleccione..." multiple data-actions-box="true" required>

                            @foreach($establishments as $establishment)
                                <option value="{{ $establishment->id }}" @if(in_array($establishment->id, $establishment_selected)) selected="selected" @endif>{{ $establishment->alias }}</option>
                            @endforeach

                    </select>
                </fieldset>


                <fieldset class="form-group col-6 col-md-3">
                    <label for="for_telephone">Telefono</label>
                    <input type="text" class="form-control" name="telephone" id="for_telephone" placeholder="ej:+56912345678"
                    value="{{ $user->telephone }}">
                </fieldset>

                <fieldset class="form-group col-6 col-md-3">
                    <label for="for_function">Función</label>
                    <input type="text" class="form-control" name="function" id="for_function"
                    value="{{ $user->function }}">
                </fieldset>




            </div>



            <div class="form-row">
                <fieldset class="form-group col-6 col-md-6">
                    <a class="btn btn-primary btn-sm" href="{{ route('users.password.restore', $user) }}">
                        <i class="fas fa-plus"></i> Generar Nueva Contraseña
                    </a>
                </fieldset>
            </div>

        </div>
    </div>

    <hr>

    <div class="form-row">
        <div class="col">



            <h5>Roles:</h5>
            @foreach($permissions as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]"
                    value="{{ $permission->name }}" {{ ($user->hasPermissionTo($permission->name))?'checked':'' }}>
                <label class="form-check-label">
                    {{ $permission->name }}
                </label>
            </div>
            @endforeach
        </div>
        <!-- <div class="col">
            <h5>Establecimientos:</h5>
            @foreach($establishments_user as $establishment_user)
              {{ $establishments_user }}
            @endforeach
        </div> -->
    </div>
    <button type="submit" class="btn btn-primary mt-3">Guardar</button>


</form>



@endsection

@section('custom_js')

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

@endsection
