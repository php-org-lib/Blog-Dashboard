<?php
    global $pdo;
    $type = "mysql";
    $host = "127.0.0.1";
    $username = "root";
    $password = "root";
    $databaseName = "blog";
    $port = 8600;
    $charset = "utf8mb4";

        $dsn = "$type:host=$host;dbname=$databaseName;port=$port;charset=$charset";
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        try {
            $pdo = new PDO($dsn, $username, $password, $default_options);
        } catch (PDOException $e) {
            error_log("There has been an error connecting to the database: " . $e->getMessage());
            throw $e;
        }


    function pdo(PDO $pdo, string $sql, array $params = null) {
        if (!$params) {
            return $pdo->query($sql);
        }
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $pdo->commit();
            return $stmt;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("There has been an error executing the query: " . $e->getMessage());
            return $e->getMessage();
        }
    }


?>