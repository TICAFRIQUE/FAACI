<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ActualiteController extends Controller
{
    public function index(): View
    {
        $articles = Article::where('statut', Article::STATUT_PUBLIE)
            ->orderByDesc('date_publication')
            ->paginate(9);

        return view('public.actualites.index', compact('articles'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->statut === Article::STATUT_PUBLIE, 404);

        $autres = Article::where('statut', Article::STATUT_PUBLIE)
            ->where('id', '!=', $article->id)
            ->orderByDesc('date_publication')
            ->take(3)
            ->get();

        return view('public.actualites.show', compact('article', 'autres'));
    }
}
