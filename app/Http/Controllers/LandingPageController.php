<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexArticlesRequest;
use App\Http\Requests\IndexGalleryRequest;
use App\Http\Requests\ShowArticleRequest;
use App\Services\ArticleService;
use App\Services\GalleryService;
use App\Services\HomePageService;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __construct(
        protected HomePageService $homePageService,
        protected ArticleService $articleService,
        protected GalleryService $galleryService,
    ) {}

    public function index(): View
    {
        return view('welcome', [
            'latestSchemes' => $this->homePageService->latestSchemes(),
            'articles' => $this->articleService->latest(),
            'galleries' => $this->homePageService->latestPublishedGalleries(),
            'homeContent' => $this->homePageService->content(),
        ]);
    }

    public function articlesIndex(IndexArticlesRequest $request): View
    {
        return view('article-index', [
            'articles' => $this->articleService->paginate(),
        ]);
    }

    public function showArticle(ShowArticleRequest $request): View
    {
        $article = $this->articleService->findByPublicSlug($request->validated('slug'));

        $this->articleService->incrementViews($article);

        return view('article-detail', [
            'article' => $this->articleService->present($article->fresh(['creator', 'tags'])),
        ]);
    }

    public function galleryIndex(IndexGalleryRequest $request): View
    {
        return view('gallery-index', [
            'galleries' => $this->galleryService->paginatePublished(),
        ]);
    }
}
