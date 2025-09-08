<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Liste tous les articles avec leur catégorie associée
     */
    public function index()
    {
        return Article::with('category')->get();
    }

    /**
     * Crée un nouvel article avec sa catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = Article::create($validated);

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Création d\'article',
            'details' => json_encode(['article_id' => $article->id, 'name' => $article->name]),
        ]);

        return response()->json($article->load('category'), 201);
    }

    /**
     * Affiche un article spécifique avec sa catégorie
     */
    public function show($id)
    {
        return Article::with('category')->findOrFail($id);
    }

    /**
     * Met à jour un article (y compris sa catégorie si modifiée)
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'stock'       => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $article->update($validated);

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Modification d\'article',
            'details' => json_encode(['article_id' => $article->id, 'updated_fields' => array_keys($validated)]),
        ]);

        return response()->json($article->load('category'));
    }

    /**
     * Supprime un article
     */
    public function destroy(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Suppression d\'article',
            'details' => json_encode(['article_id' => $id]),
        ]);

        return response()->json(['message' => 'Article supprimé avec succès']);
    }
}
