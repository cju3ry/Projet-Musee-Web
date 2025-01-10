<?php
    // modification d'un employé
    function modifierEmploye($pdo, $idEmploye, $nomEmploye, $prenomEmploye, $numTelEmploye, $login, $pwd = null) {
        $tabEmploye = array();
        
        try {
            $pdo->beginTransaction();
            
            // vérification si homonyme existant
            $requete = "SELECT nomEmploye, prenomEmploye FROM employe WHERE nomEmploye = :nomEmploye AND prenomEmploye = :prenomEmploye";
            $stmt = $pdo->prepare($requete);
            $stmt->bindParam(':nomEmploye', $nomEmploye);
            $stmt->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt->execute();
            
            while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tabEmploye[] = $ligne['nomEmploye'];
            }
            
            if (!empty($tabEmploye)) {
                return false;
            }

            // Mise à jour des informations de l'employé
            $stmt1 = $pdo->prepare('UPDATE employe 
                                SET nomEmploye = :nomEmploye,
                                    prenomEmploye = :prenomEmploye,
                                    NumTelEmploye = :numTelEmploye
                                WHERE idEmploye = :idEmploye');

            $stmt1->bindParam(':nomEmploye', $nomEmploye);
            $stmt1->bindParam(':prenomEmploye', $prenomEmploye);
            $stmt1->bindParam(':numTelEmploye', $numTelEmploye);
            $stmt1->bindParam(':idEmploye', $idEmploye);

            // Mise à jour du login (et du mot de passe si fourni)
            $query = 'UPDATE login SET login = :login';
            if ($pwd !== null) {
                $query .= ', pwd = :pwd';
            }
            $query .= ' WHERE idEmploye = :idEmploye';

            $stmt2 = $pdo->prepare($query);
            $stmt2->bindParam(':login', $login);
            if ($pwd !== null) {
                $stmt2->bindParam(':pwd', $pwd);
            }
            $stmt2->bindParam(':idEmploye', $idEmploye);

            if ($stmt1->execute() && $stmt2->execute()) {
                $pdo->commit();
                return true;
            }

        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }
    
    // modification d'une exposition
    function modifierExposition($pdo, $idExposition, $intitule, $periodeDebut, $periodeFin,$nombreOeuvres, $resume, $debutExpoTemp, $finExpoTemp,$motsCleStr) {
        try {
            // Démarrer la transaction
            $pdo->beginTransaction();
    
            // Préparer la requête SQL
            $sql = "UPDATE exposition 
                    SET intitule = :intitule, 
                        periodeDebut = :periodeDebut, 
                        periodeFin = :periodeFin, 
                        nombreOeuvres = :nombreOeuvres, 
                        resume = :resume, 
                        debutExpoTemp = :debutExpoTemp, 
                        finExpoTemp = :finExpoTemp 
                    WHERE idExposition = :idExposition";
    
            // Préparer la requête avec PDO
            $stmt = $pdo->prepare($sql);
    
            // Associe les valeurs aux paramètres
            $stmt->bindParam(':intitule', $intitule, PDO::PARAM_STR);
            $stmt->bindParam(':periodeDebut', $periodeDebut, PDO::PARAM_INT);
            $stmt->bindParam(':periodeFin', $periodeFin, PDO::PARAM_INT);
            $stmt->bindParam(':nombreOeuvres', $nombreOeuvres, PDO::PARAM_INT);
            $stmt->bindParam(':resume', $resume, PDO::PARAM_STR);
            $stmt->bindParam(':debutExpoTemp', $debutExpoTemp, PDO::PARAM_STR);
            $stmt->bindParam(':finExpoTemp', $finExpoTemp, PDO::PARAM_STR);
            $stmt->bindParam(':idExposition', $idExposition, PDO::PARAM_STR);
    
            // Exécute la requête
            $stmt->execute();
    
    
            // Converti la liste des mots-clés en tableau
            $motsCleArray = array_map('trim', explode(',', $motsCleStr));
    
            // Supprime les mots-clés qui ne sont plus dans la liste
            $placeholders = implode(',', array_fill(0, count($motsCleArray), '?'));
            $deleteQuery = "
                DELETE FROM motsCle
                WHERE idExposition = ?
                  AND motCle NOT IN ($placeholders)
            ";
            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->execute(array_merge([$idExposition], $motsCleArray));
    
            // Ajoute les nouveaux mots-clés absents
            foreach ($motsCleArray as $motCle) {
                $insertQuery = "
                    INSERT INTO motsCle (motCle, idExposition)
                    SELECT ?, ?
                    WHERE NOT EXISTS (
                        SELECT 1 FROM motsCle WHERE motCle = ? AND idExposition = ?
                    )
                ";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->execute([$motCle, $idExposition, $motCle, $idExposition]);
            }
    
            $pdo->commit();
            return true;
    
        } catch (PDOException $e) {
            // En cas d'erreur, annule la transaction
            $pdo->rollBack();
            exit($e->getMessage(). $e->getLine(). $e->getFile(). $e->printStackTrace());
            return false;
        }
    }
    
    // modification d'un conférencier
    function modifierConferencier($pdo, $idConferencier, $nom, $prenom,$telephone, $estEmploye, $specialitesStr, $indisponibilitesStr) {
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
                return false;
            }
    
            // Mettre à jour les informations du conférencier
            $sql = "UPDATE conferencier 
                    SET nomConferencier = :nom, 
                        prenomConferencier = :prenom, 
                        estEmploye = :estEmploye,
                        telephone = :telephone
                    WHERE idConferencier = :idConferencier";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            // Assure que la valeur est de longueur max 3
            $estEmploye = substr($estEmploye, 0, 3);
            $stmt->bindParam(':estEmploye', $estEmploye, PDO::PARAM_STR);
            $stmt->bindParam(':idConferencier', $idConferencier, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->execute();
    
            // Vérifier si idConferencier existe dans la table conferencier
            $checkConferencier = $pdo->prepare("SELECT idConferencier FROM conferencier WHERE idConferencier = :idConferencier");
            $checkConferencier->bindParam(':idConferencier', $idConferencier, PDO::PARAM_STR);
            $checkConferencier->execute();
    
            if ($checkConferencier->rowCount() == 0) {
                throw new Exception("Le conférencier avec ID $idConferencier n'existe pas.");
            }
    
            // Gérer les spécialités
            $specialitesArray = array_map('trim', explode(',', $specialitesStr));
            $placeholdersSpecialites = implode(',', array_fill(0, count($specialitesArray), '?'));
    
            // Supprimer les spécialités qui ne sont plus dans la liste
            $deleteSpecialitesQuery = "
                DELETE FROM specialites
                WHERE idConferencier = ? 
                  AND intitule NOT IN ($placeholdersSpecialites)
            ";
            $deleteStmt = $pdo->prepare($deleteSpecialitesQuery);
            $deleteStmt->execute(array_merge([$idConferencier], $specialitesArray));
    
            // Ajouter les nouvelles spécialités
            foreach ($specialitesArray as $specialite) {
                $insertSpecialitesQuery = "
                    INSERT INTO specialites (intitule, idConferencier)
                    SELECT ?, ? 
                    WHERE NOT EXISTS (
                        SELECT 1 FROM specialites WHERE intitule = ? AND idConferencier = ?
                    )
                ";
                $insertStmt = $pdo->prepare($insertSpecialitesQuery);
                $insertStmt->execute([$specialite, $idConferencier, $specialite, $idConferencier]);
            }
    
            // Gérer les indisponibilités
            $indisponibilitesArray = array_chunk(array_map('trim', explode(',', $indisponibilitesStr)), 2);
    
            // Supprimer les indisponibilités existantes
            $deleteIndisposQuery = "DELETE FROM indisponibilites WHERE idConferencier = ?";
            $deleteStmt = $pdo->prepare($deleteIndisposQuery);
            $deleteStmt->execute([$idConferencier]);
    
            // Ajouter les nouvelles indisponibilités
            foreach ($indisponibilitesArray as $indispo) {
                if (count($indispo) == 2) { // Vérifier que la paire est complète (date début et fin)
                    $insertIndisposQuery = "
                        INSERT INTO indisponibilites (dateDebutIndispo, dateFinIndispo, idConferencier)
                        VALUES (?, ?, ?)
                    ";
                    $insertStmt = $pdo->prepare($insertIndisposQuery);
                    $insertStmt->execute([$indispo[0], $indispo[1], $idConferencier]);
                }
            }
    
            // Valider la transaction
            $pdo->commit();
            return true;
    
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            exit( "Erreur : " . $e->getMessage().$e->getCode().$e->getFile().$e->getLine());
            return false;
        } catch (Exception $e) {
            $pdo->rollBack();
            exit( "Erreur : " . $e->getMessage());
            return false;
        }
    }
    
    // modification d'une visite
    function modifierVisite($pdo, $idVisite, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye) {
        try {
            // Requête SQL pour mettre à jour les informations d'une visite
    
            $sql = "UPDATE visite
                SET dateVisite = :dateVisite,
                    heureDebutVisite = :heureDebutVisite,
                    intituleVisite = :intituleVisite,
                    numTelVisite = :numTelVisite,
                    idExposition = :idExposition,
                    idConferencier = :idConferencier,
                    idEmploye = :idEmploye
                WHERE idVisite = :idVisite";
    
    
            $stmt = $pdo->prepare($sql);
    
            // Lier les paramètres à la requête préparée
            $stmt->bindParam(':dateVisite', $dateVisite);
            $stmt->bindParam(':heureDebutVisite', $heureDebutVisite);
            $stmt->bindParam(':intituleVisite', $intituleVisite);
            $stmt->bindParam(':numTelVisite', $numTelVisite);
            $stmt->bindParam(':idExposition', $idExposition);
            $stmt->bindParam(':idConferencier', $idConferencier);
            $stmt->bindParam(':idEmploye', $idEmploye);
            $stmt->bindParam(':idVisite', $idVisite);
    
            // Exécuter la requête et retourner true si l'exécution a réussi, false sinon
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
?>