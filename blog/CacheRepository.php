<?php
declare(strict_types=1);

class CacheRepository {
    private PDO $pdo;

    public function __construct(string $dbPath = '/tmp/manga_anime_cache.sqlite') {
        // Conexão com SQLite. O PDO lança exceções em caso de erro por padrão agora.
        $this->pdo = new PDO("sqlite:" . $dbPath);
        $this->initDb();
    }

    private function initDb(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS media_cache (
                id INTEGER PRIMARY KEY,
                title TEXT NOT NULL,
                type TEXT NOT NULL,
                synopsis TEXT
            )
        ");
    }

    public function get(int $id): ?MediaEntity {
        $stmt = $this->pdo->prepare("SELECT * FROM media_cache WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new MediaEntity(
            (int) $row['id'],
            $row['title'],
            MediaType::from($row['type']), // Converte a string do banco de volta para o Enum
            $row['synopsis']
        );
    }

    public function save(MediaEntity $media): void {
        $stmt = $this->pdo->prepare("
            INSERT OR REPLACE INTO media_cache (id, title, type, synopsis) 
            VALUES (:id, :title, :type, :synopsis)
        ");
        
        $stmt->execute([
            'id' => $media->id,
            'title' => $media->title,
            'type' => $media->type->value,
            'synopsis' => $media->synopsis
        ]);
    }
}

