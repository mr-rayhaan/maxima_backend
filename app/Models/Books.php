<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;

    protected $fillable = ["title", "description", "img_path", "pdf_path"];

    protected $hidden = ["img_path", "pdf_path"];

    protected $appends = ["image_url", "pdf_url"];

    public function getImageUrlAttribute()
    {
        $isStaging = env('IS_STAGING') ?? true;
        $baseUrl = $isStaging ? env('LOCALHOST_URL') : env('PRODUCTION_URL');

        return $baseUrl . "/storage" . "/" . $this->img_path;
    }
    public function getPdfUrlAttribute()
    {
        $isStaging = env('IS_STAGING') ?? true;
        $baseUrl = $isStaging ? env('LOCALHOST_URL') : env('PRODUCTION_URL');

        return $baseUrl . "/storage" . "/" . $this->pdf_path;
    }
}
