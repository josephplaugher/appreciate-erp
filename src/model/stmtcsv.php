<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$csvdata = json_decode($_SESSION['csvstring'],true);
	
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=fin_stmt.csv');

$fp = fopen('php://output', 'w');

foreach ($csvdata as $cells) {
    fputcsv($fp, $cells);
}

fclose($fp);

?>
