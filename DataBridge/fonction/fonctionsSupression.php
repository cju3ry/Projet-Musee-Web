<?php
    function suppressionEmploye($pdo, $idEmploye) {
		$tab = [];

		try {
			$requeteVerif = $pdo->prepare("SELECT idVisite FROM visite WHERE idEmploye = :idEmploye");
			$requeteVerif->bindParam(':idEmploye', $idEmploye);
			$requeteVerif->execute();

			while ($ligne = $requeteVerif->fetch(PDO::FETCH_ASSOC)) {
				$tab[] = $ligne['idEmploye'];
			}
	
			if (empty($tab)) {
			    $pdo->beginTransaction();
			    
				$stmt1 = $pdo->prepare("DELETE FROM login WHERE idEmploye = (:employeId)");
				$stmt2 = $pdo->prepare("DELETE FROM employe WHERE idEmploye = (:employeId)");
				
				$stmt1->bindParam(':employeId', $idEmploye);
				$stmt2->bindParam(':employeId', $idEmploye);
				
				if ($stmt1->execute() && $stmt2->execute()) {
                    $pdo->commit();
                }
                return true;
			}
		} catch (Exception $e) {
		    $pdo->rollBack();
		    return false;
		}
	}

    function suppressionVisite($pdo, $idVisite) {
        $tab = [];

		try {
		    $pdo->beginTransaction();
		    
			$stmt = $pdo->prepare("DELETE FROM visite WHERE idVisite = (:visiteId)");
    		$stmt->bindParam(':visiteId', $idVisite);
    		
    		if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
		} catch (Exception $e) {
		    $pdo->rollBack();
		    return false;
		}
	}

    function suppressionExposition($pdo, $idExposition) {
		try {
			$pdo->beginTransaction();
			suppressionMotCle($pdo, $idConferencier);
			$stmt = $pdo->prepare("DELETE FROM exposition WHERE idExposition = (:expositionId)");
			$stmt->bindParam(':expositionId', $idExposition);
			if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
		} catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

    function suppressionMotCles($pdo, $idExposition) {
        try {
            $pdo->beginTransaction();
            
    		$stmt = $pdo->prepare("DELETE FROM motCle WHERE idExposition = (:expositionId)");
    		$stmt->bindParam(':expositionId', $idExposition);
    		
    		if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
        } catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

    function suppressionConferencier($pdo, $idConferencier) {
		try {
			$pdo->beginTransaction();
			
			suppressionIndisponibilites($pdo, $idExposition);
			suppressionSpecialites($pdo, $idConferencier);
			
			$stmt = $pdo->prepare("DELETE FROM conferencier WHERE idConferencier = (:conferencierId)");
			$stmt->bindParam(':conferencierId', $idConferencier);
			
			if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
		} catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

    function suppressionIndisponibilites($pdo, $idExposition) {
        try {
            $pdo->beginTransaction();
            
    		$stmt = $pdo->prepare("DELETE FROM indisponibilite WHERE idConferencier = (:conferencierId)");
    		$stmt->bindParam(':conferencierId', $idConferencier);
    		
    		if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
        } catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}

	function suppressionSpecialites($pdo, $idConferencier) {
	    try {
    	    $pdo->beginTransaction();
    	    
    		$stmt = $pdo->prepare("DELETE FROM specialites WHERE idConferencier = (:idConferencier)");
    		$stmt->bindParam(':idConferencier', $idConferencier);
    		
    		if ($stmt->execute()) {
    		    $pdo->commit();
    		}
    		
    		return true;
	    } catch (Exception $e) {
			$pdo->rollBack();
		    return false;
		}
	}
?>