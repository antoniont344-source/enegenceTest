<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\State;
use App\Services\Serv_Copomex;

class LoadStatesCopomex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-states-copomex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load the states from COPOMEX API into the database';

    /**
     * Execute the console command.
     */
    public function handle(Serv_Copomex $service)
    {
        $states = $service->getStates();

        foreach ($states as $state => $code) {
            State::updateOrCreate(
                ['nom_id' => $code],
                ['name_state' => $state]
            );
        }

        $this->info('States loaded successfully from COPOMEX API.');
    }
}
