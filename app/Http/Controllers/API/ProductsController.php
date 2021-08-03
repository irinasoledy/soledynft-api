<?php
namespace App\Http\Controllers\API;

use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;


class ProductsController extends ApiController
{

    public function getCategories(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $categories = ProductCategory::with(
                        [
                            'translation',
                            'children.translation',
                            'products.translation',
                            'products.mainImage',
                        ])
                        ->where('parent_id', 0)
                        ->orderby('position', 'asc')
                        ->get();

        return $this->respond($categories);
    }


    public function getCategory(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $category = ProductCategory::with(
                        [
                            'translation',
                            'children.translation',
                            'products.translation',
                            'products.mainImage',
                            'products.mainPrice',
                            'products.personalPrice',
                        ])
                        ->where('alias', $request->get('alias'))
                        ->first();

        return $this->respond($category);
    }

    public function getProduct(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $product = Product::with(
                        [
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'setImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'warehouse',
                            'translation',
                        ])
                        ->where('alias', $request->get('alias'))
                        ->first();

        return $this->respond($product);
    }

    public function getNewProducts(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $products = Product::with(
                        [
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'setImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'warehouse',
                            'translation',
                        ])
                        ->where('discount', 0)
                        ->where('active', 1)
                        ->orderBy('position', 'asc')
                        ->limit(40)
                        ->get();

        return $this->respond($products);
    }

    public function getOutletProducts(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $productsPrices = ProductPrice::where('currency_id', @$_COOKIE['currency_id'])->get();
        $prodIds = [];

        foreach ($productsPrices as $key => $productPrice) {
            if ($productPrice->old_price !== $productPrice->price) {
                $prodIds[] = $productPrice->product_id;
            }
        }

        $products = Product::with(
                        [
                            'category.properties.property.parameterValues.translation',
                            'category.translation',
                            'images',
                            'mainImage',
                            'setImage',
                            'mainPrice',
                            'personalPrice',
                            'subproducts.parameterValue.translation',
                            'subproducts.parameter.translation',
                            'subproducts.warehouse',
                            'warehouse',
                            'translation',
                        ])
                        ->where('active', 1)
                        ->whereIn('id', $prodIds)
                        ->orderBy('position', 'asc')
                        ->limit(40)
                        ->get();

        return $this->respond($products);
    }

    public function getCollection(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $collection = Collection::with(
                        [
                            'translation',
                            'sets.translation',
                            'sets.personalPrice',
                            'sets.photos',
                            'sets.mainPhoto',
                            'sets.collection.translation',
                            'sets.setProducts.product.category',
                            'sets.setProducts.product.translation',
                            'sets.setProducts.product.warehouse',
                            'sets.setProducts.product.mainPrice',
                            'sets.setProducts.product.personalPrice',
                            'sets.setProducts.product.mainImage',
                            'sets.setProducts.product.setImages',
                            'sets.setProducts.product.setImage',
                            'sets.setProducts.product.subproducts.parameterValue.translation',
                            'sets.setProducts.product.subproducts.parameter',
                            'sets.setProducts.product.subproducts.warehouse',
                        ])
                        ->where('alias', $request->get('alias'))
                        ->first();

        return $this->respond($collection);
    }

    public function getCollections(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $collection = Collection::with(
                        [
                            'translation',
                            'sets.translation',
                            'sets.mainPhoto',
                        ])
                        ->orderby('position', 'asc')
                        ->get();

        return $this->respond($collection);
    }

    public function getPromotions(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $promotions = Promotion::with(['translation'])
                                ->where('active', 1)
                                ->orderBy('id', 'desc')
                                ->get();

        return $this->respond($promotions);
    }

    public function initData(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $data['services'] = BlogCategory::with(
                        [
                            'children.translation',
                            'subcategories.translation',
                            'services.translation',
                            'services.children.translation',
                            'translation'
                        ])
                            ->where('parent_id', 0)
                            ->orderby('position', 'asc')
                            ->get();

        $data['servicesAll'] = BlogCategory::with(
                            [
                                'children.translation',
                                'children.blogs.translation:blog_id,id,body,name',
                                'translation',
                                'blogs.translation:blog_id,id,body,name',
                                'services.translation',
                                'services.children.translation',
                            ])
                                ->orderby('position', 'asc')
                                ->get();

        $data['banners'] = Banner::get();


        $data['promotions'] = Promotion::with(['translation', 'promoSections.translation'])
                                ->where('active', 1)
                                ->orderBy('id', 'desc')
                                ->get();

        $data['pages'] = StaticPage::with(['translation'])->get();

        return $this->respond($data);
    }
}
