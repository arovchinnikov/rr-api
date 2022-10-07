<?php

declare(strict_types=1);

namespace PHPSTORM_META
{
    use Core\Components\Data\Container;

    override(Container::get(0), map([
        '' => '@',
    ]));
}
