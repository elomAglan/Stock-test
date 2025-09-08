<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        // Récupère les logs avec info utilisateur
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}
