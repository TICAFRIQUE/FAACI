<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Annonce::with('auteur')->select('annonces.*');

            return DataTables::of($query)
                ->addColumn('type_badge', function ($a) {
                    $cfg = $a->type_config;
                    return "<span class='badge bg-{$cfg['couleur']} bg-opacity-10 text-{$cfg['couleur']}'>"
                        ."<i class='bi {$cfg['icone']} me-1'></i>{$cfg['libelle']}</span>";
                })
                ->addColumn('statut_badge', function ($a) {
                    $map = [
                        'publiee'  => ['success',  'Publiée'],
                        'archivee' => ['secondary', 'Archivée'],
                        'brouillon'=> ['warning',   'Brouillon'],
                    ];
                    [$col, $lib] = $map[$a->statut] ?? ['secondary', $a->statut];
                    return "<span class='badge bg-{$col}'>{$lib}</span>";
                })
                ->addColumn('publiee_fmt',  fn ($a) => $a->publiee_at?->format('d/m/Y H:i') ?? '—')
                ->addColumn('expire_fmt',   fn ($a) => $a->expire_at?->format('d/m/Y') ?? '—')
                ->addColumn('auteur_nom',   fn ($a) => $a->auteur?->nom_complet ?? '—')
                ->addColumn('actions', fn ($a) => view('admin.annonces._actions', compact('a'))->render())
                ->rawColumns(['type_badge', 'statut_badge', 'actions'])
                ->make(true);
        }

        return view('admin.annonces.index');
    }

    public function create(): View
    {
        $types = Annonce::TYPES;
        return view('admin.annonces.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->validate([
                'titre'     => ['required', 'string', 'max:255'],
                'contenu'   => ['required', 'string'],
                'type'      => ['required', 'in:' . implode(',', array_keys(Annonce::TYPES))],
                'statut'    => ['required', 'in:brouillon,publiee,archivee'],
                'expire_at' => ['nullable', 'date', 'after:now'],
            ]);

            if ($data['statut'] === Annonce::STATUT_PUBLIEE) {
                $data['publiee_at'] = now();
            }

            $data['auteur_id'] = Auth::id();

            Annonce::create($data);

            return redirect()->route('admin.annonces.index')->with('status', 'Annonce créée avec succès.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function edit(Annonce $annonce): View
    {
        $types = Annonce::TYPES;
        return view('admin.annonces.edit', compact('annonce', 'types'));
    }

    public function update(Request $request, Annonce $annonce): RedirectResponse
    {
        try {
            $data = $request->validate([
                'titre'     => ['required', 'string', 'max:255'],
                'contenu'   => ['required', 'string'],
                'type'      => ['required', 'in:' . implode(',', array_keys(Annonce::TYPES))],
                'statut'    => ['required', 'in:brouillon,publiee,archivee'],
                'expire_at' => ['nullable', 'date'],
            ]);

            if ($data['statut'] === Annonce::STATUT_PUBLIEE && ! $annonce->publiee_at) {
                $data['publiee_at'] = now();
            }

            $annonce->update($data);

            return redirect()->route('admin.annonces.index')->with('status', 'Annonce mise à jour.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function destroy(Annonce $annonce): RedirectResponse
    {
        try {
            $annonce->delete();
            return redirect()->route('admin.annonces.index')->with('status', 'Annonce supprimée.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}
