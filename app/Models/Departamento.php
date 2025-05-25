<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'departamento_id');
    }
}