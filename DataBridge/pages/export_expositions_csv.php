<?php
session_start();
include("../fonction/fonctionsAuthentification.php");

if (!isset($_SESSION['loginAdmin'])) {
	header("location: ../index.php");
	exit();
}

try {
	$pdo = connecterBd();
	$stmt = $pdo->query("
        SELECT
            exposition.idExposition AS Ident,
            exposition.intitule AS Intitulé,
            exposition.periodeDebut AS PériodeDeb,
            exposition.periodeFin AS PériodeFin,
            exposition.nombreOeuvres AS nombre,
            CONCAT('#', GROUP_CONCAT(DISTINCT motscle.motCle SEPARATOR ', '), '#') AS motclé,
            exposition.resume AS résumé,
            DATE_FORMAT(exposition.debutExpoTemp, '%d/%m/%Y') AS Début,
			DATE_FORMAT(exposition.finExpoTemp, '%d/%m/%Y') AS Fin
        FROM exposition
        LEFT JOIN motscle ON exposition.idExposition = motscle.idExposition
        GROUP BY exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp
    ");
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