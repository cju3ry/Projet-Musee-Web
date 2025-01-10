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
    
        // Déterminer le nombre maximal de dates d'indisponibilités
        $sql_max = "SELECT conferencier.idConferencier, COUNT(indisponibilites.idIndisponibilite) AS nb_indispos
                    FROM conferencier
                    LEFT JOIN indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
                    GROUP BY conferencier.idConferencier
                    ORDER BY nb_indispos DESC
                    LIMIT 1";
                    
        $stmt_max = $pdo->prepare($sql_max);
        $stmt_max->execute();
        $max_indispos = $stmt_max->fetch(PDO::FETCH_ASSOC)['nb_indispos'];
    
        // Exécution de la requête SQL pour récupérer les données des conférenciers
        $sql = "SELECT
                conferencier.idConferencier,
                conferencier.nomConferencier,
                conferencier.prenomConferencier,
                CONCAT('#', GROUP_CONCAT(DISTINCT specialites.intitule SEPARATOR ', '), '#') AS specialites,
                conferencier.telephone,
                conferencier.estEmploye,
                GROUP_CONCAT(DISTINCT CONCAT(DATE_FORMAT(indisponibilites.dateDebutIndispo, '%d/%m/%Y'), ';', DATE_FORMAT(indisponibilites.dateFinIndispo, '%d/%m/%Y')) SEPARATOR ';') AS indisponibilites
            FROM conferencier
            LEFT JOIN specialites ON conferencier.idConferencier = specialites.idConferencier
            LEFT JOIN indisponibilites ON conferencier.idConferencier = indisponibilites.idConferencier
            GROUP BY conferencier.idConferencier, conferencier.nomConferencier, conferencier.prenomConferencier, conferencier.telephone, conferencier.estEmploye";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    
        // Préparation du fichier CSV
        $filename = 'conferenciers.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
    
        $output = fopen('php://output', 'w');
    
        // Écrire l'en-tête des colonnes
        $header = ['Ident', 'Nom', 'Prenom', 'Specialité', 'Telephone', 'Employe', 'Indisponibilite'];
        for ($i = 0; $i < $max_indispos; $i++) {
            $header[] = '';
        }
        
        fputcsv($output, $header, ';');
    
        // Écrire les lignes des résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $indispos = $row['indisponibilites'] ? explode(';', $row['indisponibilites']) : [];
            $row_data = [
                $row['idConferencier'],
                $row['nomConferencier'],
                $row['prenomConferencier'],
                $row['specialites'],
                $row['telephone'],
                strtolower($row['estEmploye']), // Convertir en minuscule
            ];
            
            foreach ($indispos as $indispo) {
                $row_data[] = $indispo;
            }
            
            // Ajouter des colonnes vides pour compléter la ligne
            while (count($row_data) < count($header)) {
                $row_data[] = '';
            }
            
            fputcsv($output, $row_data, ';');
        }
    
        fclose($output);
        exit;
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
        exit();
    }
?>