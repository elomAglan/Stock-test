<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Autorise l'assignation de "name" via mass assignment
    protected $fillable = ['name'];

    /**
     * Une catégorie peut avoir plusieurs articles
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
