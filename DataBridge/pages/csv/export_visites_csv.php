<?php
    session_start();
    include("../../fonction/fonctionsAuthentification.php");
    
    if(isset($_SESSION['loginAdmin']) && $_SESSION['loginAdmin'] != "") {
        $login = $_SESSION['loginAdmin'];
        $admin = true;
    } else if (isset($_SESSION["loginEmploye"]) && $_SESSION['loginEmploye'] != "") {
        $login = $_SESSION["loginEmploye"];
    } else {
        header('Location: ../../index.php');
        exit();
    }
    
    try {
        $pdo = connecterBd();
        
        // préparation de la requête pour importer les visites
        $sql = "
            SELECT
                visite.idVisite,
                visite.idExposition,
                visite.idConferencier,
                visite.idEmploye,
                DATE_FORMAT(visite.dateVisite, '%d/%m/%Y') AS dateVisite,
                DATE_FORMAT(visite.heureDebutVisite, '%Hh%i') AS heureDebutVisite,
                visite.intituleVisite,
                visite.numTelVisite
            FROM visite";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $visites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=visites.csv');
    
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Ident', 'Exposition', 'Conférencier', 'Employé', 'date', 'heuredebut', 'Intitulé', 'Téléphone', '', ''], ';');
    
        foreach ($visites as $visite) {
            $visite[] = ''; // Ajouter une colonne vide
            $visite[] = ''; // Ajouter une deuxième colonne vide
            fputcsv($output, $visite, ';');
        }
    
        fclose($output);
        exit();
    } catch (PDOException $e) {
        header('Location: ../erreurConnexion.php');
        exit();
    }
?>