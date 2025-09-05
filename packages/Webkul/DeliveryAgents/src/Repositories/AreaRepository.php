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
            throw new Exception(trans('deliveryAgent::app.country.state.area.dataGrid.no-found'));
        }

        if ($state->ranges()->exists() || $state->addresses()->exists()) {
            throw new Exception(trans('deliveryAgent::app.country.state.area.dataGrid.delete_warning_has_children'));
        }

        return parent::delete($id);
    }
}
