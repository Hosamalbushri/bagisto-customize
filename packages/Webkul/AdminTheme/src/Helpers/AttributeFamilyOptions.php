<?php

namespace Webkul\AdminTheme\Helpers;

use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class AttributeFamilyOptions
{
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
