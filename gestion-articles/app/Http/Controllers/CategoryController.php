<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\AuditLog;

class CategoryController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        return Category::all();
    }

    // Crée une nouvelle catégorie
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Création de catégorie',
            'details' => json_encode(['category_id' => $category->id, 'name' => $category->name]),
        ]);

        return response()->json($category, 201);
    }

    // Affiche une catégorie spécifique
    public function show(Category $category)
    {
        return $category;
    }

    // Met à jour une catégorie
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name
        ]);

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Modification de catégorie',
            'details' => json_encode(['category_id' => $category->id, 'updated_name' => $category->name]),
        ]);

        return response()->json($category);
    }

    // Supprime une catégorie
    public function destroy(Request $request, Category $category)
    {
        $categoryId = $category->id;
        $category->delete();

        // 🔹 Audit log manuel
        AuditLog::create([
            'user_id' => $request->user()->id,
            'action'  => 'Suppression de catégorie',
            'details' => json_encode(['category_id' => $categoryId]),
        ]);

        return response()->json(['message' => 'Catégorie supprimée']);
    }
}
