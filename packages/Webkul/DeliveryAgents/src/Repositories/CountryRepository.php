<?php

namespace Webkul\DeliveryAgents\Repositories;
use Webkul\Core\Eloquent\Repository;
use Exception;
class CountryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return '\Webkul\DeliveryAgents\Models\Country';
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
            throw new Exception(trans('deliveryAgent::app.country.dataGrid.no-found'));
        }

        // نفترض أن العلاقة معرفة في الموديل Country باسم states
        if ($country->states()->exists()) {
            throw new Exception(trans('deliveryAgent::app.country.dataGrid.delete_warning_has_children'));
        }



        return parent::delete($id);
    }




}
