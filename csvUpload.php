<?
error_reporting(E_ALL);
ini_set('display_errors', '1');


function totalPage($csvFile, $pageSize) {
    $totalData = count(file($csvFile));
    return ceil($totalData / $pageSize);
}


function displayTable($handle, $startRow, $pageSize) {
    $headers = fgetcsv($handle);

    $tableHtml = '<table border="1">';
    $tableHtml .= '<thead><tr>';
    foreach ($headers as $header) {
        $tableHtml .= '<th>' . htmlspecialchars(iconv('CP949', 'UTF-8', $header)) . '</th>';
    }
    $tableHtml .= '</tr></thead>';

    $tableHtml .= '<tbody>';
    $rowCount = 0;

    while ($rowCount < $startRow && ($row = fgetcsv($handle)) !== false) {
        $rowCount++;
    }

    while ($rowCount < $startRow + $pageSize && ($row = fgetcsv($handle)) !== false) {
        $tableHtml .= '<tr>';
        foreach ($row as $value) {
            $tableHtml .= '<td>' . htmlspecialchars(iconv('CP949', 'UTF-8', $value)) . '</td>';
        }
        $tableHtml .= '</tr>';
        $rowCount++;
    }

    $tableHtml .= '</tbody>';
    $tableHtml .= '</table>';

    return $tableHtml;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile']['tmp_name'];
    $handle = fopen($csvFile, 'r');

    $currentPage = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $pageSize = 1000;
    $startRow = ($currentPage - 1) * $pageSize;

    $tableHtml = displayTable($handle, $startRow, $pageSize);

    fclose($handle);

    $totalPages = totalPage($csvFile, $pageSize);

    $nextPageStartRow = $startRow + $pageSize;
    $handle = fopen($csvFile, 'r');
    $rowCount = 0;
    while ($rowCount < $nextPageStartRow && ($row = fgetcsv($handle)) !== false) {
        $rowCount++;
    }
    $hasNextPage = ($rowCount < $nextPageStartRow + $pageSize) && ($row !== false);

    echo json_encode([
        'tableHtml' => $tableHtml,
        'hasNextPage' => $hasNextPage,
        'totalPages' => $totalPages
    ]);
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>