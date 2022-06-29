<?php

namespace App\Factories;

use App\Factories\ProductProperties\ProductPropertiesFactory;
use App\Models\Product;

class ProductFactory
{
    private $similarFactory;
    private $productPropertiesFactory;

    public function __construct(SimilarFactory $similarFactory, ProductPropertiesFactory $productPropertiesFactory)
    {
        $this->similarFactory = $similarFactory;
        $this->productPropertiesFactory = $productPropertiesFactory;
    }

    public function createByAlias($alias)
    {
        $product = Product::with(
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

        $similarProducts = $this->similarFactory->getSimilarByProduct($product);

        $properties = $this->productPropertiesFactory->createProductProperties($product->id, $product->category_id);

        return [
            'product' => $product,
            'similars' => $similarProducts,
            'properties' => $properties,
        ];
    }
}