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
			$requeteAjout .= " nom LIKE :nom";
			$parametre[':nom'] = "%" . $nom . "%";
			$requeteAjoutOk = true;
			$dejaAjoute = true;
		}

		if ($type != "" && !$dejaAjoute) {
			$requeteAjout .= " Type = :type";
			$parametre[':type'] = $type;
			$requeteAjoutOk = true;
			$dejaAjoute = true;
		} else if ($dejaAjoute && $type != "" && $type != "TOUS") {
			$requeteAjout .= " AND Type = :type";
			$parametre[':type'] = $type;
			$requeteAjoutOk = true;
		}
    }
?>