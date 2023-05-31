<?php

namespace App\Console\Commands;

use App\Events\CuotaVencimientoEvent;
use App\Models\Cuota;
use Illuminate\Console\Command;

class EnviarRecordatorioCuotaCommand extends Command
{
    protected $signature = 'cuotas:enviar-recordatorio';

    protected $description = 'Envíar  correo electrónico para las cuotas a vencer';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $fechaVencimiento = Carbon::now()->addDays(3)->toDateString();

        $cuotasVencidas = Cuota::where('fec_vencimiento', $fechaVencimiento)->get();

        foreach ($cuotasVencidas as $cuota) {
            event(new CuotaVencimientoEvent($cuota));
        }

        $this->info('Se han enviado los correos de recordatorio para las cuotas vencidas.');
    }
}
