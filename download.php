<?
$filePath = isset($_GET['file']) ? urldecode($_GET['file']) : null;

if ($filePath && is_file($filePath)) {
    $filename = basename($filePath);

    header('Content-Type: text/html; charset=utf-8');
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    readfile($filePath);

    exit;
} else {
    echo '다운로드할 파일을 찾을 수 없습니다.';
}
?>