<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension,
            'directory' => $this->directory,
            'size' => $this->size,
            'type' => 'image/' . $this->extension,
            'url' => $this->url(),
            'created_at' => $this->created_at,
        ];
    }
}
