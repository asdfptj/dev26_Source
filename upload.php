<?

$file = $_FILES['file'];

$file_name = $file['name'];
$file_tmp = $file['tmp_name'];

$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
$allowed = array('txt');

if (in_array($file_ext, $allowed)) {
    $filePath = '/home/tjpark/public_html/upload/' . $file_name;

    if (move_uploaded_file($file_tmp, $filePath)) {
        $downloadLink = $filePath;
        $download = "<a href='download.php?file=" . urlencode($downloadLink) . "' download>" . $filePath . "</a>";

        $fileContent = file_get_contents($downloadLink);

        if ($fileContent !== false) {
            echo "<div class='downinfo'><span>다운링크 : &nbsp;</span>" . $download . "</div><br>";
            echo "<div class='downinfo'><span>파일명 : &nbsp;</span>" . $file_name . "</div>";
            echo "<div class='downinfo'><span>파일내용: &nbsp;</span>" . nl2br(htmlspecialchars($fileContent)) . "</div>";
        } else {
            echo "파일 읽기에 실패하였습니다.";
        }
    }
}
?>