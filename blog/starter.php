<?php
declare(strict_types=1);
// index.php - PHP Refresher (em Português)
// Execute pelo navegador apontando para este arquivo (ex: http://localhost/blog/)

function section(string $title): void {
    echo "\n<hr>\n<h2>" . htmlspecialchars($title) . "</h2>\n";
}

function showCode(string $code): void {
    echo "<pre style=\"background:#f7f7f7;padding:10px;border:1px solid #ddd;\">" . htmlspecialchars($code) . "</pre>\n";
}

function showOutput($label, $value): void {
    echo "<strong>" . htmlspecialchars($label) . "</strong>: ";
    echo "<pre>" . htmlspecialchars(var_export($value, true)) . "</pre>\n";
}

// HTML header
?><!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>PHP Refresher - index.php</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;line-height:1.4;padding:20px}pre{white-space:pre-wrap}</style>
</head>
<body>
<h1>PHP Refresher — Exemplos e conceitos importantes</h1>
<p>Arquivo de referência abrangente com trechos executáveis e explicações curtas.</p>

<?php
section('Tags e delimitadores');
echo '<p>PHP padrão: <code>&lt;?php ... ?&gt;</code>. Também existem short tags <code>&lt;?=</code> (echo curto) e short open tags se habilitadas.</p>';
showCode("<?php echo 'Olá mundo'; ?>");

section('Declaração de tipos e diretivas');
echo '<p>Habilitamos <code>strict_types=1</code> no topo para exemplos com tipagem estrita.</p>';
showCode("declare(strict_types=1);\nfunction soma(int $a, int $b): int { return $a + $b; }");
showOutput('soma(2,3)', (function(){ return (int) (2 + 3); })());

section('Variáveis e tipos');
$a = 123; $b = 1.23; $s = "texto"; $b_true = true; $nulo = null;
showOutput('inteiro $a', $a);
showOutput('float $b', $b);
showOutput('string $s', $s);
showOutput('boolean $b_true', $b_true);
showOutput('null $nulo', $nulo);

section('Interpolação de strings e concatenação');
$name = 'Maria';
showCode('$g = "Olá, $name"; // interpolação\n$h = "Olá, " . $name; // concatenação');
showOutput('Interpolada', "Olá, $name");
showOutput('Concatenada', 'Olá, ' . $name);

section('Arrays (indexados e associativos)');
$indexed = [1, 2, 3];
$assoc = ['nome' => 'João', 'idade' => 30];
showCode('$indexed = [1,2,3];\n$assoc = "nome"=>"João","idade"=>30;');
showOutput('Array indexado', $indexed);
showOutput('Array associativo', $assoc);

section('Array functions comuns');
$nums = [5, 2, 9, 1];
showCode('array_map, array_filter, array_reduce, usort');
$squared = array_map(fn($x) => $x * $x, $nums);
$filtered = array_filter($nums, fn($x) => $x > 3);
$sum = array_reduce($nums, fn($carry, $item) => $carry + $item, 0);
usort($nums, fn($a, $b) => $a <=> $b);
showOutput('array_map (square)', $squared);
showOutput('array_filter (>3)', $filtered);
showOutput('array_reduce (sum)', $sum);
showOutput('usort (sorted)', $nums);

section('Operadores importantes');
showCode('$a === $b (identidade), $a == $b (igualdade), $a <=> $b (spaceship), ++, --, ?? (null coalescing), ??=, Elvis ?:, @ (error control)');
showOutput('spaceship 1<=>2', 1 <=> 2);
showOutput('null coalescing', null ?? 'padrão');

section('Controle de fluxo');
showCode('if/elseif/else, switch, match (PHP8), for, foreach, while, do-while, break, continue');
foreach (['a','b','c'] as $i => $ch) {
    echo "<div>foreach index={$i} value={$ch}</div>\n";
}

section('Funções e closures');
function exemploFunc(int $x, int $y = 10): int { return $x + $y; }
$anon = fn($z) => $z * 2; // arrow function (PHP7.4+)
showCode('function exemploFunc(int $x, int $y=10): int { ... }\n$anon = fn($z) => $z*2;');
showOutput('exemploFunc(5)', exemploFunc(5));
showOutput('anon(7)', $anon(7));

section('Tipagem avançada');
showCode('Tipos escalares, nullable ?Type, union types TypeA|TypeB, mixed, iterable, object, callable');
function acceptsStringOrInt(string|int $v): string { return (string)$v; }
showOutput('acceptsStringOrInt(10)', acceptsStringOrInt(10));

section('Argumentos variádicos e named args');
function somaTudo(int ...$nums): int { return array_sum($nums); }
showOutput('somaTudo(1,2,3,4)', somaTudo(1,2,3,4));
showCode('somaTudo(...$nums) e chamada por named args (PHP8+)');

section('Namespaces, autoloading e PSR-4 (exemplo simples)');
showCode("namespace App\\Utils;\nclass Foo {}\n// Autoload simples (sem composer): spl_autoload_register(fn($class)=>require str_replace('\\\\','/', $class).'.php');");
echo '<p>Exemplo: usar <code>spl_autoload_register</code> ou Composer (PSR-4).</p>';

section('Classes, objetos e OOP moderno');
class Pessoa {
    public string $nome;
    private int $idade;
    public static int $count = 0;

    public function __construct(string $nome, int $idade) {
        $this->nome = $nome;
        $this->idade = $idade;
        self::$count++;
    }

    public function saudacao(): string { return "Olá, eu sou {$this->nome}, tenho {$this->idade} anos."; }

    public function __toString(): string { return $this->saudacao(); }
}
$p = new Pessoa('Ana', 28);
showOutput('Pessoa object', $p->saudacao());
showOutput('Pessoa::__toString', (string)$p);

section('Herança, interfaces, traits e visibilidade');
interface LoggerInterface { public function log(string $msg): void; }
trait SimpleLogger { public function log(string $m): void { echo "[LOG] " . htmlspecialchars($m) . "<br>\n"; } }
class AppBase { use SimpleLogger; protected string $appName = 'MeuApp'; }
class MinhaApp extends AppBase implements LoggerInterface { public function log(string $m): void { parent::log($m); } }
$app = new MinhaApp(); $app->log('Iniciado');

section('Magic methods importantes');
showCode('__construct, __destruct, __get, __set, __isset, __unset, __call, __toString, __invoke, __debugInfo');

section('Namespaces e aliases (use)');
showCode('use Some\\Package\\ClassName as Alias;');

section('Exceções e tratamento de erros');
showCode('try { ... } catch (Exception $e) { ... } finally { ... }\nset_error_handler, set_exception_handler');
try {
    throw new RuntimeException('Exemplo de exceção');
} catch (RuntimeException $e) {
    echo '<div style="color:darkred">Exceção capturada: ' . htmlspecialchars($e->getMessage()) . '</div>';
} finally {
    echo '<div>Finally executado</div>';
}

section('Erros e nível de relatório');
showCode('error_reporting(E_ALL); ini_set("display_errors", "1");');
error_reporting(E_ALL);
ini_set('display_errors', '1');

section('Filtragem, validação e segurança básica');
showCode('filter_var, htmlspecialchars, prepared statements (PDO), password_hash, password_verify');
$unsafe = '<script>alert(1)</script>';
showOutput('htmlspecialchars(unsafe)', htmlspecialchars($unsafe));
showCode("// Nunca concatene valores do usuário em queries SQL. Use prepared statements com PDO.\n// Exemplo (comentado):\n// $pdo = new PDO(...);\n// $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');\n// $stmt->execute(['email' => $email]);");

section('Password hashing');
$pw = 'minhaSenhaSecreta';
$hash = password_hash($pw, PASSWORD_DEFAULT);
showCode('password_hash + password_verify');
showOutput('hash gerado', $hash);
showOutput('password_verify correto', password_verify($pw, $hash));

section('PDO — prepared statements (exemplo seguro, não executa conexão)');
showCode('// Exemplo ilustrativo. Configure DSN, usuário e senha para usar.\n$dsn = "mysql:host=127.0.0.1;dbname=meudb;charset=utf8mb4";\n$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);\n$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");\n$stmt->execute([":id" => $id]);\n$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);');

section('Arquivos e diretórios');
showCode('file_get_contents, file_put_contents, fopen/fread/fwrite, is_readable, is_writable, scandir');
showOutput('arquivo atual (__FILE__ e __DIR__)', [__FILE__, __DIR__]);

section('JSON e serialização');
$arr = ['a'=>1,'b'=>2];
showCode('json_encode, json_decode, serialize, unserialize (cuidado com unserialize em dados não confiáveis)');
showOutput('json_encode', json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

section('DateTime e fusos horários');
$dt = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
showCode('DateTime e DateTimeZone');
showOutput('DateTime agora', $dt->format(DateTime::ATOM));

section('Regex (PCRE)');
$text = 'Telefone: (11) 91234-5678';
preg_match('/\(\d{2}\) \d{4,5}-\d{4}/', $text, $m);
showCode('preg_match e preg_replace');
showOutput('match telefone', $m ?: 'nenhum');

section('Iterators e Generators');
function genNumbers(int $n): Generator { for ($i=0;$i<$n;$i++) { yield $i; } }
showCode('Generators yield — consumidos com foreach');
$g = genNumbers(5);
foreach ($g as $v) echo "Generator value: {$v}<br>\n";

section('SPL e estruturas úteis');
showCode('SplDoublyLinkedList, SplStack, SplQueue, SplPriorityQueue, ArrayObject');
$ao = new ArrayObject([1,2,3]);
showOutput('ArrayObject', $ao->getArrayCopy());

section('Reflection (introspecção)');
$ref = new ReflectionClass(Pessoa::class);
showCode('ReflectionClass, ReflectionMethod, ReflectionParameter');
showOutput('Reflection class name', $ref->getName());

section('CLI vs Web');
if (php_sapi_name() === 'cli') {
    echo '<div>Rodando no CLI</div>';
} else {
    echo '<div>Rodando via servidor web (SAPI: ' . php_sapi_name() . ')</div>';
}

section('Superglobais');
showCode('$_GET, $_POST, $_REQUEST, $_COOKIE, $_FILES, $_SESSION, $_ENV, $_SERVER');
showOutput('$_SERVER keys (exemplo)', array_slice(array_keys($_SERVER), 0, 10));

section('Sessões e cookies');
showCode('session_start(), $_SESSION, setcookie()');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$_SESSION['demo'] = ($_SESSION['demo'] ?? 0) + 1;
showOutput('$_SESSION["demo"] contador', $_SESSION['demo']);

section('Boas práticas rápidas');
echo '<ul>';
echo '<li>Use Prepared Statements para DB.</li>';
echo '<li>Evite unserialize em dados externos.</li>';
echo '<li>Sanitize output com <code>htmlspecialchars</code>.</li>';
echo '<li>Use Composer para dependências e autoload.</li>';
echo '<li>Habilite e monitore logs (monolog, syslog, etc.).</li>';
echo '</ul>';

section('PHP 8+ features importantes');
showCode('Named parameters, union types, attributes, match expression, constructor property promotion, JIT (engine), attributes, enums (8.1), readonly (8.1), fibers (8.1)');

section('Enums (PHP 8.1) — exemplo');
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    eval('enum Status: string { case ACTIVE = "active"; case INACTIVE = "inactive"; }');
    showCode('enum Status: string { case ACTIVE = "active"; case INACTIVE = "inactive"; }');
    $st = Status::ACTIVE;
    showOutput('enum Status', $st->value ?? (string)$st);
} else {
    echo '<div>Enums requerem PHP 8.1+</div>';
}

section('Notas finais e recursos');
echo '<p>Este arquivo foi escrito para servir como referência rápida. Recursos úteis:</p>';
echo '<ul>';
echo '<li><a href="https://www.php.net/">php.net</a></li>';
echo '<li><a href="https://www.php-fig.org/">PHP-FIG (PSR)</a></li>';
echo '<li><a href="https://www.phptherightway.com/">PHP: The Right Way</a></li>';
echo '</ul>';

echo '<p>Próximos passos sugeridos: testar num servidor local (XAMPP/lampp), rodar <code>php -l index.php</code> para checar sintaxe, e configurar Composer para autoload.</p>';

?>
</body>
</html>
