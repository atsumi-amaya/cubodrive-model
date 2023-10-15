<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class format extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "formato";
    protected $fillable = [
        'nombre',
        'formato',
        'formato_op',
        'g_doc'
    ];
    protected $hidden = ['id'];
    protected $primaryKey = 'id';
}
