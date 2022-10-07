<?php

declare(strict_types=1);

namespace Core\Base\Collections\Interfaces;

use Core\Base\Interfaces\Types\ToArray;
use Iterator;

interface CollectionInterface extends Iterator, ToArray
{
}
