<?php
    // récupération des noms des employés
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
    
    // récupération des prénoms des employés
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
    
    // récupération des numéro de téléphone des employés
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
    
    // recherche des employés dans la base de donnée
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
    
    // recherche des employés avec leurs identifiants
    function rechercheEmployeParId($pdo, $idEmploye) {
        $sql = "SELECT 
                    employe.idEmploye,
                    employe.nomEmploye,
                    employe.prenomEmploye,
                    employe.NumTelEmploye,
                    login.login
                FROM employe
                LEFT JOIN login ON employe.idEmploye = login.idEmploye
                WHERE employe.idEmploye = :idEmploye";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idEmploye', $idEmploye, PDO::PARAM_STR);
        $stmt->execute();
        $employe = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $employe;
    }
    
    // affichage des employés par page
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
    
    // recherche des expositions
    function rechercheExposition($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp, $motCle) {
        $requete = "SELECT exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp, 
                           GROUP_CONCAT(motsCle.motCle SEPARATOR ', ') AS motsCles
                    FROM exposition
                    JOIN motsCle ON exposition.idExposition = motsCle.idExposition";
    
        $parametre = [];
        $dejaAjoute = false;
    
        if ($intitule != "" || $periodeDebut != "" || $periodeFin != "" || $nombreOeuvres != "" || $resume != "" || $debutExpoTemp != "" || $finExpoTemp != "" || $motCle != "") {
            $requete .= " WHERE";
        }
        
        // construction de la requête en fonction des paramètres
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
    
    // recherche des expositions par page
    function rechercheExpositionPage($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp, $motCle, $debut, $nbrElement) {
        $requete = "SELECT exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp, 
                           GROUP_CONCAT(motsCle.motCle SEPARATOR ', ') AS motsCles
                    FROM exposition
                    JOIN motsCle ON exposition.idExposition = motsCle.idExposition";
    
        $parametre = [];
        $dejaAjoute = false;
        
        // construction de la requête en fonction des paramètres
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
        $requete .= " GROUP BY exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp limit $debut, $nbrElement";
    
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
    
    // affichage des expositions par leurs id
    function rechercheExpositionParId($pdo, $idExposition) {
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
    
    // liste de filtres pour la période de début des expositions
    function tabPeriodeDebut($pdo) {
        $tabPeriodeDebut = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT exposition.periodeDebut FROM exposition ORDER BY periodeDebut Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabPeriodeDebut[] = $ligne['periodeDebut'];
        }
    
        return $tabPeriodeDebut;
    }
    
    // liste de filtres pour la période de fin des expositions
    function tabPeriodeFin($pdo) {
        $tabNomEmploye = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT exposition.periodeFin FROM exposition ORDER BY periodeFin Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNomEmploye[] = $ligne['periodeFin'];
        }
    
        return $tabNomEmploye;
    }
    
    // liste de filtres du nombre d'oeuvres des expositions
    function tabNombreOeuvres($pdo) {
        $tabNombreOeuvres = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT exposition.nombreOeuvres FROM exposition ORDER BY nombreOeuvres Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNombreOeuvres[] = $ligne['nombreOeuvres'];
        }
    
        return $tabNombreOeuvres;
    }
    
    // liste de filtres des mots clés des expositions
    function tabMotsCles($pdo) {
        $tabMotCle = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT motsCle.motCle FROM motsCle ORDER BY motsCle.motCle Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabMotCle[] = $ligne['motCle'];
        }
    
        return $tabMotCle;
    }
    
    // liste de filtres pour la date de début des expositions temporaires
    function tabDebutExposition($pdo) {
        $tabDebutExpoTemp = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT exposition.debutExpoTemp FROM exposition ORDER BY exposition.debutExpoTemp Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabDebutExpoTemp[] = $ligne['debutExpoTemp'];
        }
    
        return $tabDebutExpoTemp;
    }
    
    // liste de filtres pour la date de fin des expositions temporaires
    function tabFintExposition($pdo) {
        $tabFinExpoTemp = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT exposition.finExpoTemp FROM exposition ORDER BY exposition.finExpoTemp Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabFinExpoTemp[] = $ligne['finExpoTemp'];
        }
    
        return $tabFinExpoTemp;
    }
    
    // recherche des conférenciers
    function rechercheConferencier($pdo, $nomConferencier, $prenomConferencier, $estEmploye, $telephone, $specialite, $indispoDebut, $indispoFin) {
        // Début de la requête
        $requete = "
            SELECT 
                conferencier.idConferencier,
                conferencier.nomConferencier,
                conferencier.prenomConferencier,
                conferencier.estEmploye,
                conferencier.telephone,
                GROUP_CONCAT(DISTINCT specialites.intitule SEPARATOR ', ') AS specialites,
                GROUP_CONCAT(
                    DISTINCT CONCAT(
                        'Du ', DATE_FORMAT(indisponibilites.dateDebutIndispo, '%Y-%m-%d'), 
                        ' au ', DATE_FORMAT(indisponibilites.dateFinIndispo, '%Y-%m-%d')
                    ) SEPARATOR '; '
                ) AS indisponibilites
            FROM 
                conferencier
            LEFT JOIN 
                specialites ON conferencier.idConferencier = specialites.idConferencier
            LEFT JOIN 
                indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
        ";
    
        $parametre = [];
        $conditions = [];
    
        // Construction dynamique des conditions
        if ($nomConferencier != "") {
            $conditions[] = "conferencier.nomConferencier LIKE :nomConferencier";
            $parametre[':nomConferencier'] = "%$nomConferencier%";
        }
    
        if ($prenomConferencier != "") {
            $conditions[] = "conferencier.prenomConferencier LIKE :prenomConferencier";
            $parametre[':prenomConferencier'] = "%$prenomConferencier%";
        }
    
        if ($estEmploye != "") {
            $conditions[] = "conferencier.estEmploye = :estEmploye";
            $parametre[':estEmploye'] = $estEmploye;
        }
        
        if ($telephone != "") {
            $conditions[] = "conferencier.telephone = :telephone";
            $parametre[':telephone'] = "$telephone";
        }
    
        if ($specialite != "") {
            $conditions[] = "specialites.intitule LIKE :specialite";
            $parametre[':specialite'] = "%$specialite%";
        }
    
        if ($indispoDebut != "" && $indispoFin != "") {
            $conditions[] = "(indisponibilites.dateDebutIndispo <= :indispoFin AND indisponibilites.dateFinIndispo >= :indispoDebut)";
            $parametre[':indispoDebut'] = $indispoDebut;
            $parametre[':indispoFin'] = $indispoFin;
        }
    
        // Ajout des conditions à la requête
        if (!empty($conditions)) {
            $requete .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Ajout de GROUP BY pour éviter les doublons
        $requete .= " 
            GROUP BY 
                conferencier.idConferencier, 
                conferencier.nomConferencier, 
                conferencier.prenomConferencier, 
                conferencier.estEmploye,
                conferencier.telephone
        ";
    
        try {
            // Préparation et exécution de la requête
            $stmt = $pdo->prepare($requete);
            $stmt->execute($parametre);
    
            // Récupération des résultats
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $resultats;
    
        } catch (PDOException $e) {
            // En cas d'erreur
            return ['error' => $e->getMessage()];
        }
    }
    
    // liste de filtres des noms de conférenciers
    function tabNomConferencier($pdo) {
        $tabNomConfencier = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT nomConferencier FROM conferencier ORDER BY nomConferencier Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabNomConfencier[] = $ligne['nomConferencier'];
        }
    
        return $tabNomConfencier;
    }
    
    // liste de filtres des prenoms de conférenciers
    function tabPrenomConferencier($pdo) {
        $tabPreomConfencier = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT prenomConferencier FROM conferencier ORDER BY prenomConferencier Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabPreomConfencier[] = $ligne['prenomConferencier'];
        }
    
        return $tabPreomConfencier;
    }
    
    // liste de filtres des téléphones de conférenciers
    function tabTelephone($pdo) {
        $tabTelephone = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT telephone FROM conferencier ORDER BY telephone Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabTelephone[] = $ligne['telephone'];
        }
    
        return $tabTelephone;
    }
    
    // liste de filtres des spécialités de conférenciers
    function tabSpecialitesConferencier($pdo) {
        $tabSpecialites = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT specialites.intitule FROM specialites ORDER BY specialites.intitule Asc');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tabSpecialites[] = $ligne['intitule'];
        }
    
        return $tabSpecialites;
    }
    
    // liste de filtres des indisponibilités de conférenciers (date début)
    function tabDebutIndispoConferencier($pdo) {
        $dateDebutIndispo = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT dateDebutIndispo FROM indisponibilites WHERE dateDebutIndispo IS NOT NULL ORDER BY dateDebutIndispo ASC ;');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dateDebutIndispo[] = $ligne['dateDebutIndispo'];
        }
    
        return $dateDebutIndispo;
    }
    
    // liste de filtres des indisponibilités de conférenciers (date fin)
    function tabFinIndispoConferencier($pdo) {
        $dateFinIndispo = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT dateFinIndispo FROM indisponibilites WHERE dateFinIndispo IS NOT NULL ORDER BY dateFinIndispo ASC ;');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dateFinIndispo[] = $ligne['dateFinIndispo'];
        }
    
        return $dateFinIndispo;
    }
    
    // liste de filtres des conférenciers si il est employé
    function tabEstEmployeConferencier($pdo) {
        $estEmploye = array();
    
        try {
            $stmt = $pdo->prepare('SELECT DISTINCT estEmploye FROM conferencier ;');
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    
        while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estEmploye[] = $ligne['estEmploye'];
        }
    
        return $estEmploye;
    }
    
    // recherche des conférenciers
    function rechercheConferencierPage($pdo, $nomConferencier, $prenomConferencier, $estEmploye, $specialite, $telephone, $indispoDebut, $indispoFin, $debut, $nbrElement)
    {
        // Début de la requête
        $requete = "
            SELECT 
                conferencier.idConferencier,
                conferencier.nomConferencier,
                conferencier.prenomConferencier,
                conferencier.estEmploye,
                conferencier.telephone,
                GROUP_CONCAT(DISTINCT specialites.intitule SEPARATOR ', ') AS specialites,
                GROUP_CONCAT(
                    DISTINCT CONCAT(
                        'Du ', DATE_FORMAT(indisponibilites.dateDebutIndispo, '%Y-%m-%d'), 
                        ' au ', DATE_FORMAT(indisponibilites.dateFinIndispo, '%Y-%m-%d')
                    ) SEPARATOR '; '
                ) AS indisponibilites
            FROM 
                conferencier
            LEFT JOIN 
                specialites ON conferencier.idConferencier = specialites.idConferencier
            LEFT JOIN 
                indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
        ";
    
        $parametre = [];
        $conditions = [];
    
        // Construction dynamique des conditions
        if ($nomConferencier != "") {
            $conditions[] = "conferencier.nomConferencier LIKE :nomConferencier";
            $parametre[':nomConferencier'] = "%$nomConferencier%";
        }
    
        if ($prenomConferencier != "") {
            $conditions[] = "conferencier.prenomConferencier LIKE :prenomConferencier";
            $parametre[':prenomConferencier'] = "%$prenomConferencier%";
        }
    
        if ($estEmploye != "") {
            $conditions[] = "conferencier.estEmploye = :estEmploye";
            $parametre[':estEmploye'] = $estEmploye;
        }
        
        if ($telephone != "") {
            $conditions[] = "conferencier.telephone LIKE :telephone";
            $parametre[':telephone'] = "%$telephone%";
        }
    
        if ($specialite != "") {
            $conditions[] = "specialites.intitule LIKE :specialite";
            $parametre[':specialite'] = "%$specialite%";
        }
    
        if ($indispoDebut != "" && $indispoFin != "") {
            $conditions[] = "(indisponibilites.dateDebutIndispo <= :indispoFin AND indisponibilites.dateFinIndispo >= :indispoDebut)";
            $parametre[':indispoDebut'] = $indispoDebut;
            $parametre[':indispoFin'] = $indispoFin;
        }
    
        // Ajout des conditions à la requête
        if (!empty($conditions)) {
            $requete .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Ajout de GROUP BY pour éviter les doublons
        $requete .= " 
            GROUP BY 
                conferencier.idConferencier, 
                conferencier.nomConferencier, 
                conferencier.prenomConferencier, 
                conferencier.estEmploye,
                conferencier.telephone
            limit $debut, $nbrElement
        ";
    
        try {
            // Préparation et exécution de la requête
            $stmt = $pdo->prepare($requete);
            $stmt->execute($parametre);
    
            // Récupération des résultats
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $resultats;
    
        } catch (PDOException $e) {
            // En cas d'erreur
            return ['error' => $e->getMessage()];
        }
    }
    
    // recherche des conférenciers par leurs id
    function rechercheConferencierParId($pdo, $idConferencier) {
        $requete = "SELECT conferencier.idConferencier, conferencier.nomConferencier, conferencier.prenomConferencier, 
                           conferencier.estEmploye, conferencier.telephone,
                           GROUP_CONCAT(DISTINCT specialites.intitule SEPARATOR ', ') AS specialites,
                           GROUP_CONCAT(DISTINCT CONCAT(indisponibilites.dateDebutIndispo, ' , ', indisponibilites.dateFinIndispo) SEPARATOR ', ') AS indisponibilites
                    FROM conferencier
                    LEFT JOIN specialites ON conferencier.idConferencier = specialites.idConferencier
                    LEFT JOIN indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
                    WHERE conferencier.idConferencier = :idConferencier
                    GROUP BY conferencier.idConferencier, conferencier.nomConferencier, conferencier.prenomConferencier, conferencier.estEmploye";
    
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':idConferencier', $idConferencier, PDO::PARAM_STR);
    
        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Récupère directement la première ligne
        } catch (PDOException $e) {
            return null;
        }
    }
    
    // recherche des visites
    function rechercheVisite($pdo, $idVisite, $dateVisite, $heureDebut , $intitule , $idExposition , $idConferencier , $idEmploye, $telephone) {
        // Début de la requête
        $requete = "
            SELECT 
                visite.idVisite,
                visite.dateVisite,
                visite.heureDebutVisite,
                visite.intituleVisite,
                visite.numTelVisite,
                visite.idExposition,
                visite.IdEmploye,
                exposition.intitule,
                conferencier.nomConferencier,
                conferencier.prenomConferencier,
                conferencier.idConferencier,
                employe.nomEmploye,
                employe.prenomEmploye
            FROM 
                visite
            LEFT JOIN 
                exposition ON visite.idExposition = exposition.idExposition
            LEFT JOIN 
                conferencier ON visite.idConferencier = conferencier.idConferencier
            LEFT JOIN 
                employe ON visite.idEmploye = employe.idEmploye
        ";
    
        // Préparation des paramètres et des conditions
        $parametre = [];
        $conditions = [];
    
        if ($idVisite != "") {
            $conditions[] = "visite.idVisite = :idVisite";
            $parametre[':idVisite'] = $idVisite;
        }
    
        if ($telephone != "") {
            $conditions[] = "visite.numTelVisite = :numTelVisite";
            $parametre[':numTelVisite'] = $telephone;
        }
    
        if ($dateVisite != "") {
            $conditions[] = "DATE(visite.dateVisite) = :dateVisite";
            $parametre[':dateVisite'] = $dateVisite;
        }
    
        if ($heureDebut != "") {
            $conditions[] = "visite.heureDebutVisite = :heureDebut";
            $parametre[':heureDebut'] = $heureDebut;
        }
    
        if ($intitule != "") {
            $conditions[] = "visite.intituleVisite LIKE :intitule";
            $parametre[':intitule'] = "%$intitule%";
        }
    
        if ($idExposition != "" ) {
            $conditions[] = "visite.idExposition = :idExposition";
            $parametre[':idExposition'] = $idExposition;
        }
    
        if ($idConferencier != "") {
            $conditions[] = "visite.idConferencier = :idConferencier";
            $parametre[':idConferencier'] = $idConferencier;
        }
    
        if ($idEmploye != "") {
            $conditions[] = "visite.idEmploye = :idEmploye";
            $parametre[':idEmploye'] = $idEmploye;
        }
    
        // Ajout des conditions si nécessaire
        if (!empty($conditions)) {
            $requete .= " WHERE " . implode(" AND ", $conditions);
        }
    
        try {
            // Préparation et exécution de la requête
            $stmt = $pdo->prepare($requete);
            $stmt->execute($parametre);
    
            // Récupération des résultats
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $resultats;
    
        } catch (PDOException $e) {
            // Gestion des erreurs
            return ['error' => $e->getMessage()];
        }
    }
    
    // recherche des conférenciers par pages
    function rechercheVisitePage($pdo, $idVisite, $dateVisite, $heureDebut, $intitule, $idExposition, $idConferencier, $idEmploye, $telephone, $debut, $nbrElement) {
        // Début de la requête
        $requete = "
            SELECT 
                visite.idVisite,
                visite.dateVisite,
                visite.heureDebutVisite,
                visite.intituleVisite,
                visite.numTelVisite,
                visite.idExposition,
                visite.idEmploye,
                exposition.intitule,
                conferencier.nomConferencier,
                conferencier.prenomConferencier,
                conferencier.idConferencier,
                employe.nomEmploye,
                employe.prenomEmploye
            FROM 
                visite
            LEFT JOIN 
                exposition ON visite.idExposition = exposition.idExposition
            LEFT JOIN 
                conferencier ON visite.idConferencier = conferencier.idConferencier
            LEFT JOIN 
                employe ON visite.idEmploye = employe.idEmploye
        ";
    
        // Préparation des paramètres et des conditions
        $parametre = [];
        $conditions = [];
    
        if ($idVisite != "") {
            $conditions[] = "visite.idVisite = :idVisite";
            $parametre[':idVisite'] = $idVisite;
        }
    
        if ($telephone != "") {
            $conditions[] = "visite.numTelVisite = :numTelVisite";
            $parametre[':numTelVisite'] = $telephone;
        }
    
        if ($dateVisite != "") {
            $conditions[] = "DATE(visite.dateVisite) = :dateVisite";
            $parametre[':dateVisite'] = $dateVisite;
        }
    
        if ($heureDebut != "") {
            $conditions[] = "visite.heureDebutVisite = :heureDebut";
            $parametre[':heureDebut'] = $heureDebut;
        }
    
        if ($intitule != "") {
            $conditions[] = "visite.intituleVisite LIKE :intitule";
            $parametre[':intitule'] = "%$intitule%";
        }
    
        if ($idExposition != "") {
            $conditions[] = "visite.idExposition = :idExposition";
            $parametre[':idExposition'] = $idExposition;
        }
    
        if ($idConferencier != "") {
            $conditions[] = "visite.idConferencier = :idConferencier";
            $parametre[':idConferencier'] = $idConferencier;
        }
    
        if ($idEmploye != "") {
            $conditions[] = "visite.idEmploye = :idEmploye";
            $parametre[':idEmploye'] = $idEmploye;
        }
    
        // Ajout des conditions si nécessaire
        if (!empty($conditions)) {
            $requete .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Ajout de la clause LIMIT
        $requete .= " LIMIT :debut, :nbrElement";
        $parametre[':debut'] = (int) $debut;
        $parametre[':nbrElement'] = (int) $nbrElement;
    
        try {
            // Préparation et exécution de la requête
            $stmt = $pdo->prepare($requete);
            $stmt->execute($parametre);
    
            // Récupération des résultats
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $resultats;
    
        } catch (PDOException $e) {
            // Gestion des erreurs
            return ['error' => $e->getMessage()];
        }
    }
    
    // récupération des id des expositions
    function getIdExposition($pdo) {
        try {
            // Requête pour récupérer les id des expositions
            $sql = "SELECT idExposition, intitule FROM exposition ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            // Récupére les résultats sous forme de tableau
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion des erreurs
        return ['error' => $e->getMessage()];
        }
    }
    
    // récupération des id des conférenicers
    function getIdConferencier($pdo) {
        try {
        // Requête pour récupérer les id des conférenciers
        $sql = "SELECT idConferencier, nomConferencier,prenomConferencier FROM conferencier ";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        // Récupére les résultats sous forme de tableau
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion des erreurs
            return ['error' => $e->getMessage()];
        }
    }
    
    // récupération des id des employés
    function getIdEmployeAjout($pdo) {
        try {
            // Requête pour récupérer les id des employés
            $sql = "SELECT idEmploye, nomEmploye,prenomEmploye FROM employe ";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            // Récupére les résultats sous forme de tableau
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gestion des erreurs
            return ['error' => $e->getMessage()];
        }
    }
    
    // recherche des visites par leurs id
    function recherheVisiteParId($pdo, $idVisite) {
        // Requête pour récupérer toutes les informations de la visite par son ID
        $sql = "SELECT 
                visite.idVisite,
                visite.dateVisite,
                visite.heureDebutVisite,
                visite.intituleVisite,
                visite.numTelVisite,
                visite.idExposition,
                visite.IdConferencier,
                visite.IdEmploye
               FROM visite WHERE idVisite = :idVisite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idVisite', $idVisite, PDO::PARAM_STR);
        $stmt->execute();
        // Récupérer la visite en tant que tableau
        $visite = $stmt->fetch(PDO::FETCH_ASSOC);
        return $visite;
    }
    
    // récupération les valeurs d'une colonne en fonction de la table
    function getDistinctValues($pdo, $column, $table) {
        $values = [];
        try {
            $stmt = $pdo->prepare("SELECT DISTINCT $column FROM $table ORDER BY $column ASC");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $values[] = $row[$column];
            }
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
        return $values;
    }
?>