<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'testimonial_name', 'testimonial_company',
        'testimonial_job_title', 'testimonial_image',
        'testimonial_description',
    ];
}
