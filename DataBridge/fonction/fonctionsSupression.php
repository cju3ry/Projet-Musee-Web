<?php
    function suppressionEmploye($pdo, $idEmploye) {
		$stmt = $pdo->prepare("DELETE FROM employe WHERE idEmploye = (:employeId)");
		$stmt->bindParam(':employeId', $idEmploye);
		$stmt->execute();
	}

    function suppressionVisite($pdo, $idVisite) {
		$stmt = $pdo->prepare("DELETE FROM visite WHERE idVisite = (:visiteId)");
		$stmt->bindParam(':visiteId', $idVisite);
		$stmt->execute();
	}

    function suppressionExposition($pdo, $idExposition) {
		$stmt = $pdo->prepare("DELETE FROM exposition WHERE idExposition = (:expositionId)");
		$stmt->bindParam(':expositionId', $idExposition);
		$stmt->execute();

        suppressionMotCle($pdo, $idConferencier);
	}

    function suppressionMotCles($pdo, $idExposition) {
		$stmt = $pdo->prepare("DELETE FROM motCle WHERE idExposition = (:expositionId)");
		$stmt->bindParam(':expositionId', $idExposition);
		$stmt->execute();
	}

    function suppressionConferencier($pdo, $idConferencier) {
		$stmt = $pdo->prepare("DELETE FROM conferencier WHERE idConferencier = (:conferencierId)");
		$stmt->bindParam(':conferencierId', $idConferencier);
		$stmt->execute();
	}

    function suppressionIndisponibilites($pdo, $idExposition) {
		$stmt = $pdo->prepare("DELETE FROM indisponibilite WHERE idConferencier = (:conferencierId)");
		$stmt->bindParam(':conferencierId', $idConferencier);
		$stmt->execute();
	}
?>