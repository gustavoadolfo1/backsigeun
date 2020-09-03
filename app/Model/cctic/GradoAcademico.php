<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class GradoAcademico extends Model
{
    protected $table = 'acad.grados_academicos';
    protected $primaryKey = 'iGradoAcadId';

    protected $fillable = [
        'cgradoAcadDesc',
    ];
}
