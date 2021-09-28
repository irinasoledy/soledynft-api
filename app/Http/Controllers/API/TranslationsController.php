<?php
namespace App\Http\Controllers\API;

use App\Tag;
use App\Article;
use App\RealWorld\Paginate\Paginate;
use App\RealWorld\Filters\ArticleFilter;
use App\Http\Requests\Api\CreateArticle;
use App\Http\Requests\Api\UpdateArticle;
use App\Http\Requests\Api\DeleteArticle;
use App\RealWorld\Transformers\ArticleTransformer;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Models\TranslationGroup;

class TranslationsController extends ApiController
{
    public function all(Request $request)
    {
        $this->swithLang($request->get('lang'));

        $strings = \Cache::rememberForever('lang.js', function () use ($request){
            $lang = $request->get('lang');

            $files   = glob(resource_path('lang/' . $lang . '/vars.php'));
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            return $strings;
        });

        return $this->respond($strings);
    }

    /**
     * Get all the articles.
     *
     * @param ArticleFilter $filter
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ArticleFilter $filter)
    {
        $articles = new Paginate(Article::loadRelations()->filter($filter));

        return $this->respondWithPagination($articles);
    }

    /**
     * Create a new article and return the article if successful.
     *
     * @param CreateArticle $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateArticle $request)
    {
        $user = auth()->user();

        $article = $user->articles()->create([
            'title' => $request->input('article.title'),
            'description' => $request->input('article.description'),
            'body' => $request->input('article.body'),
        ]);

        $inputTags = $request->input('article.tagList');

        if ($inputTags && ! empty($inputTags)) {

            $tags = array_map(function($name) {
                return Tag::firstOrCreate(['name' => $name])->id;
            }, $inputTags);

            $article->tags()->attach($tags);
        }

        return $this->respondWithTransformer($article);
    }

    /**
     * Get the article given by its slug.
     *
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Article $article)
    {
        return $this->respondWithTransformer($article);
    }

    /**
     * Update the article given by its slug and return the article if successful.
     *
     * @param UpdateArticle $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateArticle $request, Article $article)
    {
        if ($request->has('article')) {
            $article->update($request->get('article'));
        }

        return $this->respondWithTransformer($article);
    }

    /**
     * Delete the article given by its slug.
     *
     * @param DeleteArticle $request
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteArticle $request, Article $article)
    {
        $article->delete();

        return $this->respondSuccess();
    }
}
