<?php
    function tabNomEmploye($pdo) {
        $tabNomEmploye = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT nomEmploye FROM employe ORDER BY nomEmploye Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
        
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNomEmploye[] = $ligne['nomEmploye'];
        }

        return $tabNomEmploye;
    }

    function tabPrenomEmploye($pdo) {
        $tabPrenomEmploye = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT prenomEmploye FROM employe ORDER BY prenomEmploye Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabPrenomEmploye[] = $ligne['prenomEmploye'];
        }

        return $tabPrenomEmploye;
    }

    function tabNumTel($pdo) {
        $tabNumTel = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT numTelEmploye FROM employe ORDER BY numTelEmploye Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNumTel[] = $ligne['numTelEmploye'];
        }

        return $tabNumTel;
    }

    function rechercheEmploye($pdo, $nom, $prenom, $numTelEmploye) {
        $requete = "SELECT employe.idEmploye, nomEmploye, prenomEmploye, numTelEmploye, login, pwd FROM employe JOIN login ON employe.idEmploye = login.idEmploye ";
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

        $requete .= " ORDER BY idEmploye ASC";
        
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
                'login' => $row['login'],
                'pwd' => $row['pwd']
			];
		}
	
		return $tabEmploye;
    }
    
    
function rechercheEmployePage($pdo, $nom, $prenom, $numTelEmploye, $debut, $nbrElement) {
        $requete = "SELECT employe.idEmploye, nomEmploye, prenomEmploye, numTelEmploye, login, pwd FROM employe JOIN login ON employe.idEmploye = login.idEmploye ";
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

        $requete .= " ORDER BY idEmploye ASC limit $debut,$nbrElement";
        
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
                'login' => $row['login'],
                'pwd' => $row['pwd']
			];
		}
	
		return $tabEmploye;
    }
    
    
function rechercheExposition($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp, $motCle)
{
    $requete = "SELECT exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp, 
                       GROUP_CONCAT(motsCle.motCle SEPARATOR ', ') AS motsCles
                FROM exposition
                JOIN motsCle ON exposition.idExposition = motsCle.idExposition";

    $parametre = [];
    $dejaAjoute = false;

    if ($intitule != "" || $periodeDebut != "" || $periodeFin != "" || $nombreOeuvres != "" || $resume != "" || $debutExpoTemp != "" || $finExpoTemp != "" || $motCle != "") {
        $requete .= " WHERE";
    }

    if ($intitule != "") {
        $requete .= " exposition.intitule LIKE :intitule";
        $parametre[':intitule'] = $intitule;
        $dejaAjoute = true;
    }

    if ($periodeDebut != "" && !$dejaAjoute) {
        $requete .= " exposition.periodeDebut LIKE :periodeDebut";
        $parametre[':periodeDebut'] = $periodeDebut;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $periodeDebut != "") {
        $requete .= " AND exposition.periodeDebut = :periodeDebut";
        $parametre[':periodeDebut'] = $periodeDebut;
    }

    if ($periodeFin != "" && !$dejaAjoute) {
        $requete .= " exposition.periodeFin LIKE :periodeFin";
        $parametre[':periodeFin'] = $periodeFin;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $periodeFin != "") {
        $requete .= " AND exposition.periodeFin = :periodeFin";
        $parametre[':periodeFin'] = $periodeFin;
    }

    if ($nombreOeuvres != "" && !$dejaAjoute) {
        $requete .= " exposition.nombreOeuvres LIKE :nombreOeuvres";
        $parametre[':nombreOeuvres'] = $nombreOeuvres;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $nombreOeuvres != "") {
        $requete .= " AND exposition.nombreOeuvres = :nombreOeuvres";
        $parametre[':nombreOeuvres'] = $nombreOeuvres;
    }

    if ($resume != "" && !$dejaAjoute) {
        $requete .= " exposition.resume LIKE :resume";
        $parametre[':resume'] = $resume;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $resume != "") {
        $requete .= " AND exposition.resume = :resume";
        $parametre[':resume'] = $resume;
    }

    if ($debutExpoTemp != "" && !$dejaAjoute) {
        $requete .= " exposition.debutExpoTemp LIKE :debutExpoTemp";
        $parametre[':debutExpoTemp'] = $debutExpoTemp;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $debutExpoTemp != "") {
        $requete .= " AND exposition.debutExpoTemp = :debutExpoTemp";
        $parametre[':debutExpoTemp'] = $debutExpoTemp;
    }

    if ($finExpoTemp != "" && !$dejaAjoute) {
        $requete .= " exposition.finExpoTemp LIKE :finExpoTemp";
        $parametre[':finExpoTemp'] = $finExpoTemp;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $finExpoTemp != "") {
        $requete .= " AND exposition.finExpoTemp = :finExpoTemp";
        $parametre[':finExpoTemp'] = $finExpoTemp;
    }

    if ($motCle != "" && !$dejaAjoute) {
        $requete .= " motsCle.motCle LIKE :motCle";
        $parametre[':motCle'] = $motCle;
        $dejaAjoute = true;
    } else if ($dejaAjoute && $motCle != "") {
        $requete .= " AND motsCle.motCle = :motCle";
        $parametre[':motCle'] = $motCle;
    }

    // Ajout de GROUP BY
    $requete .= " GROUP BY exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp";

    $stmt = $pdo->prepare($requete);

    try {
        $stmt->execute($parametre);
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }

    $tabExposition = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tabExposition[] = [
            'idExposition' => $row['idExposition'],
            'intitule' => $row['intitule'],
            'periodeDebut' => $row['periodeDebut'],
            'periodeFin' => $row['periodeFin'],
            'nombreOeuvres' => $row['nombreOeuvres'],
            'resume' => $row['resume'],
            'debutExpoTemp' => $row['debutExpoTemp'],
            'finExpoTemp' => $row['finExpoTemp'],
            'motsCles' => $row['motsCles']
        ];
    }

    return $tabExposition;
}

function rechercheExpositionParId($pdo, $idExposition)
{
    $requete = "SELECT exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin,
                       exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp,
                       GROUP_CONCAT(motsCle.motCle SEPARATOR ', ') AS motsCles
                FROM exposition
                LEFT JOIN motsCle ON exposition.idExposition = motsCle.idExposition
                WHERE exposition.idExposition = CAST(:idExposition AS CHAR)
                GROUP BY exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin,
                         exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp";

    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(':idExposition', $idExposition, PDO::PARAM_STR);

    try {
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Récupère directement la première ligne
    } catch (PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        return null;
    }
}


    
?>