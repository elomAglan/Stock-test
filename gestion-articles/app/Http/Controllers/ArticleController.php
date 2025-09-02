<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index() {
        return Article::all();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        $article = Article::create($validated);
        return response()->json($article, 201);
    }

    public function show($id) {
        return Article::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0'
        ]);

        $article->update($validated);
        return $article;
    }

    public function destroy($id) {
        Article::destroy($id);
        return response()->json(null, 204);
    }
}
