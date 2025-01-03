<?php
session_start();
include("../fonction/fonctionsAuthentification.php");

if (!isset($_SESSION['loginAdmin'])) {
	header("location: ../index.php");
	exit();
}

try {
	$pdo = connecterBd();
	$stmt = $pdo->query("SELECT * FROM exposition");
	$expositions = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename=expositions.csv');

	$output = fopen('php://output', 'w');
	fputcsv($output, ['Ident', 'Intitulé', 'PériodeDeb', 'PériodeFin', 'nombre', 'motclé', 'résumé', 'Début', 'Fin'], ';');

	foreach ($expositions as $exposition) {
		fputcsv($output, $exposition, ';');
	}

	fclose($output);
	exit();
} catch (PDOException $e) {
	header('Location: erreurConnexion.php');
	exit();
}
?>