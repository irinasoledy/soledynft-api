<?php

namespace App\Factories;

use App\Factories\ProductProperties\ProductPropertiesFactory;
use App\Models\Product;

class ProductFactory
{
    private $similarFactory;
    private $productPropertiesFactory;
    private $productOffersFactory;

    public function __construct(SimilarFactory           $similarFactory,
                                ProductPropertiesFactory $productPropertiesFactory,
                                ProductOffersFactory     $productOffersFactory)
    {
        $this->similarFactory = $similarFactory;
        $this->productPropertiesFactory = $productPropertiesFactory;
        $this->productOffersFactory = $productOffersFactory;
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

        $offers = $this->productOffersFactory->getProductOffers($product->id);

        return [
            'product' => $product,
            'similars' => $similarProducts,
            'properties' => $properties,
            'offers' => $offers,
        ];
    }

    public function getByCategoryId($categoryId)
    {
        $data = [];

        $products = Product::with(
            [
                'translation',
                'brand.translation',
                'images',
                'mainImage',
                'setImage',
                'mainPrice',
                'personalPrice'
            ])
            ->where('category_id', $categoryId)
            ->where('active', 1)
            ->get();

        foreach ($products as $product) {
            $data[] = [
                'product' => $product,
                'properties' => $this->productPropertiesFactory->createProductProperties($product->id, $product->category_id)
            ];
        }

        return $data;
    }
}