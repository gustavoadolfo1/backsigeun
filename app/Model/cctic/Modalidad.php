<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Publicacion;
class Modalidad extends Model
{

    protected $table = 'acad.modalidades_estudios';
    protected $primaryKey = 'iModalEstudId';

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'iModalEstudId');
    }
}
