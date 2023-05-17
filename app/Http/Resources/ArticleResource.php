<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body'  => $this->body,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'category'=> $this->category->name ?? null,
            'user' => UserResource::make($this->user),
            'comments' => CommentCollection::make($this->comments),
        ];
    }
}
