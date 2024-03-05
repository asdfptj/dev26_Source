<?
exec('ps -ef > ps.out');

$fileContent = file_get_contents('ps.out');

$lines = explode("\n", $fileContent);

foreach ($lines as $line) {
  if (!empty($line)) {
    $words = preg_split("/\s+/", $line);

    $cmd = implode(" ", array_slice($words, 7));

    echo $cmd . PHP_EOL;
  }
}

?>