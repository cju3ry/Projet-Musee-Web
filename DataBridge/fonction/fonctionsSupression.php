<?php
    function suppressionEmploye($pdo, $idEmploye) {
		$tab = [];

		try {
			$requeteVerif = $pdo->prepare("SELECT idVisite FROM visite WHERE idEmploye = :idEmploye");
			$requeteVerif->bindParam(':idEmploye', $idEmploye);
			$requeteVerif->execute();

			while ($ligne = $requeteVerif->fetch(PDO::FETCH_ASSOC)) {
				$tab[] = $ligne['idEmploye'];
			}
	
			if (empty($tab)) {
			    $pdo->beginTransaction();
				$stmt1 = $pdo->prepare("DELETE FROM login WHERE idEmploye = (:employeId)");
				$stmt2 = $pdo->prepare("DELETE FROM employe WHERE idEmploye = (:employeId)");
				$stmt1->bindParam(':employeId', $idEmploye);
				$stmt2->bindParam(':employeId', $idEmploye);

				if ($stmt1->execute() && $stmt2->execute()) {
                    $pdo->commit();
                }

                return true;
			}
		} catch (Exception $e) {
		    $pdo->rollBack();
		    return false;
		}
	}

    function suppressionExposition($pdo, $idExposition) {
		$tab = [];
		
		try {
			$requeteVerif = $pdo->prepare("SELECT idVisite FROM visite WHERE idExposition = :idExposition");
			$requeteVerif->bindParam(':idExposition', $idExposition);
			$requeteVerif->execute();

			while ($ligne = $requeteVerif->fetch(PDO::FETCH_ASSOC)) {
				$tab[] = $ligne['idExposition'];
			}

			if (empty($tab)) {
				$pdo->beginTransaction();

				$stmt1 = $pdo->prepare("DELETE FROM motCle WHERE idExposition = (:expositionId)");
				$stmt2 = $pdo->prepare("DELETE FROM exposition WHERE idExposition = (:expositionId)");

				$stmt1->bindParam(':expositionId', $idExposition);
				$stmt2->bindParam(':expositionId', $idExposition);

				if ($stmt1->execute() && $stmt2->execute()) {
					$pdo->commit();
				}
				
				return true;
			}
		} catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

    function suppressionConferencier($pdo, $idConferencier) {
		$tab = [];
		
		try {
			$requeteVerif = $pdo->prepare("SELECT idVisite FROM visite WHERE idExposition = :idExposition");
			$requeteVerif->bindParam(':idExposition', $idExposition);
			$requeteVerif->execute();

			while ($ligne = $requeteVerif->fetch(PDO::FETCH_ASSOC)) {
				$tab[] = $ligne['idExposition'];
			}

			if (empty($tab)) {
				$pdo->beginTransaction();
				
				$stmt1 = $pdo->prepare("DELETE FROM indisponibilite WHERE idConferencier = (:conferencierId)");
				$stmt2 = $pdo->prepare("DELETE FROM specialites WHERE idConferencier = (:conferencierId)");
				$stmt3 = $pdo->prepare("DELETE FROM conferencier WHERE idConferencier = (:conferencierId)");
				
				$stmt1->bindParam(':conferencierId', $idConferencier);
				$stmt2->bindParam(':conferencierId', $idConferencier);
				$stmt3->bindParam(':conferencierId', $idConferencier);
				
				if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute()) {
					$pdo->commit();
				}
				
				return true;
			}
		} catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

	function suppressionConferencier($pdo, $idConferencier) {
		$tab = [];
		
		try {
			$requeteVerif = $pdo->prepare("SELECT idVisite FROM visite WHERE idConferencier = :idConferencier");
			$requeteVerif->bindParam(':idConferencier', $idConferencier);
			$requeteVerif->execute();

			while ($ligne = $requeteVerif->fetch(PDO::FETCH_ASSOC)) {
				$tab[] = $ligne['idConferencier'];
			}

			if (empty($tab)) {
				$pdo->beginTransaction();
				
				$stmt1 = $pdo->prepare("DELETE FROM indisponibilites WHERE idConferencier = (:conferencierId)");
				$stmt2 = $pdo->prepare("DELETE FROM specialites WHERE idConferencier = (:conferencierId)");
				$stmt3 = $pdo->prepare("DELETE FROM conferencier WHERE idConferencier = (:conferencierId)");
				
				$stmt1->bindParam(':conferencierId', $idConferencier);
				$stmt2->bindParam(':conferencierId', $idConferencier);
				$stmt3->bindParam(':conferencierId', $idConferencier);
				
				if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute()) {
					$pdo->commit();
				}
				
				return true;
			}
		} catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}
?>