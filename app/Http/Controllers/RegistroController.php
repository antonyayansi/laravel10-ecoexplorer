<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Dispositivo;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dispositivo = Dispositivo::where('mac_address', $request->mac)->first();

        if(!$dispositivo){
            return response()->json([
                'message' => 'Dispositivo no encontrado'
            ], 404);
        }

        //crear registro
        $registro = new Registro();
        $registro->dispositivos_id = $dispositivo->id;
        $registro->descripcion = $request->descripcion;
        $registro->valor = $request->valor;
        $registro->unidad = $request->unidad;
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

        if(!$dispositivo){
            return response()->json([
                'message' => 'Dispositivo no encontrado'
            ], 404);
        }

        //crear registro
        $registro = new Registro();
        $registro->dispositivos_id = $dispositivo->id;
        $registro->descripcion = $request->descripcion;
        $registro->valor = $request->valor;
        $registro->unidad = $request->unidad;
        $registro->fecha = now();
        $registro->save();

        return response()->json([
            'res' => 'success',
            'message' => 'Registro creado con éxito'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Registro $registro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registro $registro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registro $registro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registro $registro)
    {
        //
    }
}
