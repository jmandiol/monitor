@extends('layouts.app')

@section('title', 'Contacto Pacientes')

@section('content')

<h3>Agregar Contacto de Pacientes</h3>

<br>

<h5>Paciente:</h5>
<div class="form-row">
  @foreach($patients as $patient)
    <fieldset class="form-group col-md-3">
        <label for="for_register_at">RUN</label>
        <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $patient->Identifier }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_register_at">Nombre</label>
        <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $patient->name }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_fathers_family">Apellido Paterno</label>
        <input type="text" class="form-control" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_mothers_family">Apellido Materno</label>
        <input type="text" class="form-control" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>
  @endforeach
</div>

<hr>

<form method="GET" class="form-horizontal" action="{{ route('patients.contacts.create', ['search'=>'search_true', 'id' => $id_patient]) }}">
<div class="input-group mb-sm-0">
    <div class="input-group-prepend">
        <span class="input-group-text">Búsqueda</span>
    </div>

    <input class="form-control" type="text" name="run" autocomplete="off" id="for_run" placeholder="RUN (sin dígito verificador) o OTRA IDENTIFICACION" value="{{$request->run}}" required>

    <input class="form-control" type="text" name="dv" id="for_dv" style="text-transform: uppercase;" placeholder="DV" readonly hidden>

    <div class="input-group-append">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>
</div>
</form>

<br>

<h5>Contacto:</h5>
@if($s == 'search_true' && $message == 'dont exist')
        <div class="alert alert-danger" role="alert">
            El paciente consultado no se encuentra en nuestros registros.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        @can('Patient: create')
        <div class="col-4 col-sm-3">
            <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}" target="-_blank">
                <i class="fas fa-plus"></i> Crear Paciente
            </a>
        </div>
        @endcan
@endif
@if($s == 'search_true' && $message == 'same patient')
        <div class="alert alert-danger" role="alert">
            No puedes asignar al mismo paciente como contacto.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif
@if($s == 'search_true' && $message == 'contact already registered')
        <div class="alert alert-success" role="alert">
            El contacto entre pacientes ya fue registrado anteriormente, ingrese otro RUN.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif
@if($s == 'search_true' && $message == 'new contact')
@foreach($contacts as $patient)
    <div class="form-row">
        <fieldset class="form-group col-md-4">
            <label for="for_register_at">Nombre</label>
            <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $patient->name }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;" readonly>
        </fieldset>
    </div>
    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_name">Género</label>
            <input type="text" class="form-control" value="{{ $patient->sexEsp }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Fecha Nac.</label>
            <input type="text" class="form-control" value="{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}" readonly>
        </fieldset>
    </div>
    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Comuna: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->commune->name:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">Dirección: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->address:'' }}  {{($patient->demographic)?$patient->demographic->number:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Teléfono: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->telephone:'' }}" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">E-mail: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->email:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>
    </div>
    <hr>


<div class="card mb-3">
    <div class="card-body">
        <h5>Ingreso los datos del contacto:</h5>

        <form method="POST" class="form-horizontal" action="{{ route('patients.contacts.store') }}">
            @csrf
            @method('POST')
            <div class="form-row">
              <fieldset class="form-group col-md-3">
                  <label for="for_last_contact_at">Fecha último contacto *</label>
                  <input type="datetime-local" class="form-control" name="last_contact_at" id="for_last_contact_at" value="" required>
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_category">Categoría *</label>
                  <select class="form-control country" name="category" id="for_category" title="Seleccione..." data-live-search="true" data-size="5" required>
                      <option value="institutional">Institucional</option>
                      <option value="ocupational">Laboral</option>
                      <option value="passenger">Pasajero</option>
                      <option value="social">Social</option>
                      <option value="waiting room">Sala de espera</option>
                      <option value="family">Familiar/domiciliario</option>
                      <option value="functionary">Personal de salud</option>
                      <!-- <option value="intradomiciliary">Intradomiciliario</option> -->
                  </select>
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_register_at">Parentesco</label>
                  <select class="form-control" name="relationship" id="for_relationship" title="Seleccione..." data-live-search="true" data-size="5" disabled>
                      @if($patient->sexEsp == 'Femenino')
                        <option value=""></option>
                        <option value="grandmother">Abuela</option>
                        <option value="sister in law">Cuñada</option>
                        <option value="wife">Esposa</option>
                        <option value="sister">Hermana</option>
                        <option value="daughter">Hija</option>
                        <option value="mother">Madre</option>
                        <option value="cousin">Primo/a</option>
                        <option value="niece">Sobrina</option>
                        <option value="mother in law">Suegra</option>
                        <option value="aunt">Tía</option>
                        <option value="grandchild">Nieta</option>
                        <option value="daughter in law">Nuera</option>
                        <option value="girlfriend">Pareja</option>
                        <option value="other">Otro</option>
                      @elseif($patient->sexEsp == 'Masculino')
                        <option value=""></option>
                        <option value="grandfather">Abuelo</option>
                        <option value="brother in law">Cuñado</option>
                        <option value="husband">Esposo</option>
                        <option value="brother">Hermano</option>
                        <option value="son">Hijo</option>
                        <option value="grandchild">Nieto</option>
                        <option value="father">Padre</option>
                        <option value="boyfriend">Pareja</option>
                        <option value="cousin">Primo/a</option>
                        <option value="nephew">Sobrino</option>
                        <option value="father in law">Suegro</option>
                        <option value="uncle">Tío</option>
                        <option value="son in law">Yerno</option>
                        <option value="other">Otro</option>
                      @else
                        <option value=""></option>
                        <option value="grandmother">Abuela</option>
                        <option value="grandfather">Abuelo</option>
                        <option value="sister in law">Cuñada</option>
                        <option value="brother in law">Cuñado</option>
                        <option value="wife">Esposa</option>
                        <option value="husband">Esposo</option>
                        <option value="sister">Hermana</option>
                        <option value="brother">Hermano</option>
                        <option value="daughter">Hija</option>
                        <option value="son">Hijo</option>
                        <option value="mother">Madre</option>
                        <option value="father">Padre</option>
                        <option value="cousin">Primo/a</option>
                        <option value="niece">Sobrina</option>
                        <option value="nephew">Sobrino</option>
                        <option value="mother in law">Suegra</option>
                        <option value="father in law">Suegro</option>
                        <option value="aunt">Tía</option>
                        <option value="uncle">Tío</option>
                        <option value="grandchild">Nieta/o</option>
                        <option value="daughter in law">Nuera</option>
                        <option value="son in law">Yerno</option>
                        <option value="girlfriend">Pareja (Femenino)</option>
                        <option value="boyfriend">Pareja (Masculino)</option>
                        <option value="other">Otro</option>
                      @endif
                  </select>
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_live_together">¿Viven Juntos?</label>
                  <select class="form-control selectpicker" name="live_together" id="for_live_together" title="Seleccione..." data-size="2">
                      <option value="1">Si</option>
                      <option value="0">No</option>
                  </select>
              </fieldset>

                <fieldset class="form-group col-md-3 mode_of_transport" style="display: none">
                    <label for="for_mode_of_transport">Tipo de transporte *</label>
                    <select class="form-control" name="mode_of_transport" id="for_mode_of_transport" title="Seleccione...">
                        <option value=""></option>
                        <option value="terrestre">Terrestre</option>
                        <option value="aereo">Aéreo</option>
                        <option value="maritimo">Marítimo</option>
                    </select>
                </fieldset>

                <fieldset class="form-group col-md-3 flight_name" style="display: none">
                    <label for="for_flight_name">Nombre de vuelo</label>
                    <input class="form-control" name="flight_name" id="for_flight_name"/>
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-4 col-lg-3 flight_date" style="display: none">
                    <label for="for_flight_date">Fecha de vuelo</label>
                    <input type="date" class="form-control" name="flight_date"
                           id="for_flight_date">
                </fieldset>

                <fieldset class="form-group col-md-3 waiting_room_establishment" style="display: none">
                    <label for="for_waiting_room_establishment">Establecimiento de sala de espera</label>
                    <input class="form-control" name="waiting_room_establishment" id="for_waiting_room_establishment"/>
                </fieldset>

                <fieldset class="form-group col-md-3 social_meeting_place" style="display: none">
                    <label for="for_social_meeting_place">Lugar de encuentro social</label>
                    <input class="form-control" name="social_meeting_place" id="for_social_meeting_place"/>
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-4 col-lg-3 social_meeting_date" style="display: none">
                    <label for="for_social_meeting_date">Fecha de encuentro social</label>
                    <input type="date" class="form-control" name="social_meeting_date"
                           id="for_social_meeting_date">
                </fieldset>

                <fieldset class="form-group col-md-3 company_name" style="display: none">
                    <label for="for_company_name">Nombre de empresa</label>
                    <input class="form-control" name="company_name" id="for_company_name"/>
                </fieldset>

                <fieldset class="form-group col-md-3 functionary_profession" style="display: none">
                    <label for="for_functionary_profession">Prefesión de personal de salud</label>
                    <input class="form-control" name="functionary_profession" id="for_functionary_profession"/>
                </fieldset>

                <fieldset class="form-group col-md-3 institution" style="display: none">
                    <label for="for_institution">Nombre institución</label>
                    <input class="form-control" name="institution" id="for_institution"/>
                </fieldset>

              <!-- <fieldset class="form-group col-md-3">
                  <label for="for_notification_contact_at">Fecha de notificación de contacto:</label>
                  <input type="datetime-local" class="form-control" name="notification_contact_at" id="for_notification_contact_at" value="">
              </fieldset> -->
            </div>
            <div class="form-row">
                <fieldset class="form-group col-md-12">
                    <label for="for_comment">Observación</label>
                    <textarea class="form-control" name="comment"  id="for_comment" rows="1"></textarea>
                </fieldset>
            </div>
            {{--CREACION SEGUIMIENTO Y ENVIO A EPIVIGILA--}}
            <h5>Seguimiento:</h5>
            <div class="form-row">
                <fieldset class="form-group col-md-2">
                    <label for="for_category"
                        @if($patient->tracing)
                            hidden
                        @endif
                    >Crear seguimiento *</label>
                    <select class="form-control" name="create_tracing" id="for_create_tracing" title="Seleccione..." required
                        @if($patient->tracing)
                            hidden disabled
                        @endif
                    >
                        <option value=true>Si</option>
                        <option value=false>No</option>
                    </select>
                </fieldset>

                <fieldset class="form-group col-md-4">
                    <label for="for_establishment_id">Establecimiento que realiza seguimiento *</label>
                    <select name="establishment_id" id="for_establishment_id" class="form-control" required
                        @if(auth()->user()->cannot('Tracing: change') AND $patient->tracing AND $patient->tracing->establishment_id)
                            disabled
                        @endif
                    >
                        <option value=""></option>
                        @foreach($establishments as $estab)
                            <option
                                value="{{ $estab->id }}" {{ ($patient->tracing) ? (($patient->tracing->establishment_id == $estab->id) ? 'selected' : '') : '' }}>{{ $estab->alias }}</option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-4 col-lg-3">
                    <label for="for_quarantine_start_at">Inicio Cuarentena *</label>
                    <input type="date" class="form-control" name="quarantine_start_at"
                           id="for_quarantine_start_at" required
                           value="{{ ($patient->tracing) ? (($patient->tracing->quarantine_start_at) ? $patient->tracing->quarantine_start_at->format('Y-m-d') : '') : '' }}">
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-4 col-lg-3">
                    <label for="for_quarantine_end_at">Término de Cuarentena *</label>
                    <input type="date" class="form-control" name="quarantine_end_at"
                           id="for_quarantine_end_at" required
                           value="{{ ($patient ->tracing) ? (($patient->tracing->quarantine_end_at) ? $patient->tracing->quarantine_end_at->format('Y-m-d') : '') : '' }}">
                </fieldset>

            </div>



            <div class="form-row">
              <fieldset class="form-group col-md-3" hidden>
                  <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $id_patient }}">
              </fieldset>

              <fieldset class="form-group col-md-3" hidden>
                  <input type="text" class="form-control" name="contact_id" id="for_contact_id" value="{{ $patient->id }}">
              </fieldset>

              <fieldset class="form-group col-md-3" hidden>
                  <input type="text" class="form-control" name="user_id" id="for_user_id" value="{{ Auth::id() }}">
              </fieldset>

              <fieldset class="form-group col-md-3" hidden>
                  <input type="text" class="form-control" name="index" id="for_index" value="1">
              </fieldset>

              <hr>
              <button type="submit" class="btn btn-primary float-right">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endif

@endsection

@section('custom_js')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        //obtiene digito verificador
        $('input[name=search]').keyup(function(e) {
            var str = $("#for_search").val();
            $('#for_dv').val($.rut.dv(str));
        });
    });
</script>

<script type="text/javascript">
$(document).ready(function(){
    // $("main").removeClass("container");

    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tablePatients tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("select.country").change(function(){
        const selectedcategory = $(this).children("option:selected").val();
        if(selectedcategory ==='family'){
            $('#for_relationship').prop('disabled', false);
            $('#for_relationship').prop("required", true);
        }
        else{
            $('#for_relationship').val('');
            $('#for_relationship').prop('disabled', true);

        }

        if(selectedcategory === 'passenger'){
            $('.mode_of_transport').show();
            $('#for_mode_of_transport').prop("required", true);

            selectedTransportMode = $('#for_mode_of_transport').children("option:selected").val();
            if(selectedTransportMode === 'aereo'){
                $('.flight_name').show();
                $('.flight_date').show();
            }
        }
        else {
            $('.mode_of_transport').hide();
            $('#for_mode_of_transport').prop("required", false);
            $('.flight_name').hide();
            $('.flight_date').hide();
        }

        if(selectedcategory === 'waiting room') {
            $('.waiting_room_establishment').show();
        }
        else {
            $('.waiting_room_establishment').hide();
        }

        if(selectedcategory === 'social'){
            $('.social_meeting_place').show();
            $('.social_meeting_date').show();
        }
        else {
            $('.social_meeting_place').hide();
            $('.social_meeting_date').hide();
        }

        if(selectedcategory === 'ocupational') {
            $('.company_name').show();
        }
        else {
            $('.company_name').hide();
        }

        if(selectedcategory === 'functionary') {
            $('.functionary_profession').show();
        }
        else {
            $('.functionary_profession').hide();
        }

        if(selectedcategory === 'institutional') {
            $('.institution').show();
        }
        else {
            $('.institution').hide();
        }

    });

    $("#for_mode_of_transport").change(function (){
        const selectedMode = $(this).children("option:selected").val();

        if(selectedMode === 'aereo'){
            $('.flight_name').show();
            $('.flight_date').show();
        }
        else {
            $('.flight_name').hide();
            $('.flight_date').hide();
        }
    })

    $('#for_create_tracing').change(function (){
        const selectedOption = $(this).children("option:selected").val();
        if(selectedOption === 'true'){
            $('#for_establishment_id').prop('disabled', false);
            $('#for_establishment_id').prop('required', true);
            $('#for_quarantine_start_at').prop('disabled', false);
            $('#for_quarantine_start_at').prop('required', true);
            $('#for_quarantine_end_at').prop('disabled', false);
            $('#for_quarantine_end_at').prop('required', true);
        }
        else {
            $('#for_establishment_id').prop('disabled', true);
            $('#for_establishment_id').prop('required', false);
            $('#for_quarantine_start_at').prop('disabled', true);
            $('#for_quarantine_start_at').prop('required', false);
            $('#for_quarantine_end_at').prop('disabled', true);
            $('#for_quarantine_end_at').prop('required', false);
        }
    });

    $('#for_last_contact_at').change(function (){
        if(!document.getElementById('for_quarantine_start_at').value){
            const selectedDate = $(this).val().split('T')[0];
            $('#for_quarantine_start_at').val(selectedDate);

            const dateEnd = new Date($(this).val());
            dateEnd.setDate(dateEnd.getDate() + 13)

            $('#for_quarantine_end_at').val(dateEnd.toISOString().split('T')[0]);
        }
    });


});

</script>
@endsection
