@extends('layouts.app')

@section('title', 'Bandeja de recepción')

@section('content')

<?php use Milon\Barcode\DNS1D; ?>


    @if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-4">
        <h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Casos creados</h3>
    </div>
    <div class="col-4"></div>
    <div class="col-4">
    </div>
</div>

<form method="GET" action="{{ route('lab.suspect_cases.reports.cases_with_barcodes') }}">
<!-------------------------->
<div class="row align-items-end mb-3">
    <div class="col-12 col-md-4 col-lg-4">
            <label for="for_establishment_id">Establecimiento</label>
            <select name="establishment_id" id="for_establishment_id" class="form-control" required>
                <option value=""> Seleccione Establecimiento</option>
                @foreach($establishments as $establishment)
                    <option
                        value="{{ $establishment->id }}" {{($establishment->id == $selectedEstablishment) ? 'selected' : '' }}>{{ $establishment->alias }}</option>
                @endforeach
            </select>

    </div>

    <div class="col-12 col-md-4 col-lg-4">
        <label for="for_sample_at">Toma Muestra</label>
        <input class="form-control" type="date" id="for_sample_at" name="sample_at" required value={{ ($selectedSampleAt) ? \Carbon\Carbon::parse($selectedSampleAt) : '' }}>



    </div>
    <div class="col-12 col-md-4 col-lg-4">
        <button type="submit" class="btn btn-primary float-left d-print-none"><i class="fa fa-search"></i> Buscar</button>
        <button type="button" class="btn btn-outline-secondary d-print-none ml-3" onclick="javascript:window.print()"> <i class="fa fa-print"></i> Imprimir </button>
    </div>

</div>
<!-------------------------->
<div class="form-group row">
    <div class="col-12 col-md-4 col-lg-4">
        @if(Auth::user()->laboratory)
            <h3>Laboratorio: {{ Auth::user()->laboratory->alias }}</h3>
        @else
            <h3 class="text-danger">Usuario no tiene laboratorio asignado</h3>
        @endif

    </div>
    <div class="col-12 col-md-4 col-lg-4">
    </div>
    <div class="col-12 col-md-4 col-lg-4 ">
        <b class="float-right">Exámenes en listado: {{ ($suspectCases) ? $suspectCases->count() : '0' }}</b>
    </div>


</div>
</form>

<!-------------------------->

<table class="table table-sm table-bordered table-responsive" id="tabla_casos">
    <thead>
        <tr>
            <th nowrap>° Monitor</th>
            <th>Fecha muestra</th>
            <th>Establecimiento</th>
            <th>Nombre</th>
            <th>Identificador</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>Observación</th>
            <th>Código Barra</th>
        </tr>
    </thead>
    <tbody id="tableCases">
    @if($suspectCases)
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pcr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">{{ $case->id }}</td>
            <td nowrap class="small">@date($case->sample_at)</td>
            <td>{{ ($case->establishment) ? $case->establishment->alias : '' }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                </a>
                @endif
            </td>
            <td class="text-center" nowrap>
                @if($case->patient)
                {{ $case->patient->identifier }}
                @endif
            </td>
            <td>{{ $case->age }}</td>
            <td>{{ strtoupper($case->gender[0]) }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
            <td><img class="mx-3 my-1" src="data:image/png;base64, <?php echo (new DNS1D)->getBarcodePNG($case->id, "C128", 2, 40); ?> " /></td>
        </tr>
        @endforeach
    @endif
    </tbody>
</table>

{{--{{ $suspectCases->appends(request()->query())->links() }}--}}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@endsection
