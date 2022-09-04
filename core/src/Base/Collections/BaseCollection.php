<?php

declare(strict_types=1);

namespace Core\Base\Collections;

use Core\Base\Collections\Interfaces\CollectionInterface;

abstract class BaseCollection implements CollectionInterface
{
    protected array $items = [];

    protected int $current = 0;

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function key(): int
    {
        return $this->current;
    }

    public function current(): mixed
    {
        return $this->items[$this->current];
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function valid(): bool
    {
        return key_exists($this->current, $this->items);
    }

    public function next(): void
    {
        $this->current++;
    }

    public function prev(): void
    {
        $this->current--;
    }

    public function rewind(): void
    {
        $this->current = 0;
    }
}
