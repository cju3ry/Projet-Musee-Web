<?php

// Employe + login
    function tabNomEmploye($pdo) {
        $tabNomEmploye = array();

        try {
            $stmt = $pdo->prepare('SELECT DISTINCT nomEmploye FROM employe ORDER BY nomEmploye');
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
            $stmt = $pdo->prepare('SELECT DISTINCT prenomEmploye FROM employe ORDER BY prenomEmploye');
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
            $stmt = $pdo->prepare('SELECT DISTINCT numTelEmploye FROM employe ORDER BY numTelEmploye');
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
        $requete = "SELECT employe.idEmploye, nomEmploye, prenomEmploye, numTelEmploye, login, pwd 
					FROM employe 
					JOIN login 
					ON employe.idEmploye = login.idEmploye;";
        $parametre = [];
        $dejaAjoute = false;

        if ($nom != "" || $prenom != "" || $numTelEmploye != "") {
			$requete .= " WHERE";
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
                'login' => $row['login'],
                'pwd' => $row['pwd']
			];
		}
	
		return $tabEmploye;
    }

// Conferencier + Indispobilités + Specialites
	function tabNomConferencier($pdo) {
		$tabNomConferencier = array();
		try {
			$stmt = $pdo->prepare('SELECT DISTINCT nomConferencier FROM conferencier ORDER BY nomConferencier');
			$stmt->execute();
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}

		while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabNomConferencier[] = $ligne['nomConferencier'];
		}

		return $tabNomConferencier;
	}

	function tabPrenomConferencier($pdo) {
		$tabPrenomConferencier = array();
		try {
			$stmt = $pdo->prepare('SELECT DISTINCT prenomConferencier FROM conferencier ORDER BY prenomConferencier');
			$stmt->execute();
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}

		while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabPrenomConferencier[] = $ligne['prenomConferencier'];
		}
		return $tabPrenomConferencier;
	}

	function tabEstEmploye($pdo) {
		$tabEstEmploye = array();
		try {
			$stmt = $pdo->prepare('SELECT DISTINCT estEmploye FROM conferencier ORDER BY estEmploye');
			$stmt->execute();
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}

		while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabEstEmploye[] = $ligne['estEmploye'];
		}

		return $tabEstEmploye;
	}

	function tabIndispo($pdo) {
		$tabIndispo = array();

		try {
			$stmt = $pdo->prepare('SELECT DISTINCT dateDebutIndispo, dateFinIndispo
								   FROM indisponibilites
								   JOIN conferencier
								   ON indisponibilites.idConferencier = conferencier.idConferencier
								   GROUP BY dateDebutIndispo, dateFinIndispo;');
			$stmt->execute();
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}

		while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabIndispo[] = $ligne['dateDebutIndispo'] . $ligne['dateDebutIndispo'];
		}

		return $tabIndispo;
	}

	function tabSepcialite($pdo) {
		$tabSpecialite = array();

		try {
			$stmt = $pdo->prepare('SELECT DISTINCT intitule, specialites.idConferencier
								   FROM specialites 
								   JOIN conferencier
								   ON specialites.idConferencier = conferencier.idConferencier
								   GROUP BY intitule, specialites.idConferencier;');
			$stmt->execute();
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}

		while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$tabSpecialite[] = $ligne['intitule'];
		}

		return $tabSpecialite;
	}

function rechercheConferencier($pdo, $nom, $prenom, $estEmploye, $dateDebutIndispo, $dateFinIndispo, $specialite) {
	$requete = "SELECT conferencier.idConferencier, conferencier.nomConferencier, conferencier.prenomConferencier, conferencier.estEmploye, indisponibilites.dateFinIndispo, indisponibilites.dateDebutIndispo, specialites.intitule
    			FROM conferencier 
    			JOIN  indisponibilites 
    			ON conferencier.idConferencier = indisponibilites.idConferencier
    			JOIN specialites
				ON conferencier.idConferencier = specialites.idConferencier;";
	$parametre = [];
	$dejaAjoute = false;

	if ($nom != "" || $prenom != "" || $estEmploye != "" || $dateDebutIndispo != "" || $dateFinIndispo != "" || $specialite != "") {
		$requete .= " WHERE";
	}

	if ($nom != "") {
		$requete .= " conferencier.nomConferencier LIKE :nom";
		$parametre[':nom'] = $nom;
		$dejaAjoute = true;
	}

	if ($prenom != "" && !$dejaAjoute) {
		$requete .= " conferencier.prenomConferencier LIKE :prenom";
		$parametre[':prenom'] = $prenom;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $prenom != "") {
		$requete .= " AND conferencier.prenomConferencier = :prenom";
		$parametre[':prenom'] = $prenom;
	}

	if ($estEmploye != "" && !$dejaAjoute) {
		$requete .= " conferencier.estEmploye LIKE :estEmploye";
		$parametre[':estEmploye'] = $estEmploye;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $estEmploye != "") {
		$requete .= " AND conferencier.estEmploye = :estEmploye";
		$parametre['estEmploye'] = $estEmploye;
	}

	if ($dateDebutIndispo != "" && !$dejaAjoute) {
		$requete .= " indisponibilites.dateDebutIndispo LIKE :dateDebutIndispo";
		$parametre[':dateDebutIndispo'] = $dateDebutIndispo;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $dateDebutIndispo != "") {
		$requete .= " AND indisponibilites.dateDebutIndispo = :dateDebutIndispo";
		$parametre[':dateDebutIndispo'] = $dateDebutIndispo;
	}

	if ($dateFinIndispo != "" && !$dejaAjoute) {
		$requete .= " indisponibilites.dateFinIndispo LIKE :dateFinIndispo";
		$parametre[':dateFinIndispo'] = $dateFinIndispo;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $dateFinIndispo != "") {
		$requete .= " AND indisponibilites.dateFinIndispo = :dateFinIndispo";
		$parametre[':dateFinIndispo'] = $dateFinIndispo;
	}

	if ($specialite != "" && !$dejaAjoute) {
		$requete .= " specialites.intitule LIKE :specialite";
		$parametre[':specialite'] = $specialite;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $specialite != "") {
		$requete .= " AND specialites.intitule = :specialite";
		$parametre[':specialite'] = $specialite;
	}

	$requete .= " ORDER BY conferencier.nomConferencier ASC";
	$stmt = $pdo -> prepare($requete);

	try {
		$stmt->execute($parametre);
	} catch (PDOException $e) {
		return ['error' => $e->getMessage()];
	}

	$tabConferencier = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabConferencier[] = [
			'idConferencier' => $row['idConferencier'],
			'nom' => $row['nomConferencier'],
			'prenom' => $row['prenomConferencier'],
			'estEmploye' => $row['estEmploye'],
			'dateDebutIndispo' => $row['dateDebutIndispo'],
			'dateFinIndispo' => $row['dateFinIndispo'],
			'intitule' => $row['intitule']
		];
	}

	return $tabConferencier;
}

// Exposition + motsCles
function tabIntituleExposition($pdo) {
	$tabIntituleExposition = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT intitule FROM exposition ORDER BY intitule');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabIntituleExposition[] = $ligne['intitule'];
	}

	return $tabIntituleExposition;
}

function tabPeriodeDebut($pdo) {
	$tabPeriodeDebut = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT periodeDebut FROM exposition ORDER BY periodeDebut');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabPeriodeDebut[] = $ligne['periodeDebut'];
	}

	return $tabPeriodeDebut;
}

function tabPeriodeFin($pdo) {
	$tabPeriodeFin = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT periodeFin FROM exposition ORDER BY periodeFin');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabPeriodeFin[] = $ligne['periodeFin'];
	}

	return $tabPeriodeFin;
}

function tabNombresOeuvres($pdo) {
	$tabNombresOeuvres = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT nombreOeuvres FROM exposition ORDER BY nombreOeuvres');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabNombresOeuvres[] = $ligne['nombreOeuvres'];
	}

	return $tabNombresOeuvres;
}

function tabResume($pdo) {
	$tabResume = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT resume FROM exposition ORDER BY resume');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabResume[] = $ligne['resume'];
	}

	return $tabResume;
}

function tabDebutExpoTemp($pdo) {
	$debutExpoTemp = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT debutExpoTemp FROM exposition ORDER BY debutExpoTemp');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$debutExpoTemp[] = $ligne['debutExpoTemp'];
	}

	return $debutExpoTemp;
}

function tabFinExpoTemp($pdo) {
	$finExpoTemp = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT finExpoTemp FROM exposition ORDER BY finExpoTemp');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$finExpoTemp[] = $ligne['finExpoTemp'];
	}

	return $finExpoTemp;
}

function tabMotsCles($pdo) {
	$tabMotsCles = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT motCle, motsCle.idExposition
							   FROM motsCle
							   JOIN exposition
							   ON motsCle.idExposition = exposition.idExposition
                       		   GROUP BY motCle, motsCle.idExposition;');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch()) {
		$tabMotsCles[] = $ligne['motCle'];
	}
	return $tabMotsCles;
}

function rechercheExposition($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp, $motCle)
{
	$requete = "SELECT exposition.idExposition, exposition.intitule, exposition.periodeDebut, exposition.periodeFin, exposition.nombreOeuvres, exposition.resume, exposition.debutExpoTemp, exposition.finExpoTemp, motsCle.motCle
				FROM exposition
				JOIN motsCle
				ON exposition.idExposition = motsCle.idExposition;";
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

	$requete .= " ORDER BY exposition.intitule ASC";

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
			'motCle' => $row['motCle']
		];
	}

	return $tabExposition;
}

//Visite

function tabDateVisite($pdo) {
	$tabDateVisite = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT dateVisite, visite.idExposition, visite.idConferencier, visite.idEmploye
							   FROM visite 
							   JOIN exposition
							   ON visite.idExposition = exposition.idExposition
							   JOIN conferencier
							   ON visite.idConferencier = conferencier.idConferencier
							   JOIN employe
							   ON visite.idEmploye = employe.idEmploye
							   GROUP BY dateVisite, visite.idExposition, visite.idConferencier, visite.idEmploye;');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabDateVisite[] = $ligne['dateVisite'];
	}

	return $tabDateVisite;
}

function tabHeureDebutVisite($pdo) {
	$tabHeureDebutVisite = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT heureDebutVisite, visite.idExposition, visite.idConferencier, visite.idEmploye
							   FROM visite 
							   JOIN exposition
							   ON visite.idExposition = exposition.idExposition
							   JOIN conferencier
							   ON visite.idConferencier = conferencier.idConferencier
							   JOIN employe
							   ON visite.idEmploye = employe.idEmploye
							   GROUP BY heureDebutVisite, visite.idExposition, visite.idConferencier, visite.idEmploye;');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabHeureDebutVisite[] = $ligne['heureDebutVisite'];
	}

	return $tabHeureDebutVisite;
}

function tabIntituleVisite($pdo) {
	$tabIntituleVisite = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT intituleVisite, visite.idExposition, visite.idConferencier, visite.idEmploye
							   FROM visite 
							   JOIN exposition
							   ON visite.idExposition = exposition.idExposition
							   JOIN conferencier
							   ON visite.idConferencier = conferencier.idConferencier
							   JOIN employe
							   ON visite.idEmploye = employe.idEmploye
							   GROUP BY intituleVisite, visite.idExposition, visite.idConferencier, visite.idEmploye;');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabIntituleVisite[] = $ligne['intituleVisite'];
	}

	return $tabIntituleVisite;
}

function numTelVisite($pdo) {
	$tabNumTelVisite = array();

	try {
		$stmt = $pdo->prepare('SELECT DISTINCT numTelVisite, visite.idExposition, visite.idConferencier, visite.idEmploye
							   FROM visite
							   JOIN exposition
							   ON visite.idExposition = exposition.idExposition
							   JOIN conferencier
							   ON visite.idConferencier = conferencier.idConferencier
							   JOIN employe
							   ON visite.idEmploye = employe.idEmploye
							   GROUP BY numTelVisite, visite.idExposition, visite.idConferencier, visite.idEmploye');
		$stmt->execute();
	} catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}

	while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabNumTelVisite[] = $ligne['numTelVisite'];
	}

	return $tabNumTelVisite;
}

function rechercheVisite($pdo, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite) {
	$requete = "SELECT visite.idVisite, visite.dateVisite, visite.heureDebutVisite, visite.intituleVisite, visite.numTelVisite, visite.idExposition, visite.idConferencier, visite.idEmploye
				FROM visite;";
	$parametre = [];
	$dejaAjoute = false;

	if ($dateVisite != "" || $heureDebutVisite != "" || $intituleVisite != "" || $numTelVisite != "") {
		$requete .= " WHERE";
	}

	if ($dateVisite != "") {
		$requete .= " visite.dateVisite LIKE :dateVisite";
		$parametre[':dateVisite'] = $dateVisite;
		$dejaAjoute = true;
	}

	if ($heureDebutVisite != "" && !$dejaAjoute) {
		$requete .= " visite.heureDebutVisite LIKE :heureDebutVisite";
		$parametre[':heureDebutVisite'] = $heureDebutVisite;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $heureDebutVisite != "") {
		$requete .= " AND visite.heureDebutVisite = :heureDebutVisite";
		$parametre[':heureDebutVisite'] = $heureDebutVisite;
	}

	if ($intituleVisite != "" && !$dejaAjoute) {
		$requete .= " visite.intituleVisite LIKE :intituleVisite";
		$parametre[':intituleVisite'] = $intituleVisite;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $intituleVisite != "") {
		$requete .= " AND visite.intituleVisite = :intituleVisite";
		$parametre[':intituleVisite'] = $intituleVisite;
	}

	if ($numTelVisite != "" && !$dejaAjoute) {
		$requete .= " visite.numTelVisite LIKE :numTelVisite";
		$parametre[':numTelVisite'] = $numTelVisite;
		$dejaAjoute = true;
	} else if ($dejaAjoute && $numTelVisite != "") {
		$requete .= " AND visite.numTelVisite = :numTelVisite";
		$parametre[':numTelVisite'] = $numTelVisite;
	}

	$requete .= " ORDER BY visite.dateVisite ASC";
	$stmt = $pdo->prepare($requete);

	try {
		$stmt->execute($parametre);
	} catch (PDOException $e) {
		return ['error' => $e->getMessage()];
	}

	$tabVisite = [];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$tabVisite[] = [
			'idVisite' => $row['idVisite'],
			'dateVisite' => $row['dateVisite'],
			'heureDebutVisite' => $row['heureDebutVisite'],
			'intituleVisite' => $row['intituleVisite'],
			'numTelVisite' => $row['numTelVisite'],
			'idExposition' => $row['idExposition'],
			'idConferencier' => $row['idConferencier'],
			'idEmploye' => $row['idEmploye']
		];
	}

	return $tabVisite;
}
?>