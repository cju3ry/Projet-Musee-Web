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
        // Vérifier si l'exposition est dans la table 'visite'
        $sqlCheckVisite = "SELECT idVisite FROM visite WHERE idExposition = :idExposition";
        $stmtCheckVisite = $pdo->prepare($sqlCheckVisite);
        // Supprimer les espaces avant et après l'id
        $idExposition = trim($idExposition);
        // Passage de la valeur de l'id à la requête
        $stmtCheckVisite->bindValue(':idExposition', $idExposition, PDO::PARAM_STR);
        // Exécution de la requête
        $stmtCheckVisite->execute();
        // Récupération des résultats
        $tab = [];
        // Parcours des résultats
        while ($ligne = $stmtCheckVisite->fetch(PDO::FETCH_ASSOC)) {
            $tab[] = $ligne['idVisite'];
        }
        // Si l'exposition est associée à des visites on ne peut pas la supprimer
        // On retourne false
        if (!empty($tab)) {
            return false;
        }
        // Si l'exposition n'est pas associée à des visites on peut la supprimer

        // Démarrer une transaction
        $pdo->beginTransaction();
        // Supprimer les mots-clés associés à l'exposition
        $sqlDeleteMotsCles = "DELETE FROM motsCle WHERE idExposition = :idExposition";
        $stmtDeleteMotsCles = $pdo->prepare($sqlDeleteMotsCles);
        $stmtDeleteMotsCles->bindParam(':idExposition', $idExposition);
        // Supprimer l'exposition elle-même
        $sqlDeleteExposition = "DELETE FROM exposition WHERE idExposition = :idExposition";
        $stmtDeleteExposition = $pdo->prepare($sqlDeleteExposition);
        $stmtDeleteExposition->bindParam(':idExposition', $idExposition);
        // Exécuter les requêtes
        if ($stmtDeleteMotsCles->execute() && $stmtDeleteExposition->execute()) {
            // Commit de la transaction si les deux requêtes réussissent
            $pdo->commit();
            return true;
        }
        // Si une erreur SQL
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
        // Autres erreurs (non SQL)
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}


    function suppressionVisite($pdo, $idVisite) {
		try {
			$pdo->beginTransaction();
			
			$stmt1 = $pdo->prepare("DELETE FROM visite WHERE idVisite = (:idVisite)");
			
			$stmt1->bindParam(':idVisite', $idVisite);
			
			if ($stmt1->execute()) {
				$pdo->commit();
			}
			
			return true;
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