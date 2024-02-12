<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pesquisa;

class Categoria extends Model
{
    use HasFactory;

    public function pesquisas()
    {
        return $this->hasMany(Pesquisa::class);
    }
}
