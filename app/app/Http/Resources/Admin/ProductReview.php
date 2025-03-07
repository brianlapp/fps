<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\PromptAuthor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductReview extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $admin = Auth::guard('admin')->user();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'rate' => $this->rate,
            'status' => $this->status_string,
            'is_verified_by_ai' => $this->is_verified_by_ai,
            'ai_error' => $this->ai_error,
            'created_at' => $this->created_at,
            'user' => $this->user->append(['name']),
            'product' => new Product($this->product),
            'can' => [
                'edit' => $admin->can('update', $this),
                'delete' => $admin->can('delete', $this),
            ],
            'deleted_at' => $this->deleted_at
        ];
    }
}

