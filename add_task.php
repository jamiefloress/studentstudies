<?php
	require ('db_credentials.php');
	require ('web_utils.php');

	$stylesheet = 'taskmanager.css';

	$course = $_POST['course'];
	$name = $_POST['name'] ? $_POST['name'] : "unnamed";
	$student_id =$_POST['student_id'];
	$pawprint = $_POST['pawprint'] ? $_POST['pawprint'] : "untitled";
	$description = $_POST['description'] ? $_POST['description'] : "";


	// Create connection
	$mysqli = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($mysqli->connect_error) {
		print generatePageHTML("Requests (Error)", generateErrorPageHTML($mysqli->connect_error), $stylesheet);
		exit;
	}

	$course = $mysqli->real_escape_string($course);
	$name = $mysqli->real_escape_string($name);
	$student_id = $mysqli->real_escape_string($student_id);
	$pawprint = $mysqli->real_escape_string($pawprint);
	$description = $mysqli->real_escape_string($description);

	$sql = "INSERT INTO request (course_id, name, pawprint, description, dateCreated) VALUES ('$course', '$name', '$pawprint', '$description', NOW())";

	$result = $mysqli->query($sql);
	if ($result) {
		// insert successfull, redirect browser to index.php to see list of tasks
		redirect("index.php");
	} else {
		print generatePageHTML("Requests (Error)", generateErrorPageHTML($mysqli->error . " using SQL: $sql"), $stylesheet);
		exit;
	}


	function generateErrorPageHTML($error) {
	$html = <<<EOT
<h1>Requests</h1>
<p>An error occurred: $error</p>
<p><a class='taskButton' href='task_form.html'>Add Task</a><a class='taskButton' href='index.php'>View Tasks</a></p>
EOT;

	return $html;
	}
?>
