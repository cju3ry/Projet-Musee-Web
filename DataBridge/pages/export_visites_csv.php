<?php
session_start();
include("../fonction/fonctionsAuthentification.php");

if (!isset($_SESSION['loginAdmin'])) {
	header("location: ../index.php");
	exit();
}

try {
	$pdo = connecterBd();
	$stmt = $pdo->query("SELECT * FROM visite");
	$visites = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename=visites.csv');

	$output = fopen('php://output', 'w');
	fputcsv($output, ['Ident', 'Exposition', 'Conférencier', 'Employé', 'date', 'heuredebut', 'Intitulé', 'Téléphone'], ';');

	foreach ($visites as $visite) {
		fputcsv($output, $visite, ';');
	}

	fclose($output);
	exit();
} catch (PDOException $e) {
	header('Location: erreurConnexion.php');
	exit();
}
?>