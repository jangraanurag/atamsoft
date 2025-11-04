<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class labeltype extends Model
{
    protected $table = 'label_types';
    protected $fillable = ['labeltype_name'];
    public $timestamps = true;
    public $primaryKey = 'id';

    public function getLabelTypeNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function setLabelTypeNameAttribute($value)
    {
        $this->attributes['labeltype_name'] = strtolower($value);
    }
}
