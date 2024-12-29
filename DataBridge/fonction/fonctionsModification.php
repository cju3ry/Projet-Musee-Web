<?php
    function modifierEmploye($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye, $login, $pwd = null) {
        try {
            $pdo->beginTransaction();

            // Mise à jour des informations de l'employé
            $stmt1 = $pdo->prepare('UPDATE employe 
                                SET nomEmploye = :nomEmploye,
                                    prenomEmploye = :prenomEmploye,
                                    NumTelEmploye = :numTelEmploye
                                WHERE idEmploye = :idEmploye');

            $stmt1->bindParam(':nomEmploye', $nomEmploye);
            $stmt1->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt1->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt1->bindParam(':idEmploye', $idEmploye);

            // Mise à jour du login (et du mot de passe si fourni)
            $query = 'UPDATE login SET login = :login';
            if ($pwd !== null) {
                $query .= ', pwd = :pwd';
            }
            $query .= ' WHERE idEmploye = :idEmploye';

            $stmt2 = $pdo->prepare($query);
            $stmt2->bindParam(':login', $login);
            if ($pwd !== null) {
                $stmt2->bindParam(':pwd', $pwd);
            }
            $stmt2->bindParam(':idEmploye', $idEmploye);

            if ($stmt1->execute() && $stmt2->execute()) {
                $pdo->commit();
                return true;
            }

        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    function verifIdConfExistant($pdo, $idConferencier) {
        try {
            $stmt = $pdo->prepare('SELECT idConferencier FROM visite WHERE idConferencier = :idConferencier');

            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $tableau = [];
	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tableau[] = [
				'idConferencier' => $row['idConferencier']
			];
		}

        if (!empty($tableau)) {
            return true;
        }

        return false;
    }

    function verifIdemployeExistant($pdo, $idEmploye) {
        try {
            $stmt = $pdo->prepare('SELECT idEmploye FROM visite WHERE idEmploye = :idEmploye');

            $stmt->bindParam(':idEmploye', $idEmploye);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $tableau = [];
	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tableau[] = [
				'idEmploye' => $row['idEmploye']
			];
		}

        if (!empty($tableau)) {
            return true;
        }

        return false;
    }

    function verifIdExpoExistant($pdo, $idExposition) {
        try {
            $stmt = $pdo->prepare('SELECT idExposition FROM visite WHERE idExposition = :idExposition');

            $stmt->bindParam(':idExposition', $idExposition);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $tableau = [];
	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tableau[] = [
				'idExposition' => $row['idExposition']
			];
		}

        if (!empty($tableau)) {
            return true;
        }

        return false;
    }

    function modifierVisite($pdo, $idVisite, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE visite
                                   SET dateVisite = :dateVisite,
                                       heureDebutVisite = :heureDebutVisite,
                                       intituleVisite = :intituleVisite,
                                       numTelVisite = :numTelVisite,
                                       idExposition = :idExposition,
                                       idConferencier = :idConferencier,
                                       idEmploye = :idEmploye
                                   WHERE idEmploye = :idEmploye');

            $stmt->bindParam(':nomEmploye', $nomEmploye);
            $stmt->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt->bindParam(':numTelEmploye', $numTelEmploye);
            
            if ($stmt->execute()) {
                $pdo->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
		    return false;
        }
    }

    function modifierConferencier($pdo, $idConferencier, $nomConferencier, $prenomConferencier, $estEmploye, $specialites, $indisponibilites) {
        try {
            $pdo->beginTransaction();

            $executeOk = true;

            foreach ($specialites as $specialite) {
                if ($executeOk) {
                    $stmt = $pdo->prepare('UPDATE specialites 
                                    SET intitule = :intitule
                                    WHERE idSpecialite = :idSpecialite');

                    $stmt->bindParam(':intitule', $specialite['intitule']);
                    $stmt->bindParam(':idSpecialite', $specialite['id']);

                    if ($stmt->execute()) {
                        $executeOk = true;
                    } else {
                        $executeOk = false;
                    }
                }
            }

            if ($executeOk) {
                $pdo->commit();
            } else {
                $pdo->rollBack();
		        return false;
            }

            $executeOk = true;

            foreach ($indisponibilites as $indisponibilite) {
                if ($executeOk) {
                    $stmt = $pdo->prepare('UPDATE indisponibilites 
                                    SET dateDebutIndispo = :dateDebutIndispo,
                                        dateFinIndispo = :dateFinIndispo
                                    WHERE idIndisponibilite = :idIndisponibilite');

                    $stmt->bindParam(':dateDebutIndispo', $indisponibilite['debut']);
                    $stmt->bindParam(':dateFinIndispo', $indisponibilite['fin']);
                    $stmt->bindParam(':idIndisponibilite', $indisponibilite['id']);

                    if ($stmt->execute()) {
                        $executeOk = true;
                    } else {
                        $executeOk = false;
                    }
                }
            }

            if ($executeOk) {
                $pdo->commit();
            } else {
                $pdo->rollBack();
		        return false;
            }

            $stmt = $pdo->prepare('UPDATE conferencier 
                                   SET nomConferencier = :nomConferencier,
                                       prenomConferencier = :prenomConferencier,
                                       estEmploye = :estEmploye
                                   WHERE idConferencier = :idConferencier');

            $stmt->bindParam(':nomConferencier', $nomConferencier);
            $stmt->bindParam(':prenomConferencier', $prenomConferencier);
            $stmt->bindParam(':estEmploye', $estEmploye);
            $stmt->bindParam(':idConferencier', $idConferencier);
            
            if ($stmt->execute()) {
                $pdo->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
		    return false;
        }
    }



function modifierExposition($pdo, $idExposition, $intitule, $periodeDebut, $periodeFin,$nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp,$motsCleStr) {
    try {
        // Démarrer la transaction
        $pdo->beginTransaction();

        // Préparer la requête SQL
        $sql = "UPDATE exposition 
                SET intitule = :intitule, 
                    periodeDebut = :periodeDebut, 
                    periodeFin = :periodeFin, 
                    nombreOeuvres = :nombreOeuvres, 
                    resume = :resume, 
                    debutExpoTemp = :debutExpoTemp, 
                    finExpoTemp = :finExpoTemp 
                WHERE idExposition = :idExposition";

        // Préparer la requête avec PDO
        $stmt = $pdo->prepare($sql);

        // Associe les valeurs aux paramètres
        $stmt->bindParam(':intitule', $intitule, PDO::PARAM_STR);
        $stmt->bindParam(':periodeDebut', $periodeDebut, PDO::PARAM_INT);
        $stmt->bindParam(':periodeFin', $periodeFin, PDO::PARAM_INT);
        $stmt->bindParam(':nombreOeuvres', $nombreOeuvres, PDO::PARAM_INT);
        $stmt->bindParam(':resume', $resume, PDO::PARAM_STR);
        $stmt->bindParam(':debutExpoTemp', $debutExpoTemp, PDO::PARAM_STR);
        $stmt->bindParam(':finExpoTemp', $finExpoTemp, PDO::PARAM_STR);
        $stmt->bindParam(':idExposition', $idExposition, PDO::PARAM_STR);

        // Exécute la requête
        $stmt->execute();


        // Converti la liste des mots-clés en tableau
        $motsCleArray = array_map('trim', explode(',', $motsCleStr));

        // Supprime les mots-clés qui ne sont plus dans la liste
        $placeholders = implode(',', array_fill(0, count($motsCleArray), '?'));
        $deleteQuery = "
            DELETE FROM motsCle
            WHERE idExposition = ?
              AND motCle NOT IN ($placeholders)
        ";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute(array_merge([$idExposition], $motsCleArray));

        // Ajoute les nouveaux mots-clés absents
        foreach ($motsCleArray as $motCle) {
            $insertQuery = "
                INSERT INTO motsCle (motCle, idExposition)
                SELECT ?, ?
                WHERE NOT EXISTS (
                    SELECT 1 FROM motsCle WHERE motCle = ? AND idExposition = ?
                )
            ";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([$motCle, $idExposition, $motCle, $idExposition]);
        }

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        // En cas d'erreur, annule la transaction
        $pdo->rollBack();
        exit($e->getMessage(). $e->getLine(). $e->getFile(). $e->printStackTrace());
        return false;
    }
}



function verifierChaine($chaine) {
        if (strlen($chaine) < 8) {
            return false;
        }
    
        if (!preg_match('/[a-z]/', $chaine)) {
            return false;
        }
    
        if (!preg_match('/[A-Z]/', $chaine)) {
            return false;
        }
    
        if (!preg_match('/[0-9]/', $chaine)) {
            return false;
        }
    
        if (!preg_match('/[\W_]/', $chaine)) { 
            return false;
        }
    
        return true;
    }
?>