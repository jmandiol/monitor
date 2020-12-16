<?php

namespace App;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;

use App\Laboratory;
use App\SuspectCase;
use App\Commune;
use App\Country;
use App\File;


class WSMinsal extends Model
{

    public static function valida_crea_muestra($request)
    {
//        dd($request);

        $response = [];
        $client = new Client();

        // $genero = strtoupper($suspectCase->gender[0]);
        if($request->gender == "female"){$genero = "F";}
        elseif($request->gender == "male"){$genero = "M";}
        elseif($request->gender == "other"){$genero = "Intersex";} //intersex
        else{$request = "Desconocido";} //desconocido

        $commune_code_deis = Commune::find($request->commune_id)->code_deis;

        $paciente_ext_paisorigen = '';

        if($request->run == "") {
            $paciente_tipodoc = "PASAPORTE";
            $country = Country::where('name',$request->nationality)->get();
            // dd($country);e
            $paciente_ext_paisorigen = $country->first()->id_minsal;
        }
        else {
            $paciente_tipodoc = "RUN";
        }

        $codigo_muestra_cliente = SuspectCase::max('id');
//        dd($codigo_muestra_cliente);
        // $cod_deis = Laboratory::find(Auth::user()->laboratory_id);
        // dd($cod_deis);
        // dd($request->run_medic);
        $array = array(
            'raw' => array(
                'codigo_muestra_cliente' => $codigo_muestra_cliente + 1,
                'rut_responsable' => Auth::user()->run . "-" . Auth::user()->dv,
                'cod_deis' => Establishment::find($request->establishment_id)->new_code_deis,
                'rut_medico' => $request->run_medic_s_dv . "-" . $request->run_medic_dv, //'16350555-K', //Pedro Valjalo
                'paciente_run' => $request->run,
                'paciente_dv' =>  $request->dv,
                'paciente_nombres' => $request->name,
                'paciente_ap_pat' => $request->fathers_family,
                'paciente_ap_mat' => $request->mothers_family,
                'paciente_fecha_nac' => $request->birthday,
                'paciente_comuna' => $commune_code_deis,
                'paciente_direccion' => $request->address . " " . $request->number,
                'paciente_telefono' => $request->telephone,
                'paciente_tipodoc' => $paciente_tipodoc,
                'paciente_ext_paisorigen' => $paciente_ext_paisorigen,
                'paciente_pasaporte' => $request->other_identification,
                'paciente_sexo' => $genero,
                'paciente_prevision' => 'FONASA', //fijo por el momento
                'fecha_muestra' => date('Y-m-d H:i:s'),
                'tecnica_muestra' => 'RT-PCR', //fijo
                'tipo_muestra' => $request->sample_type,
                'busqueda_activa' => ($request->case_type == 'Busqueda activa') ? 'true' : 'false'

            )
        );

        // dd(env('WS_VALIDA_CREAR_MUESTRA'), Laboratory::find(Auth::user()->laboratory_id)->token_ws);
        try {
            $response = $client->request('POST', env('WS_VALIDA_CREAR_MUESTRA'), [
                'json' => $array,
                'headers'  => [ 'ACCESSKEY' => Laboratory::whereNotNull('token_ws')->get()->first()->token_ws] //se permite el uso de cualuier access key
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            // $suspectCase->minsal_ws_id = $array[0]['id_muestra'];
            // $suspectCase->save();
            $response = ['status' => 1, 'msg' => $array[0]['id_muestra']];

        } catch (RequestException $e) {
//            $response = $e->getResponse();
//            if($response){
////                dd($response);
//
//                $responseBodyAsString = $response->getBody()->getContents();
//                $decode = json_decode($responseBodyAsString);
////                dd($decode);
//                $response = ['status' => 0, 'msg' => $decode->error];
//            }
//            else{
                $response = ['status' => 0, 'msg' => $e->getMessage()];
//            }
        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];
        } catch (GuzzleException $e) {
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];

        }

        return $response;
    }

    public static function crea_muestra(SuspectCase $suspectCase) {

        $response = [];
        $client = new Client();

        // $genero = strtoupper($suspectCase->gender[0]);
        if($suspectCase->gender == "female"){$genero = "F";}
        elseif($suspectCase->gender == "male"){$genero = "M";}
        elseif($suspectCase->gender == "other"){$genero = "Intersex";} //intersex
        else{$genero = "Desconocido";} //desconocido

        $commune_code_deis = Commune::find($suspectCase->patient->demographic->commune_id)->code_deis;

        $paciente_ext_paisorigen = '';

        if($suspectCase->patient->run == "") {
            $paciente_tipodoc = "PASAPORTE";
            $country = Country::where('name',$suspectCase->patient->demographic->nationality)->get();
            $paciente_ext_paisorigen = $country->first()->id_minsal;
        }
        else {
            $paciente_tipodoc = "RUN";
        }


        if ($suspectCase->run_medic == "0-0"
            || $suspectCase->run_medic == "25540525-k"
            || $suspectCase->run_medic == "25540525"
            || $suspectCase->run_medic == "26128476-6"
            || $suspectCase->run_medic == "15685849-8"
            || $suspectCase->run_medic == "13867622-6"
            || $suspectCase->run_medic == "17430962-0"
        ) {
            $run_medic = "16350555-K";
        }else{
            $run_medic = $suspectCase->run_medic;
        }

        $array = array(
            'raw' => array(
                'codigo_muestra_cliente' => $suspectCase->id,
                'rut_responsable' => $suspectCase->user->run . "-" . $suspectCase->user->dv,//'15980951-K', //Claudia Caronna
                'cod_deis' => $suspectCase->establishment->new_code_deis, //'102100', //$request->establishment_id
                'rut_medico' => $run_medic,//$suspectCase->run_medic, //'16350555-K', //Pedro Valjalo
                'paciente_run' => $suspectCase->patient->run,
                'paciente_dv' => $suspectCase->patient->dv,
                'paciente_nombres' => $suspectCase->patient->name,
                'paciente_ap_pat' => $suspectCase->patient->fathers_family,
                'paciente_ap_mat' => $suspectCase->patient->mothers_family,
                'paciente_fecha_nac' => $suspectCase->patient->birthday,
                'paciente_comuna' => $commune_code_deis,
                'paciente_direccion' => $suspectCase->patient->demographic->address . " " . $suspectCase->patient->demographic->number,
                'paciente_telefono' => $suspectCase->patient->demographic->telephone,
                'paciente_tipodoc' => $paciente_tipodoc,
                'paciente_ext_paisorigen' => $paciente_ext_paisorigen,
                'paciente_pasaporte' => $suspectCase->patient->other_identification,
                'paciente_sexo' => $genero,
                'paciente_prevision' => 'FONASA', //fijo por el momento
                'fecha_muestra' => $suspectCase->sample_at,
                'tecnica_muestra' => 'RT-PCR', //fijo
                'tipo_muestra' => $suspectCase->sample_type,
                'busqueda_activa' => ($suspectCase->case_type == 'Busqueda activa') ? 'true' : 'false',
                'id_laboratorio' => $suspectCase->laboratory->id_openagora

            )
        );

//        dd(json_encode($array, JSON_PRETTY_PRINT) );

        try {
            $response = $client->request('POST', env('WS_CREAR_MUESTRA'), [
                'json' => $array,
                'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            $suspectCase->minsal_ws_id = $array[0]['id_muestra'];
            $suspectCase->save();
            $response = ['status' => 1, 'msg' => $array[0]['id_muestra']];

        } catch (RequestException $e) {
//            $response = $e->getResponse();
//            if($response){
//                $responseBodyAsString = $response->getBody()->getContents();
//                $decode = json_decode($responseBodyAsString);
//                $response = ['status' => 0, 'msg' => $decode->error];
//            }
//            else{
//                $response = ['status' => 0, 'msg' => 'No se pudo conectar a plataforma de toma de muestras. Por favor intente nuevamente en un momento.'];
//            }

            $response = ['status' => 0, 'msg' => $e->getMessage()];

        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];
        }

        return $response;
    }



    public static function recepciona_muestra(SuspectCase $suspectCase) {

        $minsal_ws_id = $suspectCase->minsal_ws_id;
        $response = [];
        $client = new Client();
        $array = array('raw' => array('id_muestra' => $minsal_ws_id));

        try {
            $response = $client->request('POST', env('WS_RECEPCIONA_MUESTRA'), [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
            ]);

            $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
//            $response = $e->getResponse();
//            if($response){
//                $responseBodyAsString = $response->getBody()->getContents();
//                $decode = json_decode($responseBodyAsString);
//                $response = ['status' => 0, 'msg' => $decode->error];
//            }
//            else{
//                $response = ['status' => 0, 'msg' => 'No se pudo conectar a plataforma de toma de muestras. Por favor intente nuevamente en un momento.'];
//            }
            $response = ['status' => 0, 'msg' => $e->getMessage()];

        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];
        }

        return $response;
    }



    public static function resultado_muestra(SuspectCase $suspectCase) {
        $pdf = NULL;
        if ($suspectCase->laboratory) {
            if ($suspectCase->laboratory->pdf_generate) {
                $case = $suspectCase;
                $pdf = \PDF::loadView('lab.results.result', compact('case'));
//                dd($pdf);
            }
        }

        $resultado = $suspectCase->covid19;

        $client = new Client();

        try {
            if ($pdf == NULL) {
                if ($resultado == "Muestra no apta") {
                    print_r("aquí: " . $suspectCase->minsal_ws_id);
                    $response = $client->request('POST', env('WS_RESULTADO_MUESTRA'), [
                        'multipart' => [
                            [
                                'name'     => 'parametros',
                                'contents' => '{"id_muestra":"' . $suspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                            ]
                        ],
                        'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
                    ]);
                }else {
                    $response = $client->request('POST', env('WS_RESULTADO_MUESTRA'), [
                        'multipart' => [
                            [
                                'name'     => 'upfile',
                                // 'contents' => Storage::get($suspectCase->files->first()->file),
                                // 'filename' => $suspectCase->files->first()->name
                                'contents' => Storage::get('suspect_cases/' . $suspectCase->id . '.pdf'),
                                'filename' => $suspectCase->id . '.pdf'
                            ],
                            [
                                'name'     => 'parametros',
                                'contents' => '{"id_muestra":"' . $suspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                            ]
                        ],
                        'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
                    ]);
                }

            }else{
                $response = $client->request('POST', env('WS_RESULTADO_MUESTRA'), [
                    'multipart' => [
                        [
                            'name'     => 'upfile',
                            'contents' => $pdf->output(),
                            'filename' => 'Resultado.pdf'
                        ],
                        [
                            'name'     => 'parametros',
                            'contents' => '{"id_muestra":"' . $suspectCase->minsal_ws_id .'","resultado":"' . $resultado .'"}'
                        ]
                    ],
                    'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
                ]);
            }
            $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
//            $response = $e->getResponse();
//            if($response){
//                $responseBodyAsString = $response->getBody()->getContents();
//                $decode = json_decode($responseBodyAsString);
//                $response = ['status' => 0, 'msg' => $decode->error];
//            }
//            else{
//                $response = ['status' => 0, 'msg' => 'No se pudo conectar a plataforma de toma de muestras. Por favor intente nuevamente en un momento.'];
//            }

            $response = ['status' => 0, 'msg' => $e->getMessage()];

        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];
        }

        return $response;
    }

    public static function cambia_laboratorio(SuspectCase $suspectCase, $laboratory_id) {

        $minsal_ws_id = $suspectCase->minsal_ws_id;
        $response = [];
        $client = new Client();
        $array = array('raw' => array('id_muestra' => $minsal_ws_id,
                                      'id_nuevo_laboratorio' => $laboratory_id));

        try {
            $response = $client->request('POST', env('WS_CAMBIA_LABORATORIO'), [
                  'json' => $array,
                  'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
            ]);

            $response = ['status' => 1, 'msg' => 'OK'];

        } catch (RequestException $e) {
//            $response = $e->getResponse();
//            if($response){
//                $responseBodyAsString = $response->getBody()->getContents();
//                $decode = json_decode($responseBodyAsString);
//                $response = ['status' => 0, 'msg' => $decode->error];
//            }
//            else{
//                $response = ['status' => 0, 'msg' => 'No se pudo conectar a plataforma de toma de muestras. Por favor intente nuevamente en un momento.'];
//            }
            $response = ['status' => 0, 'msg' => $e->getMessage()];

        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];
        }

        return $response;
    }

    /**
     * @param \App\SuspectCase $suspectCase
     * @throws GuzzleException
     *
     */
    public static function obtiene_estado_muestra(SuspectCase $suspectCase){
        $response = [];
        $client = new Client();
        try {
            $response = $client->request('POST', env('WS_DATOS_MUESTRA_ID'), [
                'multipart' => [
                    [
                        'name'     => 'parametros',
                        'contents' => '{"id_muestra": ' . $suspectCase->minsal_ws_id .'}'
                    ]
                ],
                'headers'  => [ 'ACCESSKEY' => $suspectCase->laboratory->token_ws]
            ]);

            $array = json_decode($response->getBody()->getContents(), true);
            $response = ['status' => 1, 'sample_status' => $array[0]['estado_muestra']];

        }catch (RequestException $e){
            $response = ['status' => 0, 'msg' => $e->getMessage()];
        }catch (Exception $e){
            $response = ['status' => 0, 'msg' => "Error inesperado en conexión a plataforma de toma de muestras. Por favor intente nuevamente en un momento. {$e->getMessage()}"];

        }

        return $response;
    }

}
