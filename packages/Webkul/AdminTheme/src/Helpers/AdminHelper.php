<?php

namespace Webkul\AdminTheme\Helpers;

use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class AdminHelper
{
    public function isPostCodeRequired()
    {
        if($this->show_postal_code()){
            return (bool) core()->getConfigData('customer.address.requirements.postcode');
        }
        return false;
    }
    public function show_postal_code()
    {
        return (bool) core()->getConfigData('customer.address.options.show_postal_code');
    }

    public function show_company_name()
    {
        return (bool) core()->getConfigData('customer.address.options.show_company_name');
    }

    public function show_tax_number()
    {
        return (bool) core()->getConfigData('customer.address.options.show_tax_number');
    }

    public function get_default_country()
    {
        return core()->getConfigData('general.location.store.default_country') ? core()->getConfigData('general.location.store.default_country') : null;
    }
    /**
     * Get attribute families options for configuration
     */
    public function attribute_families(): array
    {
        $attributeFamilyRepository = app(AttributeFamilyRepository::class);
        $families = $attributeFamilyRepository->all();

        $options = [];
        foreach ($families as $family) {
            $options[] = [
                'title' => $family->name,
                'value' => $family->id,
            ];
        }
        return $options;
    }
}
