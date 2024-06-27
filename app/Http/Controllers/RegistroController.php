<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Dispositivo;
use App\Models\Sensor;
use Illuminate\Http\Request;
use DB;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dispositivo = Dispositivo::where('mac_address', $request->mac)->first();
        $sensor = Sensor::where('tipo', $request->descripcion)->first();

        if(!$dispositivo){
            return response()->json([
                'message' => 'Dispositivo no encontrado'
            ], 404);
        }

        //crear registro
        $registro = new Registro();
        $registro->sensors_id = $sensor->id;
        $registro->valor = $request->valor;
        $registro->fecha = now();
        $registro->save();

        return response()->json([
            'res' => 'success',
            'message' => 'Registro creado con éxito'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //encotrar id con la direccion MAC del dispositivo
        $dispositivo = Dispositivo::where('mac_address', $request->mac)->first();
        $sensor = Sensor::where('tipo', $request->descripcion)->first();

        if(!$dispositivo){
            return response()->json([
                'message' => 'Dispositivo no encontrado'
            ], 404);
        }

        if(!$sensor){
            return response()->json([
                'message' => 'Sensor no indentificado'
            ], 404); 
        }

        //crear registro
        $registro = new Registro();
        $registro->sensors_id = $sensor->id;
        $registro->valor = $request->valor;
        $registro->fecha = now();
        $registro->save();

        return response()->json([
            'res' => 'success',
            'message' => 'Registro creado con éxito'
        ], 200);
    }

    public function getDataFormat(Request $request){

        if($request->rango == 'Hoy') {
        $registros = DB::table('registros as r')
            ->join('sensors as s', 's.id', '=', 'r.sensors_id')
            ->select('r.fecha', 's.tipo', 'r.valor', 's.unidad')
            ->where('r.fecha', '>=', now()->startOfWeek())
            ->where('r.fecha', '<=', now()->endOfWeek())
            ->where('s.tipo', $request->tipo)
            ->get();

        // Hacer promedio por hora
        $data = [];
        $totalSuma = 0;
        $totalContador = 0;

        for ($i = 0; $i < 24; $i++) {
            $suma = 0;
            $contador = 0;
            foreach ($registros as $registro) {
                // Asegurarse de que $registro->fecha es una instancia de Carbon
                $fecha = new \Carbon\Carbon($registro->fecha);
                if ($fecha->hour == $i) {
                    $suma += $registro->valor;
                    $contador++;
                }
            }
            if ($contador != 0) {
                $promedio = $suma / $contador;
                $totalSuma += $suma;
                $totalContador += $contador;
                $data[$i] = [
                    'hora' => $i,
                    'promedio' => $promedio
                ];
            } else {
                $data[$i] = [
                    'hora' => $i,
                    'promedio' => null // Inicialmente no hay promedio
                ];
            }
        }

            // Calcular el promedio general
            $promedioGeneral = ($totalContador != 0) ? $totalSuma / $totalContador : 0;

            // Rellenar las horas sin registros con el promedio general
            foreach ($data as &$hora) {
                if ($hora['promedio'] === null) {
                    $hora['promedio'] = $promedioGeneral;
                }
            }

            return response()->json($data, 200);

        }else if($request->rango == 'Semana'){
            $registros = DB::table('registros as r')
                ->join('sensors as s', 's.id', '=', 'r.sensors_id')
                ->select('r.fecha', 's.tipo', 'r.valor', 's.unidad')
                ->where('r.fecha', '>=', now()->startOfWeek())
                ->where('r.fecha', '<=', now()->endOfWeek())
                ->where('s.tipo', $request->tipo)
                ->get();

            //hacer promedio por dia
            $data = [];
            for ($i=0; $i < 7; $i++) {
                $suma = 0;
                $contador = 0;
                foreach ($registros as $registro) {
                    // Asegurarse de que $registro->fecha es una instancia de Carbon
                    $fecha = new \Carbon\Carbon($registro->fecha);
                    if ($fecha->dayOfWeek == $i) {
                        $suma += $registro->valor;
                        $contador++;
                    }
                }
                if($contador != 0){
                    $promedio = $suma / $contador;
                    $data[] = [
                        'dia' => $i,
                        'promedio' => $promedio
                    ];
                }
            }
            return response()->json($data, 200);
        }
    }
}
