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

    function rechercheEmploye($pdo, $nom, $prenom, $NumTelEmploye) {
        $requete = "SELECT idEmploye, nomEmploye, prenomEmploye, NumTelEmploye FROM employe ";
        $parametre = [];

        if ($nom != "" || $prenom != "" || $NumTelEmploye != "") {
			$requete .= "WHERE";
		}

        if ($nom != "") {
			$requeteAjout = " nomEmploye LIKE :nom";
			$parametre[':nom'] = $nom;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		}

		if ($prenom != "" && !$dejaAjoute) {
			$requeteAjout = " prenomEmploye LIKE :prenom";
			$parametre[':prenom'] = $prenom;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		} else if ($dejaAjoute && $prenom != "") {
			$requeteAjout = " AND prenomEmploye = :prenom";
			$parametre[':prenom'] = $prenom;
            $requete .= $requeteAjout;
		}

        if ($NumTelEmploye != "" && !$dejaAjoute) {
			$requeteAjout = " NumTelEmploye LIKE :numTel";
			$parametre[':numTel'] = $NumTelEmploye;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		} else if ($dejaAjoute && $NumTelEmploye != "") {
			$requeteAjout = " AND NumTelEmploye = :numTel";
			$parametre[':numTel'] = $NumTelEmploye;
            $requete .= $requeteAjout;
		}

        $requete .= " ORDER BY nomEmploye ASC";
        
        $stmt = $pdo -> prepare($requete);

		try {
			$stmt->execute($parametre);
		} catch (PDOException $e) {
			return ['error' => $e->getMessage()];
		}

        $tabEmploye = [];
	
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabEmploye[] = [
                'idEmploye' => $row['idEmploye'],
				'nom' => $row['nomEmploye'],
				'prenom' => $row['prenomEmploye'],
                'numTel' => $row['NumTelEmploye']
			];
		}
	
		return $tabEmploye;
    }
?>