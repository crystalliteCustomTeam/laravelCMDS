<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkSiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'worksite' => [
                'id' => $this->id,
                'name' => $this->Name,
                // Add other WorkSite fields here as needed
                'areas' => $this->areas->map(function ($area) {
                    return [
                        'id' => $area->id,
                        'name' => $area->Area_Name,
                        // Add other Area fields here as needed
                    ];
                }),
            ],
        ];
    }
}
