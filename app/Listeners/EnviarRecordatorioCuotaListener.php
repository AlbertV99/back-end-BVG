<?php

namespace App\Listeners;

use App\Events\EventsCuotaVencimientoEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarRecordatorioCuotaListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EventsCuotaVencimientoEvent  $event
     * @return void
     */
    public function handle(EventsCuotaVencimientoEvent $event){
        $cuota = $event->cuota;

        // Mail::to($cuota->email)->send(new RecordatorioCuotaMail($cuota));
    }
}
