<?php

declare(strict_types=1);

namespace App\Modules\User\Api\Filters;

use App\Common\Base\Http\BaseFilter;

class UsersFilter extends BaseFilter
{
    public function buildFilter(): void
    {
        $this->fields();
        $this->paginated();
    }

    private function fields(): void
    {
        if (!empty($this->get['login'])) {
            $this->addCondition("login LIKE :login");
            $this->addParam('login', $this->get['login']);
        }

        if ($this->get['deleted'] === 'true') {
            $this->addCondition('deleted_at IS NOT NULL');
        }

        if ($this->get['deleted'] === 'false') {
            $this->addCondition('deleted_at IS NULL');
        }
    }

    private function paginated(): void
    {
        if (isset($this->get['per_page']) && isset($this->get['page'])) {
            $this->setPagination(+$this->get['per_page'], +$this->get['page']);
        }
    }
}
