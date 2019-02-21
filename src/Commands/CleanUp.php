<?php

namespace mineschan\LocoLaravelExport\Commands;

use Illuminate\Console\Command;
use mineschan\LocoLaravelExport\Facades\LocoLaravelExport;

/**
 * List all locally installed packages.
 *
 * @author JeroenG
 **/
class CleanUp extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'localise:clean';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Manually clean up the contents extract from the downloaded achieve.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(LocoLaravelExport::clean()) {
            $this->comment('Downloaded folder cleaned.');
        }
    }
}

