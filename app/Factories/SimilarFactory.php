<?php

namespace App\Factories;

use App\Models\Product;
use App\Models\ProductSimilar;

class SimilarFactory
{
    public function getSimilarByProduct($product)
    {
        $category = $product->category;

        $similarCategoriesId = ProductSimilar::select('category_id')
            ->where('product_id', $product->id)
            ->pluck('category_id')->toArray();

        if (!count($similarCategoriesId)) {
            $similarCategoriesId[] = $category->id;
        }

        return Product::with([
            'category.properties.property.parameterValues.translation',
            'category.translation',
            'images',
            'mainImage',
            'setImage',
            'mainPrice',
            'personalPrice',
            'subproducts.parameterValue.translation',
            'subproducts.parameter.translation',
            'translation'
        ])
            ->whereIn('category_id', $similarCategoriesId)
            ->where('id', '!=', $product->id)
            ->where('active', 1)
            ->get();
    }
}