<?php

namespace App\Factories;

use App\Models\Product;

class ProductFactory
{
    public function createByAlias($alias)
    {
        return Product::with(
            [
                'category.properties.property.parameterValues.translation',
                'category.translation',
                'brand.translation',
                'images',
                'mainImage',
                'setImage',
                'mainPrice',
                'personalPrice',
                'subproducts.parameterValue.translation',
                'subproducts.parameter.translation',
                'subproducts.warehouse',
                'translation',
            ])
            ->where('alias', $alias)
            ->first();
    }
}