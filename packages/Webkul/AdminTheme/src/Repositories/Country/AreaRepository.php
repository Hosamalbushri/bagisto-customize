<?php

namespace Webkul\AdminTheme\Repositories\Country;

use Exception;
use Webkul\Core\Eloquent\Repository;

class AreaRepository extends Repository
{
    public function model()
    {
        return '\Webkul\AdminTheme\Models\Areas';
    }

    /**
     * Delete an area after checking if it has related data.
     *
     * @param  int  $id
     *
     * @throws \Exception
     */
    public function delete($id): bool
    {
        $area = $this->find($id);

        if (! $area) {
            throw new Exception(trans('adminTheme::app.country.state.area.dataGrid.no-found'));
        }

        if ($area->ranges()->exists() || $area->addresses()->exists()) {
            throw new Exception(trans('adminTheme::app.country.state.area.dataGrid.delete_warning_has_children'));
        }

        return parent::delete($id);
    }
}
