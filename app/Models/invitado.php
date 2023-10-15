<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invitado extends Model
{
    use HasFactory;
    protected $table = "invitados";
    protected $fillable = [
        'editar',
        'descargar',
        'ver',
        'eliminar',
        'mover',
        'invitado_id',
        'document_id',
    ];
    protected $hidden = ['id'];
    protected $primaryKey = 'id';
}
