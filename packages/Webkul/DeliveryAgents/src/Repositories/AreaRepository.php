<?php

namespace Webkul\DeliveryAgents\Repositories;

use Exception;
use Webkul\Core\Eloquent\Repository;

class AreaRepository extends Repository
{
    public function model()
    {
        return '\Webkul\DeliveryAgents\Models\Areas';
    }

    /**
     * Delete a country after checking if it has states.
     *
     * @param  int  $id
     *
     * @throws \Exception
     */
    public function delete($id): bool
    {
        $state = $this->find($id);

        if (! $state) {
            throw new Exception('الحي غير موجودة');
        }

        if ($state->ranges()->exists() || $state->addresses()->exists()) {
            throw new Exception('لا يمكن حذف الحي  لأنه يحتوي على ارتباطات.');
        }

        return parent::delete($id);
    }
}
