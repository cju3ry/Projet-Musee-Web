<?php
    function insertEmploye($pdo, $nom, $prenom, $numTelEmploye, $login, $pwd) {
        $tabEmploye = array();
        
        try {
            $pdo->beginTransaction();
            
            // vérification si homonyme existant
            $requete = "SELECT nomEmploye, prenomEmploye FROM employe WHERE nomEmploye = :nomEmploye AND prenomEmploye = :prenomEmploye";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':nomEmploye', $nom);
            $stmt->bindParam(':prenomEmploye', $prenom);
            $stmt->execute();
            
            while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tabEmploye[] = $ligne['nomEmploye'];
            }
            
            if (!empty($tabEmploye)) {
                throw new Exception("Erreur lors de l'insertion de l'employé");
            }
            
            // Insertion des employés
            $requete1 = "INSERT INTO employe (idEmploye, nomEmploye, prenomEmploye, numTelEmploye)
                        VALUES (createIdEmployes(), :nom, :prenom, :numTelEmploye)";
            $stmt1 = $pdo->prepare($requete1);
            $stmt1->bindParam(':nom', $nom);
            $stmt1->bindParam(':prenom', $prenom);
            $stmt1->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt1->execute();

            // récupération du dernier id employé insérer
            $requete2 = 'SELECT MAX(idEmploye) AS maxId FROM employe';
            $stmt2 = $pdo->prepare($requete2);
            $stmt2->execute();
            $result = $stmt2->fetch(PDO::FETCH_ASSOC);
            $idEmploye = $result['maxId'];

            // Insertion du login de l'employé
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
        
    function insertConferencier($pdo, $nom, $prenom, $estEmploye, $specialites, $indisponibilites,$telephone) {
        $tabConferencier = array();
            
        try {
            // Démarrer la transaction
            $pdo->beginTransaction();
            
            // vérification si homonyme existant
            $requete = "SELECT nomConferencier, prenomConferencier FROM conferencier WHERE nomConferencier = :nomConferencier AND prenomConferencier = :prenomConferencier";
            $stmt1 = $pdo->prepare($requete);
            $stmt1->bindParam(':nomConferencier', $nom);
            $stmt1->bindParam(':prenomConferencier', $prenom);
            $stmt1->execute();
            
            while ($ligne = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $tabConferencier[] = $ligne['nomConferencier'];
            }
            
            if (!empty($tabConferencier)) {
                throw new Exception("Erreur lors de l'insertion du conférencier, homonyme existant avec " . $nom . " " .  $prenom . ".");
            }
    
            // Requête pour insérer dans la table `conferencier`
            $queryConferencier = "INSERT INTO `conferencier` (`idConferencier`, `nomConferencier`, `prenomConferencier`, `estEmploye`, `telephone`) 
                                  VALUES (createIdConferenciers(), :nom, :prenom, :estEmploye, :telephone)";
            
            $stmtConferencier = $pdo->prepare($queryConferencier);
            $stmtConferencier->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':estEmploye' => $estEmploye,
                ':telephone' => $telephone
            ]);
    
            // Récupère l'ID du conférencier inséré
            $queryIdConferencier = "SELECT MAX(idConferencier) AS maxId FROM conferencier";
            $stmtIdConferencier = $pdo->prepare($queryIdConferencier);
            $stmtIdConferencier->execute();
            $result = $stmtIdConferencier->fetch(PDO::FETCH_ASSOC);
            $idConferencier = $result['maxId'];
    
            // Insère les spécialités
            $specialitesArray = array_map('trim', explode(',', $specialites));
            $querySpecialite = "INSERT INTO `specialites` (`intitule`, `idConferencier`) VALUES (:intitule, :idConferencier)";
            $stmtSpecialite = $pdo->prepare($querySpecialite);
            foreach ($specialitesArray as $specialite) {
                if (!empty($specialite)) {
                    $stmtSpecialite->execute([
                        ':intitule' => $specialite,
                        ':idConferencier' => $idConferencier
                    ]);
                }
            }
    
            // Insère les indisponibilités
            $indisponibilitesArray = array_chunk(array_map('trim', explode(',', $indisponibilites)), 2);
            $queryIndisponibilite = "INSERT INTO `indisponibilites` (`dateDebutIndispo`, `dateFinIndispo`, `idConferencier`) 
                                     VALUES (:dateDebut, :dateFin, :idConferencier)";
            $stmtIndisponibilite = $pdo->prepare($queryIndisponibilite);
            foreach ($indisponibilitesArray as $indispo) {
                if (count($indispo) === 2) { // Vérifier que nous avons bien un couple (date début, date fin)
                    $stmtIndisponibilite->execute([
                        ':dateDebut' => $indispo[0],
                        ':dateFin' => $indispo[1],
                        ':idConferencier' => $idConferencier
                    ]);
                }
            }
            $pdo->commit();
    
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new PDOException("Erreur lors de l'insertion du conférencier : " . $e->getMessage());
        }
    }
      
    function insererVisite($pdo, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye) {
        // On vérifie que le conferencier est disponible 
        try {
            $query = "SELECT dateDebutIndispo, dateFinIndispo FROM indisponibilites WHERE idConferencier = :idConferencier";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $dateStart = $row['dateDebutIndispo'];
                $dateEnd = $row['dateFinIndispo'];
                if ($dateVisite >= $dateStart && $dateVisite <= $dateEnd) {
                    return false;
                } else {
                    throw new Exception("ID non trouvé dans la base de données.");
                }
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
        
        // On vérifie que l'exposition est disponible
        try {
            $query = "SELECT debutExpoTemp, finExpoTemp
                      FROM exposition
                      WHERE idExposition = :idExposition
                      AND debutExpoTemp IS NOT NULL
                      AND finExpoTemp IS NOT NULL;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':idExposition', $idExposition);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $dateStart = $row['debutExpoTemp'];
                $dateEnd = $row['finExpoTemp'];
                if (!($dateVisite >= $dateStart && $dateVisite <= $dateEnd)) {
                    return false;
                } else {
                    throw new Exception("ID non trouvé dans la base de données.");
                }
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
        
        // Vérifie que tous les champs requis sont fournis
        if( empty($dateVisite) || empty($heureDebutVisite) || empty($intituleVisite) || empty($numTelVisite) || empty($idExposition) || empty($idConferencier) || empty($idEmploye)) {
            return false;
        }
    
        // Requête SQL pour insérer une visite
        $requete = "
            INSERT INTO visite (idVisite, dateVisite, heureDebutVisite, intituleVisite, numTelVisite, idExposition, idConferencier, idEmploye) 
            VALUES (createIdVisites(), :dateVisite, :heureDebutVisite, :intituleVisite, :numTelVisite, :idExposition, :idConferencier, :idEmploye)
        ";
    
        try {
            // Préparer la requête
            $stmt = $pdo->prepare($requete);
            
            // Associer les paramètres
            $stmt->bindParam(':dateVisite', $dateVisite, PDO::PARAM_STR);
            $stmt->bindParam(':heureDebutVisite', $heureDebutVisite, PDO::PARAM_STR);
            $stmt->bindParam(':intituleVisite', $intituleVisite, PDO::PARAM_STR);
            $stmt->bindParam(':numTelVisite', $numTelVisite, PDO::PARAM_STR);
            $stmt->bindParam(':idExposition', $idExposition, PDO::PARAM_STR);
            $stmt->bindParam(':idConferencier', $idConferencier, PDO::PARAM_STR);
            $stmt->bindParam(':idEmploye', $idEmploye, PDO::PARAM_STR);
            $stmt->execute();
    
            return true;
        } catch (PDOException $e) {
            exit ("Erreur: " . $e->getMessage());
            return false;
        }
    }
?>