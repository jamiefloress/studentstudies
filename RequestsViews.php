<?php

	class RequestsViews {
		private $stylesheet = 'stylesheet.css';
		private $pageTitle = 'Requests';

		public function __construct() {

		}

		public function __destruct() {

		}

		public function requestListView($requests, $orderBy = 'name', $orderDirection = 'asc', $message = '') {
			$body = "<h2>REQUESTS</h2><br>\n";

			if ($message) {
				$body .= "<p class='message'>$message</p>\n";
			}

			$body .= "<p><a class='requestButton' href='index.php?view=requestform'>+ Add Request</a></p><br><br>\n";

			if (count($requests) < 1) {
				$body .= "<p>No requests to display!</p>\n";
				return $body;
			}

			$body .= "<table>\n";
			$body .= "<tr>";

			$columns = array(array('name' => 'code', 'label' => 'Course'),
							 array('name' => 'name', 'label' => 'Name'),
							 array('name' => 'pawprint', 'label' => 'PawPrint'),
							 array('name' => 'description', 'label' => 'Description'),
							 array('name' => 'dateCreated', 'label' => 'Add Date'),
							 array('name' => 'dateCompleted', 'label' => 'Completed Date'));

			foreach ($columns as $column) {
				$name = $column['name'];
				$label = $column['label'];
				if ($name == $orderBy) {
					if ($orderDirection == 'asc') {
						$label .= " &#x25BC;";  // ▼
					} else {
						$label .= " &#x25B2;";  // ▲
					}
				}
				$body .= "<th><a class='order' href='index.php?orderby=$name'>$label</a></th>";
			}

			$body .= "<th>Completed</th><th>Edit</th><th>Delete</th>";

			foreach ($requests as $request) {
				$id = $request['id'];
				$dateCreated = $request['dateCreated'];
				$dateCompleted = ($request['dateCompleted']) ? $request['dateCompleted'] : '';
				$name = $request['name'];
				$pawprint = $request['pawprint'];
				$description = ($request['description']) ? $request['description'] : '';
				$title = $request['title'];
				$code = $request['code'];

				$completedAction = 'set_completed';
				$completedLabel = 'not completed';
				if ($dateCompleted) {
					$completedAction = 'set_not_completed';
					$completedLabel = 'completed';
				}

				$body .= "<tr>";
				$body .= "<td>$code - $title</td><td>$name</td><td>$pawprint</td><td>$description</td><td>$dateCreated</td><td>$dateCompleted</td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='$completedAction' /><input type='hidden' name='id' value='$id' /><input type='submit' value='$completedLabel'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='delete' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Delete'></form></td>";
				$body .= "<td><form action='index.php' method='post'><input type='hidden' name='action' value='edit' /><input type='hidden' name='id' value='$id' /><input type='submit' value='Edit'></form></td>";
				$body .= "</tr>\n";
			}
			$body .= "</table>\n";

			return $this->page($body);
		}

		public function requestFormView($data = null, $message = '') {
			$name = '';
			$pawprint = '';
			$description = '';
			$course_id = '';
			if ($data) {
				$name = $data['name'];
				$pawprint = $data['pawprint'];
				$description = $data['description'];
				$course_id = $data['course_id'];
			}

			$html = <<<EOT1
<!DOCTYPE html>
<html>
<head>
<title>Request</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<link rel="icon" href="Logo.png" type="image/gif" sizes="16x16">
</head>
<body>
<h2>REQUEST</h2>
EOT1;

			if ($message) {
				$html .= "<p class='message'>$message</p>\n";
			}

			$html .= "<form class='requestform' action='index.php' method='post'>";

			if ($data['id']) {
				$html .= "<input type='hidden' name='action' value='update' />";
				$html .= "<input type='hidden' name='id' value='{$data['id']}' />";
			} else {
				$html .= "<input type='hidden' name='action' value='add' />";
			}

			$html .= "<p class='titles'>Courses<br />";
			$html .= "<select name = 'course_id'>";

			require ('db_credentials.php');

			// Create connection
			$mysqli = new mysqli($servername, $username, $password, $dbname);

			$sql = "SELECT id, code FROM courses";
			$result = $mysqli->query($sql);
			$courses = array();
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
						array_push($courses, $row);
				}
			}

			$mysqli->close();

			foreach($courses as $course) {
				$code = $course['code'];
				$id = $course['id'];
				if($course_id == $id) {
					$html .= "<option value='" . $id . "' selected>" . $code . "</option>";
				}
				else {
					$html .= "<option value='" . $id . "'>" . $code . "</option>";
				}
			}

			$html .= "</select>";
			$html .= "</p>";

			$html .= <<<EOT2
<p class="titles">Name<br />
<input type="text" name="name" value="$name" placeholder="Name" maxlength="255" size="80"></p>

<p class="titles">PawPrint<br />
<input type="text" name="pawprint" value="$pawprint" placeholder="Pawprint" maxlength="255" size="80"></p>

<p class="titles">Description<br />
<textarea name="description" rows="6" cols="80" placeholder="Description">$description</textarea></p>

<div class="space"><input class="button" type="submit" name="submit" value="Submit"></div> <div class="space"><input class="button" type="submit" name="cancel" value="Cancel"></div>
</form>
</body>
</html>
EOT2;

			print $html;
		}

		public function errorView($message) {
			$body = "<h1>Requests</h1>\n";
			$body .= "<p>$message</p>\n";

			return $this->page($body);
		}

		private function page($body) {
			require ('db_credentials.php');

			// Create connection
			$mysqli = new mysqli($servername, $username, $password, $dbname);

			$sql = "SELECT id, code FROM courses";
			$result = $mysqli->query($sql);
			$courses = array();
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
						array_push($courses, $row);
				}
			}

			$mysqli->close();




			$html = <<<EOT
<!DOCTYPE html>
<html lang='en>'
<html>
<head>
<title>{$this->pageTitle}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{$this->stylesheet}">
<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
<link rel="icon" href="Logo.png" type="image/gif" sizes="16x16">
</head>
<body>
<nav class="navbar navbar-inverse">

</nav>

<div class="container-fluid text-center">

      <div class='logo'><h1>STUDENT STUDIES</h1>
			<img src="Logo.png" alt="Logo"></div>
			<hr>
      <p>Are you struggling with your math homework? Are you tired of working on labs alone? Do you need help editing a paper? <br>We're here to help you find study partners for your classes!<br>
			<br>
			<b>How to use: </b>Check the table below to see if someone else has requested help in your class!
			<br>
			If someone has, email them using the pawprint they provided to setup a meeting time.
			<br>
			If no one has requested help in your class, follow steps 1 - 5.
			<br>
			<br>
			P.S. You can click on the heading "Course" in the table to sort the requests so it's easier to find your course!
			<br>
			<br>
			<b>Step 1: </b>Click on the button 'Add Request'
			<br>
			<b>	Step 2:	</b> Fill in the form with your personal information
			<br>
			<b>Step 3: 	</b>Sumbit your information
			<br>
			<b>	Step 4:	</b> Check back and see if another student has requested help in your course.
			<br>
			<b>	Step 5:</b> Once you've found a match, come back and mark your search as complete! Or delete it...
			<br>
			</p>
      <hr>
      <p>$body</p>
</div>
</body>
</html>
EOT;
			return $html;
		}

}
