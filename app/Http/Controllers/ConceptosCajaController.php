<?php

namespace App\Http\Controllers;

use App\Models\ConceptosCaja;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConceptosCajaRequest;
use App\Http\Requests\UpdateConceptosCajaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConceptosCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pag=0)
    {

        $query = ConceptosCaja::select("id","tipo","descripcion");
        // if($busqueda !=""){
        //     $query = $query->where("usuario.nombre_usuario","like",$busqueda)->orWhere("usuario.nombre","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda)->orWhere("usuario.apellido","like",$busqueda);
        // }
        $query = $query->orderBy("descripcion");

        return ["cod"=>"00","msg"=>"todo correcto","datos"=>$query->get()];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreConceptosCajaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConceptosCajaRequest $request)
    {
        try {
            // $campos = $this->validate($request,[
            //     "tipo"=>"required|string",
            //     "descripcion"=> "required|string|regex:/(ENTRADA)"
            // ]);

            $campos = $request->validate([
                "tipo"=>['required','string' , 'regex:/ENTRADA|SALIDA/'],
                "descripcion"=> "required|string"
            ]);

            $ConceptosCaja = ConceptosCaja::create($campos);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
        return ["cod"=>"00","msg"=>"todo correcto"];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConceptosCaja  $conceptosCaja
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $ConceptosCaja = ConceptosCaja::findOrfail($id);
            return ["cod"=>"00","msg"=>"todo correcto","datos"=>[$ConceptosCaja]];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        } catch (\Exception $e) {
            return ["cod"=>"99","msg"=>"Error general","error"=>$e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConceptosCaja  $conceptosCaja
     * @return \Illuminate\Http\Response
     */
    public function edit(ConceptosCaja $conceptosCaja){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConceptosCajaRequest  $request
     * @param  \App\Models\ConceptosCaja  $conceptosCaja
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateConceptosCajaRequest $request, $id)
    {
        try {
            $ConceptosCaja = ConceptosCaja::findOrfail($id);
            $campos = $this->validate($request,[
                "tipo"=>['required','string' , 'regex:/ENTRADA|SALIDA/'],
                "descripcion"=>"required|string"
            ]);

            $ConceptosCaja->update($campos);
            return ["cod"=>"00","msg"=>"todo correcto"];

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ["cod"=>"06","msg"=>"Error al insertar los datos","errores"=>[$e->errors() ]];

        } catch (\Exception $e) {
            return ["cod"=>"05","msg"=>"Error al insertar los datos","error"=>$e->getMessage()];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConceptosCaja  $conceptosCaja
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($id === "1" || $id === "2"){
                return ["cod"=>"11","msg"=>"No se puede eliminar este concepto caja"];
            }
            
            $ConceptosCaja = ConceptosCaja::findOrfail($id);
            $ConceptosCaja->delete();

            return ["cod"=>"00","msg"=>"Eliminado correctamente"];
        } catch( ModelNotFoundException $e){
            return ["cod"=>"04","msg"=>"no existen datos","error"=>$e->getMessage()];
        }  catch (\Exception $e) {
            return ["cod"=>"08","msg"=>"Error al eliminar el registro","errores"=>[$e->getMessage() ]];
        }
    }
}
