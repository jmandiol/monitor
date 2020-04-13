@extends('layouts.app')

@section('title', 'Ver booking')

@section('content')

@include('sanitary_hotels.nav')


<u>
    <strong>
        <h3 class="mb-3" align="center">{{ $booking->patient->fullName }}</h3>
        <h4 class="mb-3" align="center">{{ $booking->room->hotel->name }}</h4>
        <h5 class="mb-3" align="center">Habitacion:{{ $booking->room->number }}</h5>
    </strong>
</u>

<hr>
<h5 class="mb-3" align="center">Ingreso:{{ $booking->from->format('d-m-Y H:i') }} - Salida Estimada:{{ $booking->to->format('d-m-Y H:i') }}</h5>
<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Run o (ID)</th>                
                <th>Genero</th>
                <th>Fecha Nac.</th>
                <th>Dirección/Comuna</th>
                <th>Teléfono/Email</th>
                <th>Procedencia</th>
                <th>Fecha Muestra</th>
                <th>Resultado</th>
                <th>Fecha Entrega de Resultado Lab</th>
            </tr>
        </thead>
        <tbody id="tablePatients">
        <td class="text-center">{{ $booking->patient->identifier }}</td>
        <td>{{ $booking->patient->genderEsp }}</td>
        <td nowrap>{{ $booking->patient->birthday }}</td>
        <td class="small">
                {{ ($booking->patient->demographic)?$booking->patient->demographic->address:'' }}
                {{ ($booking->patient->demographic)?$booking->patient->demographic->number:'' }}<br>
                {{ ($booking->patient->demographic)?$booking->patient->demographic->commune:'' }}
            </td>
            <td class="small">
                {{ ($booking->patient->demographic)?$booking->patient->demographic->telephone:'' }}<br>
                {{ ($booking->patient->demographic)?$booking->patient->demographic->email:'' }}
            </td>
            <td>{{ $booking->patient->suspectCases->first()->origin }}</td>
            <td>{{ ($booking->patient->suspectCases->first()->sample_at->format('d-m-Y') ) }}</td>
            <td>{{ $booking->patient->suspectCases->first()->covid19 }}</td>
            <td>{{ $booking->patient->suspectCases->first()->pscr_sars_cov_2_at }}</td>

        </tbody>
    </table>
</div>
<hr>

@if ($booking->indications <> null)

    <label for="for_indications">Indicaciones</label>
    <p readonly class="form-control" id="for_indications" rows="3" name="indications">{{ $booking->indications }}</p>
    <hr>
    @endif

    @if ($booking->observations <> null)
        <label for="for_observations">Observaciones</label>
        <p readonly class="form-control" rows="3" name="observations" id="for_observations">{{ $booking->observations }}</p>
        <hr>
        @endif










        @include('sanitary_hotels.vital_signs.partials.create', compact('booking'))

        <hr>

        @include('sanitary_hotels.vital_signs.partials.index', compact('booking'))

        @endsection

        @section('custom_js')

        @endsection