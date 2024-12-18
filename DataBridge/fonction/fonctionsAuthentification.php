<?php
function connecterBd($base) {
    $host = 'sql8.freesqldatabase.com';
    $db = $base;
    $user = 'sql8752584';
    $pass = 'HytVJNeRCy';
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

    $pwd = MD5($pwd);

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

    $pwd = MD5($pwd);

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

function setIdEmploye($pdo, $idEmploye) {
    $requete = 'CALL SetCurrentUser(:idEmploye)';

    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(':idEmploye', $idEmploye);
    $stmt->execute();
}