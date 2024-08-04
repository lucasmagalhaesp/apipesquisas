<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class PerfilUsuario extends Model
{
    use HasFactory;

    protected $table = "perfis_usuarios";
    protected $fillable = ["id", "nome"];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }
}
