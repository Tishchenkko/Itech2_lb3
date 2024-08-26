<?php
    $project_name = isset($_GET['project_name']) ? $_GET['project_name'] : '';

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
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(w.time_end, w.time_start)))) AS total_time_spent
        FROM project p
        JOIN work w ON p.ID_PROJECTS = w.FID_PROJECTS
        WHERE p.name = :project_name
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result["total_time_spent"]) {
            $time_spent = $result["total_time_spent"];
        } else {
            $time_spent = "Project with name $project_name not found";
        }

        // Prepare responses
        $plain_text = "Time spent on project " . htmlspecialchars($project_name) . "\nIs " . htmlspecialchars($time_spent) . " hours\n";
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
