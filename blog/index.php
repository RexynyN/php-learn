<?php 
declare(strict_types=1);

// Erros de Dev
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once 'MediaEntity.php';
require_once 'CacheRepository.php';
require_once 'ApiClient.php';



// echo $_SERVER['REQUEST_URI'] ;
echo basename($_SERVER['REQUEST_URI']);

try {
    // Só tenta processar se o usuário enviou um ID (se não, só carrega a tela vazia)
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $typeStr = $_GET['type'] ?? 'anime';
        $type = MediaType::tryFrom($typeStr); 
        
        if (!$type) {
            throw new InvalidArgumentException("Tipo inválido. Use anime ou manga.");
        }

        $cache = new CacheRepository();
        $api = new ApiClient();

        $media = $cache->get($id);
        $source = 'Cache Local (SQLite)';

        if (!$media) {
            $media = $api->fetchMediaInfo($id, $type);
            $cache->save($media);
            $source = 'API Externa (Jikan)';
        }
    }
} catch (Throwable $e) {
    // Em vez de json_encode, passamos o erro para a view
    $error = $e->getMessage();
}

?> 

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Mídia</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent: #38bdf8;
            --border: #334155;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem;
            min-height: 100vh;
        }

        .search-form {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            width: 100%;
            max-width: 600px;
        }

        .search-form input, .search-form select {
            background: var(--bg-color);
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 1rem;
        }

        .search-form input[type="number"] { flex: 1; }

        .search-form button {
            background: var(--accent);
            color: #0f172a;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .search-form button:hover { opacity: 0.9; }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .title { font-size: 1.75rem; line-height: 1.2; }

        .badge {
            background: rgba(56, 189, 248, 0.1);
            color: var(--accent);
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .synopsis {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .meta-footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            font-size: 0.875rem;
            color: var(--text-muted);
            text-align: right;
        }
        
        .error { color: #f87171; text-align: center; }
    </style>
</head>
<body>

    <!-- Formulário que faz a requisição GET para o próprio index.php -->
    <form class="search-form" method="GET" action="/blog">
        <input type="number" name="id" placeholder="ID (ex: 1, 20, 50)" value="<?= htmlspecialchars((string)($id ?? '')) ?>" required>
        <select name="type">
            <option value="anime" <?= (isset($typeStr) && $typeStr === 'anime') ? 'selected' : '' ?>>Anime</option>
            <option value="manga" <?= (isset($typeStr) && $typeStr === 'manga') ? 'selected' : '' ?>>Mangá</option>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="card"><p class="error"><?= htmlspecialchars($error) ?></p></div>
    <?php elseif (isset($media)): ?>
        <!-- O Card de Resultado -->
        <article class="card">
            <header class="card-header">
                <h1 class="title"><?= htmlspecialchars($media->title) ?></h1>
                <span class="badge"><?= htmlspecialchars($media->type->value) ?></span>
            </header>
            
            <div class="synopsis">
                <!-- nl2br converte quebras de linha do texto em tags <br> do HTML -->
                <?= nl2br(htmlspecialchars($media->synopsis ?? 'Nenhuma sinopse disponível.')) ?>
            </div>

            <footer class="meta-footer">
                Carregado via: <strong><?= htmlspecialchars($source) ?></strong>
            </footer>
        </article>
    <?php else: ?>
        <p style="color: var(--text-muted);">Digite um ID e escolha o tipo para buscar.</p>
    <?php endif; ?>

</body>
</html>