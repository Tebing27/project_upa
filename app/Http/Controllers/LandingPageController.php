<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\Article;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $latestSchemes = Scheme::latest()->take(3)->get();
        $articles = Article::latest()->take(3)->get();
        $galleries = \App\Models\Gallery::latest()->take(6)->get();

        return view('welcome', compact('latestSchemes', 'articles', 'galleries'));
    }

    public function articlesIndex()
    {
        $articles = Article::latest()->paginate(9);
        return view('article-index', compact('articles'));
    }

    public function showArticle(Article $article)
    {
        $article->increment('views_count');
        return view('article-detail', compact('article'));
    }
}
