<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::orderByDesc('date_publication')->orderByDesc('id')->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        try {
            $data = $request->safe()->except(['image', 'photos']);

            $article = Article::create($data);

            if ($request->hasFile('image')) {
                $article->addMediaFromRequest('image')->toMediaCollection('image');
            }

            foreach ($request->file('photos', []) as $photo) {
                $article->addMedia($photo)->toMediaCollection('photos');
            }

            Article::clearCache();

            return redirect()->route('admin.articles.edit', $article)
                ->with('status', 'Article créé avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        try {
            $data = $request->safe()->except(['image', 'photos']);

            $article->update($data);

            if ($request->hasFile('image')) {
                $article->addMediaFromRequest('image')->toMediaCollection('image');
            }

            foreach ($request->file('photos', []) as $photo) {
                $article->addMedia($photo)->toMediaCollection('photos');
            }

            Article::clearCache();

            return redirect()->route('admin.articles.edit', $article)
                ->with('status', 'Article mis à jour avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy(Article $article): RedirectResponse
    {
        try {
            $article->delete();
            Article::clearCache();

            return redirect()->route('admin.articles.index')->with('status', 'Article supprimé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function supprimerPhoto(Article $article, Media $media)
    {
        abort_unless($media->model_id === $article->id && $media->collection_name === 'photos', 403);

        $media->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', 'Photo supprimée.');
    }
}
