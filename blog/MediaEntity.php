<?php 

declare(strict_types=1);

enum MediaType: string {
    case MANGA = 'manga';
    case ANIME = 'anime';
    case BOOK = 'book';
}

class MediaEntity {
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly MediaType $type, 
        public readonly ?string $synopsis = null
    ){}

    public function toArray(): array {
        return [
            'id' => $this->id, 
            'title' => $this->title, 
            'type' => $this->type, 
            'synopsis' => $this->synopsis 
        ];
    }
}