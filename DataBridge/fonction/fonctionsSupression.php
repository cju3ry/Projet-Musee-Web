<?php
    function suppressionEmploye($pdo, $idEmploye) {
		try {
			$pdo->beginTransaction();
			$stmt1 = $pdo->prepare("DELETE FROM login WHERE idEmploye = (:employeId)");
			$stmt2 = $pdo->prepare("DELETE FROM employe WHERE idEmploye = (:employeId)");
			$stmt1->bindParam(':employeId', $idEmploye);
			$stmt2->bindParam(':employeId', $idEmploye);
			$stmt->execute();
		} catch (Exception $e) {
			$pdo->rollBack();
		}
	}

    function suppressionVisite($pdo, $idVisite) {
		$stmt = $pdo->prepare("DELETE FROM visite WHERE idVisite = (:visiteId)");
		$stmt->bindParam(':visiteId', $idVisite);
		$stmt->execute();
	}

    function suppressionExposition($pdo, $idExposition) {
		try {
			$pdo->beginTransaction();
			suppressionMotCle($pdo, $idConferencier);
			$stmt = $pdo->prepare("DELETE FROM exposition WHERE idExposition = (:expositionId)");
			$stmt->bindParam(':expositionId', $idExposition);
			$stmt->execute();
		} catch (Exception $e) {
			$pdo->rollBack();
		}
	}

    function suppressionMotCles($pdo, $idExposition) {
		$stmt = $pdo->prepare("DELETE FROM motCle WHERE idExposition = (:expositionId)");
		$stmt->bindParam(':expositionId', $idExposition);
		$stmt->execute();
	}

    function suppressionConferencier($pdo, $idConferencier) {
		try {
			$pdo->beginTransaction();
			suppressionIndisponibilites($pdo, $idExposition);
			suppressionSpecialites($pdo, $idConferencier);
			$stmt = $pdo->prepare("DELETE FROM conferencier WHERE idConferencier = (:conferencierId)");
			$stmt->bindParam(':conferencierId', $idConferencier);
			$stmt->execute();
		} catch (Exception $e) {
			$pdo->rollBack();
		}
	}

    function suppressionIndisponibilites($pdo, $idExposition) {
		$stmt = $pdo->prepare("DELETE FROM indisponibilite WHERE idConferencier = (:conferencierId)");
		$stmt->bindParam(':conferencierId', $idConferencier);
		$stmt->execute();
	}

	function suppressionSpecialites($pdo, $idConferencier) {
		$stmt = $pdo->prepare("DELETE FROM specialites WHERE idConferencier = (:idConferencier)");
		$stmt->bindParam(':idConferencier', $idConferencier);
		$stmt->execute();
	}
?>