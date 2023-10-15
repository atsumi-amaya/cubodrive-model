<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class code extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "codes";
    protected $fillable = [
        'descripcion',
        'code',
        'email',
        'estado',
        'created_at', 
        'updated_at'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id';
}