<?php
    function modifierEmploye($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye) {
        try {
            $stmt = $pdo->prepare('UPDATE employe 
                                   SET nomEmploye = :nomEmploye,
                                   prenomEmploye = :prenomEmploye,
                                   numTelEmploye = :numTelEmploye
                                   WHERE idEmploye = :idEmploye');

            $stmt->bindParam(':nomEmploye', $nomEmploye);
            $stmt->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
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
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function modifierConferencier($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye) {
        try {
            $stmt = $pdo->prepare('UPDATE table 
                                   SET nomEmploye = :nomEmploye,
                                   prenomEmploye = :prenomEmploye,
                                   numTelEmploye = :numTelEmploye
                                   WHERE idEmploye = :idEmploye');

            $stmt->bindParam(':nomEmploye', $nomEmploye);
            $stmt->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function modifierExposition($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye) {
        try {
            $stmt = $pdo->prepare('UPDATE table 
                                   SET nomEmploye = :nomEmploye,
                                   prenomEmploye = :prenomEmploye,
                                   numTelEmploye = :numTelEmploye
                                   WHERE idEmploye = :idEmploye');

            $stmt->bindParam(':nomEmploye', $nomEmploye);
            $stmt->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
?>