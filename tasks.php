<?php
    $project_name = isset($_GET['project_name']) ? $_GET['project_name'] : '';
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    $host = "localhost";
    $databaseName = "lb_pdo_workers";
    $username = "root";
    $password = "";

    $dsn = "mysql:host=$host;dbname=$databaseName";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "
        SELECT 
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(w.time_end, w.time_start)))) AS time_spent
        FROM project p
        JOIN work w ON p.ID_PROJECTS = w.FID_PROJECTS
        WHERE w.date = :date AND p.name = :project_name
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result["time_spent"]) {
            $time_spent = $result["time_spent"];
        } else {
            $time_spent = "No work or no project with this data";
        }

        // Prepare responses
        $plain_text = "Project: " . htmlspecialchars($project_name) . "\nTime spent: " . htmlspecialchars($time_spent) . "\n";
        $json = json_encode([
            'project_name' => $project_name,
            'time_spent' => $time_spent
        ]);
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<response>\n";
        $xml .= "  <project_name>" . htmlspecialchars($project_name) . "</project_name>\n";
        $xml .= "  <time_spent>" . htmlspecialchars($time_spent) . "</time_spent>\n";
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
