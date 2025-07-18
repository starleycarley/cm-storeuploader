<?php
require_once __DIR__.'/../lib/db.php';
require_once __DIR__.'/../lib/drive.php';

session_start();

// Check if logged in
if (!isset($_SESSION['store_id'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$store_id = $_SESSION['store_id'];

// Get the upload ID
$id = $_GET['id'] ?? 0;
$size = $_GET['size'] ?? 'medium';

// Get upload details - verify it belongs to this store
$pdo = get_pdo();
$stmt = $pdo->prepare('SELECT drive_id, mime FROM uploads WHERE id = ? AND store_id = ?');
$stmt->execute([$id, $store_id]);
$upload = $stmt->fetch();

if (!$upload) {
    // Return a 1x1 transparent pixel if not found
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}

// Check if it's a video
if (strpos($upload['mime'], 'video') !== false) {
    // Return video placeholder
    header('Content-Type: image/svg+xml');
    $width = $size === 'small' ? 80 : 200;
    echo '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="'.$width.'" height="'.$width.'" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <rect width="100" height="100" fill="#000"/>
        <circle cx="50" cy="50" r="20" fill="none" stroke="#fff" stroke-width="3"/>
        <polygon points="45,40 45,60 60,50" fill="#fff"/>
    </svg>';
    exit;
}

try {
    // Get access token
    $token = drive_get_access_token();

    // Try to get thumbnail metadata
    $metadataUrl = "https://www.googleapis.com/drive/v3/files/{$upload['drive_id']}?fields=thumbnailLink,hasThumbnail&access_token={$token}";

    $ch = curl_init($metadataUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
    ]);

    $metadataResult = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $metadata = json_decode($metadataResult, true);
        if (!empty($metadata['thumbnailLink'])) {
            // Use the thumbnail link
            $width = $size === 'small' ? 100 : 400;
            $thumbnailUrl = str_replace('=s220', '=s'.$width, $metadata['thumbnailLink']);

            $ch = curl_init($thumbnailUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"]
            ]);

            $imageData = curl_exec($ch);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $imageData) {
                header('Content-Type: ' . $contentType);
                header('Cache-Control: public, max-age=86400'); // Cache for 24 hours
                echo $imageData;
                exit;
            }
        }
    }

    // If no thumbnail, throw exception to show placeholder
    throw new Exception('No thumbnail available');

} catch (Exception $e) {
    // Return image placeholder
    header('Content-Type: image/svg+xml');
    $width = $size === 'small' ? 80 : 200;
    echo '<?xml version="1.0" encoding="UTF-8"?>
    <svg width="'.$width.'" height="'.$width.'" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <rect width="100" height="100" fill="#e9ecef"/>
        <path d="M30 70 L30 40 L45 25 L55 25 L70 40 L70 70 Z" fill="#6c757d"/>
        <circle cx="45" cy="45" r="8" fill="#e9ecef"/>
    </svg>';
}
?>