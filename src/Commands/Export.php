<?php

namespace mineschan\LocoLaravelExport\Commands;

use Illuminate\Console\Command;
use mineschan\LocoLaravelExport\Facades\LocoLaravelExport;

/**
 * List all locally installed packages.
 *
 * @author JeroenG
 **/
class Export extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'localise:export {lang?*} {--force}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Export the lang file with Loco Api';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $exportLanguages = count($this->argument('lang')) ? $this->argument('lang') : config('loco-laravel-export.locales');
        $force = $this->option('force');

        if (app()->environment() != 'local' && !$force) {

            $this->alert('Environment is not local!');
            $confirmDownloadAnswer = $this->ask('Proceed downloading lang translations from Localise.biz? [Y/N]');

            if (strtoupper(trim($confirmDownloadAnswer)) != 'Y') {
                $this->comment('Okay, no hard feelings. ;)');
                return;
            }
        }

        LocoLaravelExport::exportArchive($exportLanguages, $this);
    }
}

