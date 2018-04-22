<?php
    require('db_credentials.php'); 
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("<p>Connection failed: " . $conn->connect_error . "</p>");
    }

    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

    $requests = array();
    while($row = $result->fetch_assoc()) {
            array_push($requests, $row);
        }

        $taskTableHTML = generateTaskTableHTML($requests);
        print generatePageHTML($taskTableHTML);
    }

    function generateTaskTableHTML($tasks) {
    $html = "<table>\n";
    $html .= "<tr><th>ID</th><th>Name</th><th>pawprint</th><th>Description</th><th>dateCreated</th></tr>\n";

    foreach ($requests as $request) {
    $html .= "<tr><td>{$request['id']}</td><td>{$request['name']}</td><td>{$request['pawprint']}</td><td>{$request['description']}</td><td>{$request['dateCreated']}</td></tr>\n";
    }
    $html .= "</table>\n";

    return $html;
    }

    function generatePageHTML($body) {
    $html = <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
    <title>Tasks</title>
    </head>
    <body>
    $body
    </body>
    </html>
    EOT;
        
    ?>    

    return $html;
    }

    ?>
