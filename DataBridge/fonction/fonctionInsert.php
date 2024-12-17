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

function insertEmploye($pdo, $nom, $prenom, $numTelEmploye) {
    try {
        $requete = "INSERT INTO employe (idEmploye, nomEmploye, prenomEmploye, numTelEmploye) 
                    VALUES (createIdEmployes() ,:nom, :prenom, :numTelEmploye)";
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':numTelEmploye', $numTelEmploye);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function insertExposition($pdo, $intitule, $periodeDebut, $periodeFin, $nombresOeuvres, $resume, $debutExpoTemp, $finExpoTemp) {
    try {
        $requete = "INSERT INTO exposition (idExposition, intitule, periodeDebut, periodeFin, nombreOeuvres, resume, debutExpoTemp, finExpoTemp) 
                    VALUES (createIdExpositions() ,:intitule, :periodeDebut, :periodeFin, :nombresOeuvres, :resume, :debutExpoTemp, :finExpoTemp)";
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->bindParam(':periodeDebut', $periodeDebut);
        $stmt->bindParam(':periodeFin', $periodeFin);
        $stmt->bindParam(':nombresOeuvres', $nombresOeuvres);
        $stmt->bindParam(':resume', $resume);
        $stmt->bindParam(':debutExpoTemp', $debutExpoTemp);
        $stmt->bindParam(':finExpoTemp', $finExpoTemp);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function insertIndisponibilites($pdo, $dateDebutIndispo, $dateFinIndispo, $idConferencier) {
    try {
        $requete = "INSERT INTO indisponibilites (dateDebutIndispo, dateFinIndispo, idConferencier) 
                    VALUES (:dateDebutIndispo, :dateFinIndispo, :idConferencier)";
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':dateDebutIndispo', $dateDebutIndispo);
        $stmt->bindParam(':dateFinIndispo', $dateFinIndispo);
        $stmt->bindParam(':idConferencier', $idConferencier);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function insertMotsCles($pdo, $motCle, $idExposition) {
    try {
        $requete = "INSERT INTO motscle (motCle, idExposition) 
                    VALUES (:motCle, :idExposition)";
        $stmt = $pdo->prepare($requete);
        $stmt->bindParam(':motCle', $motCle);
        $stmt->bindParam(':idExposition', $idExposition);
        $stmt->execute();
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function insertVisite($pdo, $dateVisite, $heureDebutVisite, $intituleVisite, $numTelVisite, $idExposition, $idConferencier, $idEmploye) {
    try {
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
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
}