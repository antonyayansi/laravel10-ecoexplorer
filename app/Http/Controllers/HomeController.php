<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function rangos(Request $request)
    {
        switch($request->tipo){
            case 'day':
                //array de las 24 horas del día
                $rango = array();
                for($i = 0; $i < 24; $i++){
                    $rango[] = $i;
                }
                return response()->json([
                    'rango' => $rango
                ], 200);
                break;
            case 'week':
                //array de los 7 días de la semana
                $rango = array();
                for($i = 1; $i <= 7; $i++){
                    $rango[] = $i;
                }
                return response()->json([
                    'rango' => $rango
                ], 200);
                break;
            default:
                return response()->json([
                    'error' => 'Tipo de rango no válido'
                ], 500);
        }
    }
}
