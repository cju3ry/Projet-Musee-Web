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
            conferencier.idConferencier,
            conferencier.nomConferencier,
            conferencier.prenomConferencier,
            CONCAT('#', GROUP_CONCAT(DISTINCT specialites.intitule SEPARATOR ', '), '#') AS specialites,
            conferencier.telephone,
            conferencier.estEmploye,
            GROUP_CONCAT(DISTINCT CONCAT(REPLACE(indisponibilites.dateDebutIndispo, '-', '/'), ';', REPLACE(indisponibilites.dateFinIndispo, '-', '/')) SEPARATOR ', ') AS indisponibilites
        FROM conferencier
        LEFT JOIN specialites ON conferencier.idConferencier = specialites.idConferencier
        LEFT JOIN indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
        GROUP BY conferencier.idConferencier, conferencier.nomConferencier, conferencier.prenomConferencier, conferencier.telephone, conferencier.estEmploye
    ");
	$conferenciers = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: text/csv');
	header('Content-Disposition: attachment;filename=conferenciers.csv');

	$output = fopen('php://output', 'w');

	// Create the header row
	$header = ['Ident', 'Nom', 'Prenom', 'Specialité', 'Telephone', 'Employe', 'Indisponibilite', '', '', ''];
	fputcsv($output, $header, ';');

	// Create the data rows
	foreach ($conferenciers as $conferencier) {
		$indisponibilites = explode(', ', $conferencier['indisponibilites'] ?? '');
		$row = [
			$conferencier['idConferencier'],
			$conferencier['nomConferencier'],
			$conferencier['prenomConferencier'],
			$conferencier['specialites'],
			$conferencier['telephone'],
			$conferencier['estEmploye'] ? 'oui' : 'non',
			$indisponibilites[0] ?? '',
			$indisponibilites[1] ?? '',
			$indisponibilites[2] ?? '',
			$indisponibilites[3] ?? ''
		];

		fputcsv($output, $row, ';');
	}

	fclose($output);
	exit();
} catch (PDOException $e) {
	error_log("Database error: " . $e->getMessage()); // Log the error message
	echo "Database error: " . $e->getMessage(); // Display the error message
	exit();
}
?>