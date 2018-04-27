<?php
	require ('db_credentials.php');
  require ('web_utils.php');

	$stylesheet = 'stylesheet.css';

	// Create connection
	$mysqli = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($mysqli->connect_error) {
		print generatePageHTML("Request (Error)", generateErrorPageHTML($mysqli->connect_error), $stylesheet);
		exit;
	}

	$sql = "SELECT * FROM request";
	$result = $mysqli->query($sql);
	$requests = array();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			array_push($requests, $row);
		}
	}

	print generatePageHTML("Requests", generateTaskTableHTML($requests), $stylesheet);

	function generateTaskTableHTML($requests) {
		$html = "<h1>Requests</h1>\n";

        $html .= "<p><a class ='taskButton' href= 'task_form.html'>+ Add Request</a></p>\n";

		if (count($requests) < 1) {
			$html .= "<p>No requests to display!</p>\n";
			return $html;
		}

		$html .= "<table>\n";
		$html .= "<tr><th>actions</th><th>id</th><th>Name</th><th>PawPrint</th><th>Description</th><th>Date Created</th></tr>\n";

		foreach ($requests as $request) {
			$id = $request['id'];
			$name = $request['name'];
			$pawprint = $request['pawprint'];
			$description = $request['description'];
			$dateCreated = $request['dateCreated'];

			$html .= "<tr><td><form action='delete_request.php' method='post'><input type='hidden' name='id' value='id' /><input type='submit' value='Delete'></form></td><td>$id</td><td>$name</td><td>$pawprint</td><td>$description</td><td>$dateCreated</td></tr>\n";

		}
		$html .= "</table>\n";

		return $html;
	}

	function generateErrorPageHTML($error) {
		$html = <<<EOT
<h1>Tasks</h1>
<p>An error occurred: $error</p>
EOT;

		return $html;
	}

?>
