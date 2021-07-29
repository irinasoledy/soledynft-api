<?php
namespace App\Http\Controllers\API;

use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Promotion;
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
