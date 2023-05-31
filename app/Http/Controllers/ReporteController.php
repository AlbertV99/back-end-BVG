<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Operaciones;
use App\Models\Cliente;
use App\Models\Usuario;
use PDF;

class ReporteController extends Controller
{
    public function movimientosCaja($fechaInicio,$fechaFin){

        $query  = Operaciones::select("operacion.id","operacion.caja","operacion.concepto","operacion.saldo_anterior","operacion.monto",
        "operacion.saldo_posterior","operacion.fecha_operacion","operacion.solicitud_id","operacion.usuario_id",
        "caja.descripcion as caja_descripcion","conceptos_caja.descripcion as concepto_caja","usuario.nombre_usuario as nombre_usuario")
        ->join("caja", "caja.id", "operacion.caja")
        ->join("conceptos_caja","conceptos_caja.id","operacion.concepto")
        ->join("usuario","usuario.id","operacion.usuario_id")
        ->orderBy("operacion.fecha_operacion");

        // if ($fechaInicio && $fechaFin) {
            $query->whereBetween('operacion.fecha_operacion', [$fechaInicio, $fechaFin]);
        // }

        $datos = $query->get();

        $data = [
            'title' => 'Reporte movimientos de caja por fechas',
            'datos' => $datos
        ];

        $pdf = PDF::loadView('movimientosCaja', $data);

        return $pdf->download('reporteCaja.pdf');
    }

    public function clientes()
    {
        $datos = Cliente::select("barrio.nombre as barrio","cliente.documento","cliente.nombre","cliente.apellido","cliente.tipo_documento","cliente.f_nacimiento","cliente.correo")
        ->join("barrio", "barrio.id", "cliente.barrio")
        ->orderBy("cliente.apellido")->get();
        $cantidadClientes = Cliente::count();

        $data = [
            'title' => 'Reporte clientes',
            'datos' => $datos,
            'cantidadClientes' => $cantidadClientes
        ];

        $pdf = PDF::loadView('clientes', $data);

        return $pdf->download('reporteCliente.pdf');
    }

    public function usuarios()
    {
        $datos = Usuario::select("usuario.nombre_usuario","usuario.nombre","usuario.apellido","usuario.cedula","perfil.descripcion")->join("perfil","perfil.id","usuario.perfil_id")
        ->orderBy("usuario.nombre_usuario")
        ->get();
        $cantidadUsuarios = Usuario::count();

        $data = [
            'title' => 'Reporte usuarios',
            'datos' => $datos,
            'cantidadUsuarios' => $cantidadUsuarios
        ];

        $pdf = PDF::loadView('usuarios', $data);

        return $pdf->download('reporteUsuario.pdf');
    }

    public function balanceMensual($anho)
    {
        $movimientos = DB::table('operacion')
            ->join('conceptos_caja', 'operacion.concepto', '=', 'conceptos_caja.id')
            ->select(DB::raw(' EXTRACT(MONTH FROM operacion.fecha_operacion) as mes, conceptos_caja.tipo, SUM(operacion.monto) as total, COUNT(operacion.monto) as cantidad, EXTRACT(YEAR FROM operacion.fecha_operacion) as anho'))
            ->where(DB::raw('EXTRACT(YEAR FROM operacion.fecha_operacion)'),'=',$anho)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM operacion.fecha_operacion), conceptos_caja.tipo, EXTRACT(YEAR FROM operacion.fecha_operacion)'))
            ->get();

            

        $data = [
            'title' => 'Reporte balance mensual',
            'datos' => $movimientos,
            'anho' => $anho
        ];

        $pdf = PDF::loadView('balanceMensual', $data);

        return $pdf->download('reporteBalance.pdf');
    }

    public function estadisticaMovimiento(){
        $movimientos = DB::table('operacion')
            ->join('conceptos_caja', 'operacion.concepto', '=', 'conceptos_caja.id')
            ->select(DB::raw(' EXTRACT(MONTH FROM operacion.fecha_operacion) as mes, conceptos_caja.tipo, SUM(operacion.monto) as total ,COUNT(operacion.monto) as cantidad ',))
            ->groupBy(DB::raw(' EXTRACT(MONTH FROM operacion.fecha_operacion), conceptos_caja.tipo'))
            ->get();
        $data = [
            'title' => 'Reporte balance mensual',
            'datos' => json_encode($movimientos)
        ];
        $pdf = PDF::loadView('estadisticaMovimientos', $data);

        return $pdf->download('estadisticaMovimiento.pdf');
        // return view('estadisticaMovimientos', $data);
    }

}
