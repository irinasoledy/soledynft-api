<?php

namespace App\Factories;

use App\Models\ProductCategory;

class CategoryFactory
{
    private $productFactory;

    public function __construct(ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
    }

    public function createByAlias($alias)
    {
        $category = ProductCategory::with(
            [
                'translation',
                'children.translation',
                'params.property.translation',
                'params.property.transData',
                'params.property.parameterValues.translation',
                'params.property.parameterValues.transData',
            ])
            ->where('alias', $alias)
            ->first();

        $products = $this->productFactory->getByCategoryId($category->id);

        return [
            'category' => $category,
            'products' => $products
        ];
    }
}