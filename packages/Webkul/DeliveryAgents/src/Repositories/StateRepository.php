<?php

namespace Webkul\DeliveryAgents\Repositories;

use Exception;
use Webkul\Core\Eloquent\Repository;

class StateRepository extends Repository
{
    public function model()
    {
        return 'Webkul\DeliveryAgents\Models\State';
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
            throw new Exception('الولاية غير موجودة');
        }

        if ($state->areas()->exists()) {
            throw new Exception('لا يمكن حذف الولاية لأنها تحتوي على ارتباطات.');
        }

        return parent::delete($id);
    }
}
