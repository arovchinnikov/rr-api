<?php

declare(strict_types=1);

namespace App\Common\Api\Filters;

use Core\Components\Http\Request;

abstract class BaseFilter
{
    protected array $conditions = [];
    protected array $params = [];

    protected array $post = [];
    protected array $get = [];

    protected string $pagination;

    public function __construct(Request $request = null)
    {
        if (empty($request)) {
            return;
        }

        $this->post = $request->post;
        $this->get = $request->get;

        $this->buildFilter();
    }

    abstract public function buildFilter(): void;

    public function addCondition(string $condition): void
    {
        $this->conditions[] = $condition;
    }

    public function addParam(string $key, int|string|bool $param): void
    {
        $this->params[$key] = $param;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function setPagination(int $perPage, int $page): void
    {
        $this->pagination = ' LIMIT ' . $perPage . ' OFFSET ' . $page;
    }

    public function pagination(): string
    {
        return $this->pagination ?? '';
    }

    public function toSql(bool $orCondition = false): string
    {
        if (count($this->conditions) === 0) {
            return '';
        }

        $orCondition ? $operator = ' OR ' : $operator = ' AND ';

        return ' WHERE ' . implode($operator, $this->conditions);
    }
}
