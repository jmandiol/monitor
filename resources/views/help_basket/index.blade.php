@extends('layouts.app')

@section('title', 'Listado de Entregas de Canastas')
@section('content')
@include('help_basket.nav')

<h3 class="mb-3">Listado de Canasta Entregadas</h3>

<div class="col-4 col-md-2">
    <a class="btn btn-primary mb-3" href="{{ route('help_basket.create') }}">
        Entregar Canasta
    </a>
</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre Completo</th>
            <th>Dirección</th>
            <th>Comuna</th>
            <th>Fono</th>
            <th>Entregado Por</th>
            <th>Entregado el</th>
            <th>Editar</th>            
            <th>Eliminar</th>
        </tr>
    </thead>

    <tbody>
        @foreach($helpbaskets as $helpBasket)
        <tr>
            <td>{{$helpBasket->identifier}}</td>
            <td>{{$helpBasket->fullName}}</td>
            <td>{{$helpBasket->address}} {{$helpBasket->number}} {{$helpBasket->department}} </td>
            <td>{{$helpBasket->commune->name}}</td>
            <td>{{$helpBasket->telephone}}</td>
            <td>{{$helpBasket->user->name}}</td>
            <td>{{$helpBasket->updated_at->format('d-m-Y H:i')}}</td>
            <td>
                @if($helpBasket->user_id == Auth::id())
                <a href="{{ route('help_basket.edit', $helpBasket) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
                @endif
            </td>
            <td>
                @if($helpBasket->user_id == Auth::id())
                    <form method="POST" class="form-horizontal" action="{{ route('help_basket.destroy',$helpBasket) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger float-left" onclick="return confirm('¿Está seguro que desea eliminar la entrega de canasta a : {{$helpBasket->fullName}}? ' )"><i class="fas fa-trash-alt"></i></button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection