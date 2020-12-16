@extends('layouts.app')

@section('title', 'Listado de Laboratorios')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Listado de Laboratorios</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.lab.create') }}">Crear nuevo laboratorio</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Id Openagora</th>
            <th>Nombre</th>
            <th>Alias</th>
            <th>Externo</th>
            <th>Webservice Minsal</th>
            <th>Token Webservice</th>
            <th>PDF automático</th>
            <th>Cod. Deis</th>
            <th>Comuna</th>
            <th>Director</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
    @foreach($laboratories as $laboratory)
        <tr>
            <td>{{ $laboratory->id_openagora }}</td>
            <td>{{ $laboratory->name }}</td>
            <td>{{ $laboratory->alias }}</td>
            <td>{{ ($laboratory->external == 1)? 'Si':'No' }}</td>
            <td>{{ ($laboratory->minsal_ws == 1)? 'Si':'No' }}</td>
            <td>{{ $laboratory->token_ws }}</td>
            <td>{{ ($laboratory->pdf_generate == 1)? 'Si':'No' }}</td>
            <td>{{ $laboratory->cod_deis }}</td>
            <td>{{ $laboratory->commune->name }}</td>
            <td>{{ ($laboratory->director) ? $laboratory->director->name : '' }}</td>
            <td>
            <a href="{{ route('parameters.lab.edit', $laboratory) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
