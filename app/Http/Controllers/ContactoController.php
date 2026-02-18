<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactos = Contacto::with('entidad')->get();
        return response()->json($contactos, Response::HTTP_OK);
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
        $validatedData = $request->validate([
            'entidad_id' => 'required|exists:entidades,id',
            'nombre' => 'required|string|max:191|unique:contactos,nombre',
            'email' => 'required|email|max:191|unique:contactos,email',
            'identificacion' => 'required|string|unique:contactos,identificacion',
            'telefono' => 'nullable|string|max:20',
        ]);

        $contacto = Contacto::create($validatedData);

        return response()->json($contacto, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacto $contacto)
    {
        $contacto = Contacto::with('entidad')->find($id);

        if (!$contacto) {
            return response()->json(['error' => 'Contacto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($contacto, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacto $contacto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacto $contacto)
    {
        
        $validatedData = $request->validate([
            'entidad_id' => 'sometimes|required|exists:entidades,id',
            'nombre' => ['sometimes', 'required', 'string', 'max:191', Rule::unique('contactos')->ignore($contacto->id)],
            'email' => ['sometimes', 'required', 'email', 'max:191', Rule::unique('contactos')->ignore($contacto->id)],
            'identificacion' => ['sometimes', 'required', Rule::unique('contactos')->ignore($contacto->id)],
            'telefono' => 'nullable|string|max:20',
        ]);

        $contacto->update($validatedData);

        return response()->json($contacto, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacto $contacto)
    {

        $contacto->delete();

        return response()->json(['message' => 'Contacto eliminado correctamente'], Response::HTTP_OK);
    }
}
