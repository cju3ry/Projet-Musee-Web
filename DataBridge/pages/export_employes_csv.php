<?php
session_start();
include("../fonction/fonctionsAuthentification.php");

if (!isset($_SESSION['loginAdmin'])) {
	header("location: ../index.php");
	exit();
}

try {
	$pdo = connecterBd();
	$stmt = $pdo->query("SELECT * FROM employe");
	$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename=employes.csv');

	$output = fopen('php://output', 'w');
	fputcsv($output, ['Ident', 'Nom', 'Prenom', 'Telephone'], ';');

	foreach ($employes as $employe) {
		fputcsv($output, $employe, ';');
	}

	fclose($output);
	exit();
} catch (PDOException $e) {
	header('Location: erreurConnexion.php');
	exit();
}
?>