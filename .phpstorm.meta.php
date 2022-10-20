<?php

declare(strict_types=1);

namespace PHPSTORM_META
{

    use Core\Components\Dependencies\Container;
    use Core\Components\Dependencies\StaticContainer;

    override(Container::get(0), map([
        '' => '@',
    ]));
}
