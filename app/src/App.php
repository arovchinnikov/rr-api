<?php

declare(strict_types=1);

namespace App;

use Core\Base\BaseApp;

class App extends BaseApp
{
    /**
     * List of bootloaders to load with init
     * @return string[]
     */
    public function bootloaders(): array
    {
        return [

        ];
    }

    /**
     * List of middlewares to handle with any request
     * @return string[]
     */
    public function middlewares(): array
    {
        return [

        ];
    }
}
