<?php

namespace App\Http\Controllers\API;

use App\Factories\ProductFactory;
use App\Factories\ProductProperties\ProductPropertiesFactory;
use App\Factories\SimilarFactory;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ParameterValueProduct;
use App\Models\ParameterValue;
use App\Models\Brand;
use Illuminate\Http\Request;


class ProductsController extends ApiController
{
    private $productFactory;

    public function __construct(ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
    }

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
            ->where('active', 1)
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
                'params.property.translation',
                'params.property.transData',
                'params.property.parameterValues.translation',
                'params.property.parameterValues.transData',
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
            return $this->respondError("Language or currency is not found", 500);
        }

        $product = $this->productFactory->createByAlias($request->get('alias'));

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

        $productsPrices = ProductPrice::where('currency_id', $request->get('currency'))->get();
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
            // ->where('active', 1)
            ->whereIn('id', $prodIds)
            ->orderBy('position', 'asc')
            // ->limit(40)
            ->get();

        return $this->respond($products);
    }


    public function getAllProducts(Request $request)
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
            ->where('active', 1)
            ->orderBy('position', 'asc')
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
            ->where('active', 1)
            ->orderby('position', 'asc')
            ->get();

        return $this->respond($collection);
    }

    public function getPromotions(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $promotions = Promotion::with([
            'translation',
            'products.translation',
            'products.category',
            'products.mainImage',
            'products.mainPrice',
            'products.personalPrice'
        ])
            ->where('active', 1)
            ->orderBy('position', 'asc')
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

    public function getSortedProducts(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $sortDirection = 'desc';
        if ($request->get('order') == 'priceAsc') {
            $sortDirection = 'asc';
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
            'subproducts.warehouse',
            'warehouse',
            'translation',
        ])
            ->where('active', 1)
            ->where('category_id', $request->get('categoryId'))
            ->orderBy('actual_price', $sortDirection)
            ->get();
    }

    public function getFiltredProducts(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $props = json_decode($request->get('properties'));

        $parameters = [];
        $propsProducts = [];
        $dependable = 0;

        foreach ($props as $key => $param) {
            if ($param->name != $dependable) {
                $parameters[$param->name][] = $param->value;
            }
        }

        foreach ($parameters as $param => $values) {
            $propIds = [];
            foreach ($values as $key => $value) {
                $row = ParameterValueProduct::select('product_id')
                    ->where('parameter_value_id', $value)
                    ->where('parameter_id', $param)
                    ->when(count($propsProducts) > 0, function ($query) use ($propsProducts) {
                        return $query->whereIn('product_id', $propsProducts);
                    })
                    ->pluck('product_id')->toArray();

                $propIds = array_merge($propIds, $row);
            }
            $propsProducts = $propIds;
        }

        if ((count($request->get('properties')) > 0) && (count($propsProducts) == 0)) $propsProducts = [0];

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
            'subproducts.warehouse',
            'warehouse',
            'translation',
        ])
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->where('category_id', $request->get('categoryId'))
            ->whereBetween('actual_price', [$request->get('minPrice'), $request->get('maxPrice')])
            ->when(count($propsProducts) > 0, function ($query) use ($propsProducts) {
                return $query->whereIn('id', $propsProducts);
            })
            ->get();

    }

    public function getDefaultFilter(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $categoryChilds = ProductCategory::where('parent_id', $request->get('categoryId'))->pluck('id')->toArray();
        $categoryChilds = array_merge($categoryChilds, [$request->get('categoryId')]);
        $allProducts = Product::where('category_id', $request->get('categoryId'))->where('active', 1)->get(); // without pagination

        $data['parameters'] = $this->_getParametersList($allProducts, $request->get('categoryId'));

        $maxPrice = ProductPrice::where('currency_id', $this->currency->id)
            ->whereIn('product_id', $allProducts->pluck('id')->toArray())
            ->max('price');

        $data['prices']['min'] = 0;
        $data['prices']['max'] = $maxPrice;

        return $data;
    }

    private function _getParametersList($allProducts, $categoryId)
    {
        $dependable = 0;
        $parametersId = ParameterValueProduct::whereIn('product_id', array_filter($allProducts->pluck('id')->toArray()))->pluck('parameter_value_id')->toArray();
        $dependableCategory = ProductCategory::where('id', $categoryId)->first();

        if (!is_null($dependableCategory)) {
            if (!is_null($dependableCategory->subproductParameter)) {
                $dependable = $dependableCategory->subproductParameter->parameter_id;
            }
        }

        $dependebleValues = ParameterValue::where('parameter_id', $dependable)->pluck('id')->toArray();

        $parametersId = array_merge($parametersId, $dependebleValues);
        return json_encode(array_filter($parametersId));
    }

    public function getDesigners(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $brands = Brand::with(['translation'])->get();

        return $this->respond($brands);
    }

    public function getDesigner(Request $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return $this->respondError("Language is not found", 500);
        }

        $brand = Brand::with([
            'translation',
            'products.category.properties.property.parameterValues.translation',
            'products.category.translation',
            'products.images',
            'products.mainImage',
            'products.setImage',
            'products.mainPrice',
            'products.personalPrice',
            'products.subproducts.parameterValue.translation',
            'products.subproducts.parameter.translation',
            'products.subproducts.warehouse',
            'products.warehouse',
            'products.translation',
        ])
            ->where('alias', $request->get('alias'))->first();

        return $this->respond($brand);
    }
}
