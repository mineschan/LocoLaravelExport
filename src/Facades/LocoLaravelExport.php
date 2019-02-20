<?php

namespace mineschan\LocoLaravelExport\Facades;

use Illuminate\Support\Facades\Facade;

class LocoLaravelExport extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \mineschan\LocoLaravelExport\LocoLaravelExport::class;
    }
}
