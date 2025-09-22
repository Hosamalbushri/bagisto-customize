<?php

namespace Webkul\AdminTheme\Repositories\Country;

use Exception;
use Webkul\Core\Eloquent\Repository;

class StateRepository extends Repository
{
    public function model()
    {
        return 'Webkul\AdminTheme\Models\State';
    }

    /**
     * Delete a state after checking if it has areas.
     *
     * @param  int  $id
     *
     * @throws \Exception
     */
    public function delete($id): bool
    {
        $state = $this->find($id);

        if (! $state) {
            throw new Exception(trans('adminTheme::app.country.state.dataGrid.no-found'));
        }

        if ($state->areas()->exists()) {
            throw new Exception(trans('adminTheme::app.country.state.dataGrid.delete_warning_has_children'));
        }

        return parent::delete($id);
    }
}
