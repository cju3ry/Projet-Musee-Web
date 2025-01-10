<?php
    // suppression d'un employé
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
    
    // suppression d'une exposition
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
    
    // suppression d'un conférencier
    function suppressionConferencier($pdo, $idConferencier) {
        try {
            // Démarrer une transaction
            $pdo->beginTransaction();
    
            // Supprimer les spécialités associées au conférencier
            $sqlDeleteSpecialites = "DELETE FROM specialites WHERE idConferencier = :idConferencier";
            $stmtDeleteSpecialites = $pdo->prepare($sqlDeleteSpecialites);
            $stmtDeleteSpecialites->bindParam(':idConferencier', $idConferencier);
            if (!$stmtDeleteSpecialites->execute()) {
                throw new Exception("Erreur lors de la suppression des spécialités.");
            }
    
            // Supprimer les indisponibilités associées au conférencier
            $sqlDeleteIndisponibilites = "DELETE FROM indisponibilites WHERE idConferencier = :idConferencier";
            $stmtDeleteIndisponibilites = $pdo->prepare($sqlDeleteIndisponibilites);
            $stmtDeleteIndisponibilites->bindParam(':idConferencier', $idConferencier);
            if (!$stmtDeleteIndisponibilites->execute()) {
                throw new Exception("Erreur lors de la suppression des indisponibilités.");
            }
    
            // Supprimer le conférencier
            $sqlDeleteConferencier = "DELETE FROM conferencier WHERE idConferencier = :idConferencier";
            $stmtDeleteConferencier = $pdo->prepare($sqlDeleteConferencier);
            $stmtDeleteConferencier->bindParam(':idConferencier', $idConferencier);
            if (!$stmtDeleteConferencier->execute()) {
                throw new Exception("Erreur lors de la suppression du conférencier.");
            }
    
            // Commit de la transaction si tout s'est bien passé
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction si une erreur survient
            $pdo->rollBack();
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    // suppression d'une visite
    function suppressionVisite($pdo, $idVisite) {
        // Vérification que l'identifiant est fourni
        if (empty($idVisite)) {
            return false;
        }
    
        // Requête SQL pour supprimer la visite
        $requete = "DELETE FROM visite WHERE idVisite = :idVisite";
    
        try {
            // Préparation et exécution de la requête
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':idVisite', $idVisite, PDO::PARAM_STR);
            $stmt->execute();
    
            // Vérification si une ligne a été supprimée
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Gestion des erreurs
            return false;
        }
    }
?>