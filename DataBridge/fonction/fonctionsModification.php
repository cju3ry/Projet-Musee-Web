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
    
    function modifierLogin($pdo, $idLogin, $login, $pwd, $idEmploye) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE login 
                                   SET login = :login,
                                   pwd = :pwd,
                                   idEmploye = :idEmploye
                                   WHERE idLogin = :idLogin');

            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':pwd', $pwd);
            $stmt->bindParam(':idEmploye', $idEmploye);
            
            if ($stmt->execute()) {
                $pdo->commit();
            }
            
            return true;
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

    function modifierConferencier($pdo, $idConferencier, $nomConferencier, $prenomConferencier, $estEmploye) {
        try {
            $pdo->beginTransaction();
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

    function modifierExposition($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE table 
                                   SET nomEmploye = :nomEmploye,
                                   prenomEmploye = :prenomEmploye,
                                   numTelEmploye = :numTelEmploye
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
    
    function modifierSpecialite($pdo, $idSpecialite, $specialite, $idConferencier) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE specialites 
                                   SET specialite = :specialite,
                                   idConferencier = :idConferencier
                                   WHERE idSpecialite = :idSpecialite');

            $stmt->bindParam(':specialite', $nomEmploye);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->bindParam(':idSpecialite', $numTelEmploye);
            
            if ($stmt->execute()) {
                $pdo->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
		    return false;
        }
    }
    
    function modifierIndispo($pdo, $idIndisponibilite, $dateDebutIndispo, $dateFinIndispo, $idConferencier) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('UPDATE indisponibilites 
                                   SET dateDebutIndispo = :dateDebutIndispo,
                                   dateFinIndispo = :dateFinIndispo,
                                   idConferencier = :idConferencier
                                   WHERE idIndisponibilite = :idIndisponibilite');

            $stmt->bindParam(':dateDebutIndispo', $dateDebutIndispo);
            $stmt->bindParam(':dateFinIndispo', $dateFinIndispo);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->bindParam(':idIndisponibilite', $idIndisponibilite);
            
            if ($stmt->execute()) {
                $pdo->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
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