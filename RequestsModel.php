<?php

	class RequestsModel {
		private $error = '';
		private $mysqli;
		private $orderBy = 'name';
		private $orderDirection = 'asc';

		public function __construct() {
			session_start();
			$this->initDatabaseConnection();
			$this->restoreOrdering();
		}

		public function __destruct() {
			if ($this->mysqli) {
				$this->mysqli->close();
			}
		}

		public function getError() {
			return $this->error;
		}

		private function initDatabaseConnection() {
			require('db_credentials.php');
			$this->mysqli = new mysqli($servername, $username, $password, $dbname);
			if ($this->mysqli->connect_error) {
				$this->error = $mysqli->connect_error;
			}
		}

		private function restoreOrdering() {
			$this->orderBy = $_SESSION['orderby'] ? $_SESSION['orderby'] : $this->orderBy;
			$this->orderDirection = $_SESSION['orderdirection'] ? $_SESSION['orderdirection'] : $this->orderDirection;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		public function toggleOrder($orderBy) {
			if ($this->orderBy == $orderBy)	{
				if ($this->orderDirection == 'asc') {
					$this->orderDirection = 'desc';
				} else {
					$this->orderDirection = 'asc';
				}
			} else {
				$this->orderDirection = 'asc';
			}
			$this->orderBy = $orderBy;

			$_SESSION['orderby'] = $this->orderBy;
			$_SESSION['orderdirection'] = $this->orderDirection;
		}

		public function getOrdering() {
			return array($this->orderBy, $this->orderDirection);
		}

		public function getRequests() {
			$this->error = '';
			$requests = array();

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($requests, $this->error);
			}

			$orderByEscaped = $this->mysqli->real_escape_string($this->orderBy);
			$orderDirectionEscaped = $this->mysqli->real_escape_string($this->orderDirection);
			$sql = "SELECT courses.code, courses.title, requests.id, requests.name, requests.pawprint, requests.description, DATE_FORMAT(dateCreated, '%W %D %M %Y') dateCreated, DATE_FORMAT(dateCompleted, '%W %D %M %Y') dateCompleted FROM requests INNER JOIN courses ON requests.course_id = courses.id ORDER BY $orderByEscaped $orderDirectionEscaped";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						array_push($requests, $row);
					}
				}
				$result->close();
			} else {
				$this->error = $mysqli->error;
			}

			return array($requests, $this->error);
		}

		public function getRequest($id) {
			$this->error = '';
			$request = null;

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return array($request, $this->error);
			}

			if (! $id) {
				$this->error = "No id specified for task to retrieve.";
				return array($request, $this->error);
			}

			$idEscaped = $this->mysqli->real_escape_string($id);

			$sql = "SELECT * FROM requests WHERE id = '$idEscaped'";
			if ($result = $this->mysqli->query($sql)) {
				if ($result->num_rows > 0) {
					$request = $result->fetch_assoc();
				}
				$result->close();
			} else {
				$this->error = $this->mysqli->error;
			}

			return array($request, $this->error);
		}

		public function addRequest($data) {
			$this->error = '';

			$course_id = $_POST['course_id'];
			$name = $_POST['name'];
			$pawprint = $_POST['pawprint'];
			$description = $_POST['description'] ? $_POST['description'] : "";

			if(! $course_id) {
				$this->wrror = "No course found. A course is required.";
				return $this->error;
			}

			if (! $name) {
				$this->error = "No name found for request to add. A name is required.";
				return $this->error;
			}

			if(! $pawprint) {
				$this->error = "No pawprint found. A pawprint is required.";
				return $this->error;
			}

			$course_idEscaped = $this->mysqli->real_escape_string($course_id);
			$nameEscaped = $this->mysqli->real_escape_string($name);
			$pawprintEscaped = $this->mysqli->real_escape_string($pawprint);
			$descriptionEscaped = $this->mysqli->real_escape_string($description);

			$sql = "INSERT INTO requests (course_id, name, pawprint, description, dateCreated) VALUES ($course_idEscaped, '$nameEscaped', '$pawprintEscaped', '$descriptionEscaped', NOW())";

			if (! $result = $this->mysqli->query($sql)) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		public function updateRequestCompletionStatus($id, $status) {
			$this->error = "";

			$dateCompleted = 'null';
			if ($status == 'completed') {
				$dateCompleted = 'NOW()';
			}

			if (!$id) {
				$this->error = "No request was specified to change completion status.";
			} else {
				$idEscaped = $this->mysqli->real_escape_string($id);
				$sql = "UPDATE requests SET dateCompleted = $dateCompleted WHERE id = '$idEscaped'";
				if (! $result = $this->mysqli->query($sql) ) {
					$this->error = $this->mysqli->error;
				}
			}

			return $this->error;
		}

		public function updateRequest($data) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			$id = $data['id'];
			if (! $id) {
				$this->error = "No id specified for task to update.";
				return $this->error;
			}

			$course_id = $data['course_id'];
			if (! $course_id) {
				$this->error = "No course id found for request to update. A course id is required.";
				return $this->error;
			}

			$name = $data['name'];
			if (! $name) {
				$this->error = "No name found for request to update. A name is required.";
				return $this->error;
			}

			$pawprint = $data['pawprint'];
			if(! $pawprint) {
				$this->error = "No pawprint found for request to update. A pawprint is required.";
				return $this->error;
			}

			$description = $data['description'];

			$idEscaped = $this->mysqli->real_escape_string($id);
			$course_idEscaped = $this->mysqli->real_escape_string($course_id);
			$nameEscaped = $this->mysqli->real_escape_string($name);
			$pawprintEscaped = $this->mysqli->real_escape_string($pawprint);
			$descriptionEscaped = $this->mysqli->real_escape_string($description);

			$sql = "UPDATE requests SET course_id='$course_idEscaped', name='$nameEscaped', pawprint='$pawprintEscaped', description='$descriptionEscaped' WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}

		public function deleteRequest($id) {
			$this->error = '';

			if (! $this->mysqli) {
				$this->error = "No connection to database.";
				return $this->error;
			}

			if (! $id) {
				$this->error = "No id specified for task to delete.";
				return $this->error;
			}

			$idEscaped = $this->mysqli->real_escape_string($id);
			$sql = "DELETE FROM requests WHERE id = $idEscaped";
			if (! $result = $this->mysqli->query($sql) ) {
				$this->error = $this->mysqli->error;
			}

			return $this->error;
		}
	}
?>
