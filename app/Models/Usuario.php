<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "perfil_usuario_id",
        "nome",
        "senha",
        "email",
        "confirmar_senha",
        "ativo"
    ];

    protected $dates  = ["created_at", "updated_at"];

    public function getCreatedAtAttribute($date)
    {
        return date("d/m/Y", strtotime($date));
    }

    public function perfilUsuario()
    {
        return $this->belongsTo(PerfilUsuario::class, "perfil_usuario_id");
    }
}
