<?php

namespace App\Factories\ProductProperties;

use App\Models\ParameterValueProduct;

class ProductValuePropertyFactory
{
    public function getPropertyValue($productId, $propertyId)
    {
        $value = ParameterValueProduct::select('id', 'parameter_id', 'product_id', 'parameter_value_id')
            ->where('product_id', $productId)
            ->where('parameter_id', $propertyId)->first();

        if ($value) {
            if ($value->value) {
                return $value->value->translation->name;
            } else {
                return $value->translation->value;
            }
        }

        return null;
    }
}