<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    // Adicione isso para o Laravel permitir salvar os dados
    protected $fillable = ['user_id', 'hora_inicio', 'hora_saida', 'data'];
}
