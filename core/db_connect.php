<?php
// core/db_connect.php
// Koneksi database SafeGate + helper kecil agar view tidak berisi query mentah.

date_default_timezone_set(getenv('SG_APP_TIMEZONE') ?: 'Asia/Jakarta');

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load Composer autoloader only when dependencies are installed.
$sgComposerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($sgComposerAutoload)) {
    require_once $sgComposerAutoload;
}

define('SG_DB_HOST', getenv('SG_DB_HOST') ?: '127.0.0.1');
define('SG_DB_NAME', getenv('SG_DB_NAME') ?: 'safegate_db');
define('SG_DB_USER', getenv('SG_DB_USER') ?: 'root');
define('SG_DB_PASS', getenv('SG_DB_PASS') ?: '');

// Midtrans Configurations (Default Sandbox from User)
define('SG_MIDTRANS_SERVER_KEY', getenv('SG_MIDTRANS_SERVER_KEY'));
define('SG_MIDTRANS_CLIENT_KEY', getenv('SG_MIDTRANS_CLIENT_KEY'));
define('SG_MIDTRANS_IS_PRODUCTION', getenv('SG_MIDTRANS_IS_PRODUCTION') === 'true');


function sg_db(): ?PDO
{
    static $pdo = null;
    static $failed = false;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if ($failed) {
        return null;
    }

    try {
        $dsn = 'mysql:host=' . SG_DB_HOST . ';dbname=' . SG_DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, SG_DB_USER, SG_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;
    } catch (Throwable $error) {
        $failed = true;
        $GLOBALS['sg_db_error'] = $error->getMessage();
        return null;
    }
}

function sg_db_error(): ?string
{
    return $GLOBALS['sg_db_error'] ?? null;
}

function sg_fetch_all(string $sql, array $params = []): array
{
    $db = sg_db();
    if (!$db) {
        return [];
    }

    try {
        $statement = $db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    } catch (Throwable $error) {
        $GLOBALS['sg_db_error'] = $error->getMessage();
        return [];
    }
}

function sg_fetch_one(string $sql, array $params = []): ?array
{
    $rows = sg_fetch_all($sql, $params);
    return $rows[0] ?? null;
}

function sg_execute(string $sql, array $params = []): bool
{
    $db = sg_db();
    if (!$db) {
        return false;
    }

    try {
        $statement = $db->prepare($sql);
        return $statement->execute($params);
    } catch (Throwable $error) {
        $GLOBALS['sg_db_error'] = $error->getMessage();
        return false;
    }
}

function sg_h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sg_rupiah($amount): string
{
    return 'Rp ' . number_format((int) $amount, 0, ',', '.');
}

function sg_upload_error(): ?string
{
    return $GLOBALS['sg_upload_error'] ?? null;
}

function sg_upload_file(
    string $field,
    string $folder,
    string $fallback = 'pending-upload',
    array $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'],
    int $maxBytes = 10485760
): string {
    unset($GLOBALS['sg_upload_error']);

    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        if (!empty($_FILES[$field]) && ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $GLOBALS['sg_upload_error'] = 'Upload gagal. Pastikan file tidak rusak dan ukurannya sesuai batas.';
        }
        return $fallback;
    }

    $fileSize = (int) ($_FILES[$field]['size'] ?? 0);
    if ($fileSize <= 0 || $fileSize > $maxBytes) {
        $GLOBALS['sg_upload_error'] = 'Ukuran file maksimal ' . number_format($maxBytes / 1048576, 0) . 'MB.';
        return $fallback;
    }

    $originalName = basename((string) $_FILES[$field]['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $safeExtension = preg_replace('/[^a-z0-9]/', '', $extension) ?: '';
    $allowedExtensions = array_map('strtolower', $allowedExtensions);

    if (!in_array($safeExtension, $allowedExtensions, true)) {
        $GLOBALS['sg_upload_error'] = 'Format file tidak didukung. Gunakan: ' . strtoupper(implode(', ', $allowedExtensions)) . '.';
        return $fallback;
    }

    $tmpPath = (string) ($_FILES[$field]['tmp_name'] ?? '');
    $detectedMime = '';
    if (function_exists('finfo_open') && is_file($tmpPath)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $detectedMime = (string) finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
        }
    }

    $mimeByExtension = [
        'pdf' => ['application/pdf'],
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'webp' => ['image/webp'],
        'pkpass' => ['application/vnd.apple.pkpass', 'application/zip', 'application/octet-stream'],
    ];

    if ($detectedMime !== '' && isset($mimeByExtension[$safeExtension]) && !in_array($detectedMime, $mimeByExtension[$safeExtension], true)) {
        $GLOBALS['sg_upload_error'] = 'Isi file tidak cocok dengan ekstensi .' . $safeExtension . '.';
        return $fallback;
    }

    $baseDir = dirname(__DIR__) . '/assets/uploads/' . trim($folder, '/');
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0777, true);
    }

    $fileName = uniqid('sg_', true) . '.' . $safeExtension;
    $targetPath = $baseDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
        $GLOBALS['sg_upload_error'] = 'File gagal dipindahkan ke folder upload.';
        return $fallback;
    }

    return 'assets/uploads/' . trim($folder, '/') . '/' . $fileName;
}

function sg_current_user_id(?string $role = null): ?int
{
    if (!empty($_SESSION['user_id'])) {
        if ($role !== null && ($_SESSION['role'] ?? null) !== $role) {
            return null;
        }

        return (int) $_SESSION['user_id'];
    }

    return null;
}

function sg_flash(?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['sg_flash'] = ['message' => $message, 'type' => $type];
        return null;
    }

    $flash = $_SESSION['sg_flash'] ?? null;
    unset($_SESSION['sg_flash']);
    return $flash;
}
