<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleRequestPublic extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'query' => $this->search_query,
            'qty' => $this->qty,
            'isRunning' => $this->isRunning(),
            'isSuccessful' => $this->isSuccessful(),
            'isFailed' => $this->isFailed(),
            'articles' => ArticlePublic::collection($this->articles()->plain()->get()),
        ];
    }
}
