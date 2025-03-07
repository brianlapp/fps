<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Activity extends JsonResource
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
            'uuid' => $this->uuid,
            'user' => new Profile($this->user),
            'status' => $this->status_string,
            'result' => $this->result,
            'child_age' => $this->child_age,
            'available_time' => $this->available_time,
            'location' => $this->location,
            'activity_type_preferences' => $this->activity_type_preferences,
            'available_resources' => $this->available_resources,
            'special_requests' => $this->special_requests,
            'title' => $this->title,
            'brief_description' => $this->brief_description,
            'age_range' => $this->age_range,
            'time_required' => $this->time_required,
            'materials_needed' => $this->materials_needed,
            'generated_at' => $this->generated_at,
        ];
    }
}
