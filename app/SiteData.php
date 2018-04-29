<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteData extends Model
{
    protected $table = 'sites_data';

    protected $fillable = [
        'link',
        'title',
        'code',
        'status',
        'sizes',
        'price',
        'price2',
        'img',
        'colors',
        'description',
    ];
}
