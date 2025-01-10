<?php
function connecterBd() {
    $host = 'localhost';
    $db = "u842999230_DataBridge"; // nom de la base de donnée
    $user = 'u842999230_DataBridgeUser';
    $pass = '@rI0grw00d_qUeens$@';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    
    return $pdo;
}

function loginEmploye($pdo, $login, $pwd) {

    $pwd = MD5($pwd); // encodage en MD5
    
    // requêtes de login employé
    $query = 'SELECT login FROM login';

    $query .= ' WHERE login = :login';

    $query .= ' AND pwd = :pwd';

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':login', $login);

    $stmt->bindParam(':pwd', $pwd);

    $stmt->execute();

    $tableLogEmploye = [];

    while ($row = $stmt->fetch()) {
        $tableLogEmploye[] = [
            'login' => $row['login'],
        ];
    }

    return $tableLogEmploye;
}

function loginAdmin($pdo, $login, $pwd) {

    $pwd = MD5($pwd); // encodage en MD5
    
    // requêtes de login administrateur
    $query = 'SELECT loginAdmin FROM admin';

    $query .= ' WHERE loginAdmin = :login';

    $query .= ' AND mdpAdmin = :pwd';

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':login', $login);

    $stmt->bindParam(':pwd', $pwd);

    $stmt->execute();

    $tableLogAdmin = [];

    while ($row = $stmt->fetch()) {
        $tableLogAdmin[] = [
            'loginAdmin' => $row['loginAdmin'],
        ];
    }

    return $tableLogAdmin;
}

// récupération de l'id de l'employé en fonction de son login
function getIdEmploye($pdo, $login) {
    $requete = 'SELECT idEmploye FROM login WHERE login LIKE :login';

    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $tab = [];

    while ($row = $stmt->fetch()) {
        $tab[] = [
            'idEmploye' => $row['idEmploye'],
        ];
    }

    return $tab;
}

// synchronisation de l'id de l'employé avec la base
function setIdEmploye($pdo, $idEmploye) {
    $requete = 'CALL SetCurrentUser(:idEmploye)';

    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(':idEmploye', $idEmploye);
    $stmt->execute();
}