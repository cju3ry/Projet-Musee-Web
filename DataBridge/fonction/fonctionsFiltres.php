<?php
    function tabNomEmploye($pdo) {
        $tabNomEmploye = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT nom FROM employe ORDER BY Type Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
        
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNomEmploye[] = $ligne['nom'];
        }

        return $tabNomEmploye;
    }

    function tabPrenomEmploye($pdo) {
        $tabPrenomEmploye = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT prenom FROM employe ORDER BY Type Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabPrenomEmploye[] = $ligne['prenom'];
        }

        return $tabPrenomEmploye;
    }

    function rechercheEmploye($pdo, $nom, $prenom) {
        $requete = "SELECT nom, prenom FROM employe ";
        $parametre = [];

        if ($nom != "" || $prenom != "") {
			$requete .= "WHERE";
		}

        if ($nom != "") {
			$requeteAjout = " nom LIKE :nom";
			$parametre[':nom'] = $nom;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		}

		if ($prenom != "" && !$dejaAjoute) {
			$requeteAjout = " prenom LIKE :prenom";
			$parametre[':prenom'] = $prenom;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		} else if ($dejaAjoute && $prenom != "") {
			$requeteAjout = " AND prenom = :prenom";
			$parametre[':prenom'] = $prenom;
            $requete .= $requeteAjout;
		}

        $requete .= " ORDER BY nom ASC";
        
        $stmt = $pdo -> prepare($requete);

		try {
			$stmt->execute($parametre);
		} catch (PDOException $e) {
			return ['error' => $e->getMessage()];
		}

        $tabEmploye = [];
	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabEmploye[] = [
				'nom' => $row['nom'],
				'prenom' => $row['prenom']
			];
		}
	
		return $tabEmploye;
    }
?>