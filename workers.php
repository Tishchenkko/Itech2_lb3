<?php
    $manager_name = isset($_GET['manager_name']) ? $_GET['manager_name'] : '';

    $host = "localhost";
    $databaseName = "lb_pdo_workers";
    $username = "root";
    $password = "";

    $dsn = "mysql:host=$host;dbname=$databaseName";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "
        SELECT d.chief, COUNT(w.ID_WORKER) AS worker_count
        FROM department d
        JOIN worker w ON d.ID_DEPARTMENT = w.FID_DEPARTMENT
        WHERE d.chief = :manager_name
        GROUP BY d.chief;
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $chief = $manager_name;
            $worker_count = $result["worker_count"];
        } else {
            $chief = $manager_name;
            $worker_count = 0;
        }

        // Prepare responses
        $plain_text = "Chief: " . htmlspecialchars($chief) . "\nHas " . htmlspecialchars($worker_count) . " workers\n";
        $json = json_encode([
            'chief' => $chief,
            'worker_count' => $worker_count
        ]);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<response>\n";
        $xml .= "  <chief>" . htmlspecialchars($chief) . "</chief>\n";
        $xml .= "  <worker_count>" . htmlspecialchars($worker_count) . "</worker_count>\n";
        $xml .= "</response>\n";

        // Return all formats in a JSON object
        header('Content-Type: application/json');
        echo json_encode([
            'plain_text' => $plain_text,
            'json' => $json,
            'xml' => $xml
        ]);

    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }

    $pdo = null;
?>