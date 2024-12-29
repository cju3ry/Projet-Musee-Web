<?php
    function insertConferencier($pdo, $nom, $prenom, $specialite, $estEmploye) {
        try {
            $requete = "INSERT INTO conferencier (idConferencier, nomConferencier, prenomConferencier, specialite, estEmploye) 
                        VALUES (createIdEmployes() ,:nom, :prenom, :specialite, :estEmploye)";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':specialite', $specialite);
            $stmt->bindParam(':estEmploye', $estEmploye);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function insertEmploye($pdo, $nom, $prenom, $numTelEmploye, $login, $pwd) {
        try {
            $pdo->beginTransaction();
            
            // Insert into employe table
            $requete1 = "INSERT INTO employe (idEmploye, nomEmploye, prenomEmploye, numTelEmploye)
                        VALUES (createIdEmployes(), :nom, :prenom, :numTelEmploye)";
            $stmt1 = $pdo->prepare($requete1);
            $stmt1->bindParam(':nom', $nom);
            $stmt1->bindParam(':prenom', $prenom);
            $stmt1->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt1->execute();

            // Retrieve the last inserted idEmploye
            $requete2 = 'SELECT MAX(idEmploye) AS maxId FROM employe';
            $stmt2 = $pdo->prepare($requete2);
            $stmt2->execute();
            $result = $stmt2->fetch(PDO::FETCH_ASSOC);
            $idEmploye = $result['maxId'];

            // Insert into login table
            $requete3 = "INSERT INTO login (login, pwd, idEmploye) VALUES (:login, :pwd, :idEmploye)";
            $stmt3 = $pdo->prepare($requete3);
            $stmt3->bindParam(':login', $login);
            $stmt3->bindParam(':pwd', $pwd);
            $stmt3->bindParam(':idEmploye', $idEmploye);
            $stmt3->execute();

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new PDOException("Erreur lors de l'insertion de l'employé : " . $e->getMessage(), (int)$e->getCode());
        }
    }

function insertExposition($pdo, $intitule, $periodeDebut, $periodeFin, $nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp, $motsCles) {
    try {
        $pdo->beginTransaction();
        // Requête pour insérer dans la table `exposition`
        $queryExposition = "INSERT INTO `exposition` 
            (`idExposition`, `intitule`, `periodeDebut`, `periodeFin`, `nombreOeuvres`, `resume`, `debutExpoTemp`, `finExpoTemp`) 
            VALUES (createIdExpositions(), :intitule, :periodeDebut, :periodeFin, :nombreOeuvres, :resume, :debutExpoTemp, :finExpoTemp)";

        $stmtExposition = $pdo->prepare($queryExposition);
        $stmtExposition->execute([
            ':intitule' => $intitule,
            ':periodeDebut' => $periodeDebut,
            ':periodeFin' => $periodeFin,
            ':nombreOeuvres' => $nombreOeuvres,
            ':resume' => $resume,
            ':debutExpoTemp' => $debutExpoTemp,
            ':finExpoTemp' => $finExpoTemp
        ]);
        // Recupérer l'id de l'exposition créée pour l'insertion des mots clés
        $queryIdExposition = "SELECT MAX(idExposition) AS maxId FROM exposition";
        $stmtIdExposition = $pdo->prepare($queryIdExposition);
        $stmtIdExposition->execute();
        $result = $stmtIdExposition->fetch(PDO::FETCH_ASSOC);
        $idExposition = $result['maxId'];

        // Séparer les mots-clés par la virgule et supprimer les espaces inutiles
        $motsClesArray = array_map('trim', explode(',', $motsCles));

        // Préparer la requête pour insérer un mot-clé
        $query = "INSERT INTO `motsCle` (`motCle`, `idExposition`) 
              VALUES (:motCle, :idExposition)";
        $stmt = $pdo->prepare($query);

        try {
            // Boucle pour insérer chaque mot-clé individuellement
            foreach ($motsClesArray as $motCle) {
                if (!empty($motCle)) { // Vérifier que le mot-clé n'est pas vide
                    $stmt->execute([
                        ':motCle' => $motCle,
                        ':idExposition' => $idExposition
                    ]);
                }
            }
        } catch (PDOException $e) {
            throw new Exception( "Erreur lors de l'insertion des mots-clés : " . $e->getMessage());
        }
        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw new PDOException("Erreur lors de l'insertion de l'exposition : " . $e->getMessage());
    }
}
    
    function insertIndisponibilites($pdo, $dateDebutIndispo, $dateFinIndispo, $idConferencier) {
        try {
            $pdo->beginTransaction();
            
            $requete = "INSERT INTO indisponibilites (dateDebutIndispo, dateFinIndispo, idConferencier) 
                        VALUES (:dateDebutIndispo, :dateFinIndispo, :idConferencier)";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':dateDebutIndispo', $dateDebutIndispo);
            $stmt->bindParam(':dateFinIndispo', $dateFinIndispo);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->execute();
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function insertSpecialite($pdo, $intitule, $idConferencier) {
        try {
            $pdo->beginTransaction();
            
            $requete = "INSERT INTO specialites (intitule, idConferencier) 
                        VALUES (:intitule, :idConferencier)";
                        
            $stmt = $pdo->prepare($requete);
            
            $stmt->bindParam(':intitule', $intitule);
            $stmt->bindParam(':idConferencier', $idConferencier);
            
            $stmt->execute();
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function insertMotsCles($pdo, $motCle, $idExposition) {
        try {
            $pdo->beginTransaction();
            
            $requete = "INSERT INTO motscle (motCle, idExposition) 
                        VALUES (:motCle, :idExposition)";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':motCle', $motCle);
            $stmt->bindParam(':idExposition', $idExposition);
            $stmt->execute();
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function insertVisite($pdo, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye) {
        try {
            $pdo->beginTransaction();
            
            $requete = "INSERT INTO visite (idVisite, dateVisite, heureDebutVisite, intituleVisite, numTelVisite, idExposition, idConferencier, idEmploye) 
                        VALUES (createIdVisites() ,:dateVisite, :heureDebutVisite, :intituleVisite, :numTelVisite, :idExposition, :idConferencier, :idEmploye)";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':dateVisite', $dateVisite);
            $stmt->bindParam(':heureDebutVisite', $heureDebutVisite);
            $stmt->bindParam(':intituleVisite', $intituleVisite);
            $stmt->bindParam(':numTelVisite', $numTelVisite);
            $stmt->bindParam(':idExposition', $idExposition);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->bindParam(':idEmploye', $idEmploye);
            $stmt->execute();
            
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function verifierMdp($chaine) {
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