<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // utilisateur
            $table->string('action');        // Création / Modification / Suppression / Accès
            $table->text('details')->nullable(); // infos supplémentaires
            $table->timestamps();            // created_at = date de l'action
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
