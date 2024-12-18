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

    function rechercheEmploye($pdo, $nom, $prenom, $numTelEmploye) {
        $requete = "SELECT idEmploye, nomEmploye, prenomEmploye, numTelEmploye, login FROM employe JOIN login ON employe.idEmploye = login.idEmploye ";
        $parametre = [];
        $dejaAjoute = false;

        if ($nom != "" || $prenom != "" || $numTelEmploye != "") {
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

        if ($numTelEmploye != "" && !$dejaAjoute) {
			$requeteAjout = " numTelEmploye LIKE :numTel";
			$parametre[':numTel'] = $numTelEmploye;
            $requete .= $requeteAjout;
			$dejaAjoute = true;
		} else if ($dejaAjoute && $numTelEmploye != "") {
			$requeteAjout = " AND numTelEmploye = :numTel";
			$parametre[':numTel'] = $numTelEmploye;
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
                'numTel' => $row['numTelEmploye'],
                'login' => $row['login']
			];
		}
	
		return $tabEmploye;
    }
?>