<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "documents";
    protected $fillable = [
        'nombre',
        'filecode',
        'formato',
        'size',
        'direccion',
        'local_dir',
        'propietario',
        'estado',
        'last_binned',
        'last_deleted',
        'created_at', 
        'updated_at'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id';
}
