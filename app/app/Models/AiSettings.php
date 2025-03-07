<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiSettings extends Model
{
    protected $table = 'ai_settings';

    const AVAILABLE_MODELS = [
        'gpt-4o',
        'gpt-4',
        'gpt-3.5-turbo-1106'
    ];

    const AVAILABLE_IMAGES_MODELS = [
        'dall-e-3',
    ];

    protected $fillable = [
        'function',
        'instructions',
        'prompt',
        'model',
        'temperature',
    ];

    protected $casts = [
        'temperature' => 'float',
    ];

    const DEFAULTS = [
        [
            'function' => 'articles',
            'instructions' => 'Assume the role of a highly sought after parenting expert. You get paid $1,000,000 to give parents advice.',
            'prompt' => 'Please generate {qty} different articles. EVERY Article MUST contain minimum 700 and maximum 900 words. Articles theme is determined in Search prompt.
                            Search Prompt:  {search}
                            IMPORTANT! DO NOT generate articles similar to Article Titles List!
                            Article Titles List: {titles}

                            Response MUST BE a JSON ARRAY OF OBJECTS. Every generated article must be a JSON object filled according to the schema
                            Schema: {schema}

                            JSON MUST NOT be prettied AND the response MUST NOT contain anything but JSON. Please do not include any input data into response, return only JSONed array of objects

                            Article content MUST NOT contain phrases like "This article" or any other reference. You must write as a human writer.
                            Articles must differ each other as much a possible.
                            Articles content must be formatted as HTML!
                            DO NOT INCLUDE ANY CSS STYLE SHEETS!
                            DO NOT include article title in content section of JSON!
                            Prompt Unique Identifier: {uuid}',
            'temperature' => 0.7,
            'model' => 'gpt-4o'
        ],
        [
            'function' => 'live_article',
            'instructions' => 'Assume the role of a highly sought after parenting expert. You get paid $1,000,000 to give parents advice.',
            'prompt' => 'Please generate an article. Article MUST contain minimum 700 and maximum 900 words. Article theme is determined in Search prompt.
                            Search Prompt:  {search}
                            IMPORTANT! DO NOT generate articles similar to Article Titles List!
                            Article Titles List: {titles}

                            Article content MUST NOT contain phrases like "This article" or any other references. You must write as a human writer.
                            Article content must be formatted as HTML!
                            Please generate Tags associated with the article. Place tags as comma-separated list into HTML <meta name=\"tags\"> tag
                            Please generate appropriate title for the article. Place the title  into HTML <h1> tag
                            DO NOT INCLUDE ANY CSS STYLE SHEETS!
                            Prompt Unique Identifier: {uuid}',
            'temperature' => 0.7,
            'model' => 'gpt-4o'
        ],
        [
            'function' => 'image_for_article',
            'instructions' => 'Assume the role of a highly sought after parenting expert. You get paid $1,000,000 to give parents advice.',
            'prompt' => 'Generate an image that visually represents the main topic of the ARTICLE. The image should be a clear and engaging illustration without any text, words, letters, numbers, or symbols of any kind. The focus should be entirely on creating a visual representation of the topic, using elements like colors, shapes, objects, and scenes that are relevant to the article\'s content. Avoid any overlays, captions, or embedded text in the image.
                            ARTICLE:
                            {article}
                            Prompt Unique Identifier: {uuid}',
            'temperature' => 0,
            'model' => 'dall-e-3'
        ]
    ];

    /**
     * Get softDeleted items.
     *
     * @param mixed $value
     * @param null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'function';
    }

    public function setDefaults(): void
    {
        $defaults = collect(self::DEFAULTS)->where('function', $this->function)->first();
        $this->fill($defaults);
        $this->save();
    }
}
