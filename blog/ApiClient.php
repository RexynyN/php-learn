<?php 
declare(strict_types=1);

class ApiClient {
    public function fetchMediaInfo(int $id, MediaType $type): MediaEntity {
        $endpoint = match($type) {
            MediaType::ANIME => "https://api.jikan.moe/v4/anime/{$id}",
            MediaType::MANGA => "https://api.jikan.moe/v4/manga/{$id}",
            default => throw new InvalidArgumentException("Tipo não suportado pela API externa")
        };

        $response = @file_get_contents($endpoint);
        if($response === false) {
            throw new RuntimeException("Falha ao buscar dados API externa");
        }#

        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        $info = $data['data'];

        // echo json_encode($data, JSON_PRETTY_PRINT);

        return new MediaEntity(
            $info['mal_id'],
            $info['title'],
            $type,
            $info['synopsis'] ?? null
        );
    }
}