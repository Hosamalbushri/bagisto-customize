<?php

namespace Webkul\AdminTheme\Repositories\Country;

use Webkul\Core\Eloquent\Repository;
use Exception;

class CountryRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return '\Webkul\AdminTheme\Models\Country';
    }

    /**
     * Delete a country after checking if it has states.
     *
     * @param  int  $id
     * @return bool
     *
     * @throws \Exception
     */
    public function delete($id): bool
    {
        $country = $this->find($id);

        if (! $country) {
            throw new Exception(trans('adminTheme::app.country.dataGrid.no-found'));
        }

        // Check if country has states
        if ($country->states()->exists()) {
            throw new Exception(trans('adminTheme::app.country.dataGrid.delete_warning_has_children'));
        }

        return parent::delete($id);
    }
}
