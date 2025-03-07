<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;

class SimpleArticle extends Model
{
    protected $table = 'articles';
    
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt',
        'author_id', 'type', 'is_page', 'source',
        'image_caption', 'is_image_being_generated',
        'was_improved', 'seo_title', 'seo_headline',
        'entity_id', 'entity_type', 'is_featured'
    ];
}
