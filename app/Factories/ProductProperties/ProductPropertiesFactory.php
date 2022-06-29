<?php

namespace App\Factories\ProductProperties;

use App\Models\ProductCategory;

class ProductPropertiesFactory
{
    private $productValuePropertyFactory;

    public function __construct(ProductValuePropertyFactory $productValuePropertyFactory)
    {
        $this->productValuePropertyFactory = $productValuePropertyFactory;
    }

    public function createProductProperties($productId, $categoryId)
    {
        $category = ProductCategory::select('id')->find($categoryId);

        $properties = [];
        $propertyValues = [];

        foreach ($category->params as $params) {
            $properties[] = $params->property->translation;
        }

        foreach ($properties as $property){
            $propertyValues[$property->name] = $this->productValuePropertyFactory->getPropertyValue($productId, $property->parameter_id);
        }
        return $propertyValues;
    }
}