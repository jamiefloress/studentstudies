<?php
	require('RequestsModel.php');
	require('RequestsViews.php');

	class RequestsController {
		private $model;
		private $views;

		private $orderBy = '';
		private $view = '';
		private $action = '';
		private $message = '';
		private $data = array();

		public function __construct() {
			$this->model = new RequestsModel();
			$this->views = new RequestsViews();

			$this->view = $_GET['view'] ? $_GET['view'] : 'requestlist';
			$this->action = $_POST['action'];
		}

		public function __destruct() {
			$this->model = null;
			$this->views = null;
		}

		public function run() {
			if ($error = $this->model->getError()) {
				print $views->errorView($error);
				exit;
			}

			$this->processOrderBy();

			switch($this->action) {
				case 'delete':
					$this->handleDelete();
					break;
				case 'set_completed':
					$this->handleSetCompletionStatus('completed');
					break;
				case 'set_not_completed':
					$this->handleSetCompletionStatus('not completed');
					break;
				case 'add':
					$this->handleAddRequest();
					break;
				case 'edit':
					$this->handleEditRequest();
					break;
				case 'update':
					$this->handleUpdateRequest();
					break;
			}

			switch($this->view) {
				case 'requestform':
					print $this->views->requestFormView($this->data, $this->message);
					break;
				default: // 'requestlist'
					list($orderBy, $orderDirection) = $this->model->getOrdering();
					list($requests, $error) = $this->model->getRequests();
					if ($error) {
						$this->message = $error;
					}
					print $this->views->requestListView($requests, $orderBy, $orderDirection, $this->message);
			}

		}

		private function processOrderby() {
			if ($_GET['orderby']) {
				$this->model->toggleOrder($_GET['orderby']);
			}
		}

		private function handleDelete() {
			if ($error = $this->model->deleteRequest($_POST['id'])) {
				$this->message = $error;
			}
			$this->view = 'requestlist';
		}

		private function handleSetCompletionStatus($status) {
			if ($error = $this->model->updateRequestCompletionStatus($_POST['id'], $status)) {
				$this->message = $error;
			}
			$this->view = 'requestlist';
		}

		private function handleAddRequest() {
			if ($_POST['cancel']) {
				$this->view = 'requestlist';
				return;
			}

			$error = $this->model->addRequest($_POST);
			if ($error) {
				$this->message = $error;
				$this->view = 'requestform';
				$this->data = $_POST;
			}
		}

		private function handleEditRequest() {
			list($request, $error) = $this->model->getRequest($_POST['id']);
			if ($error) {
				$this->message = $error;
				$this->view = 'requestlist';
				return;
			}
			$this->data = $request;
			$this->view = 'requestform';
		}

		private function handleUpdateRequest() {
			if ($_POST['cancel']) {
				$this->view = 'requestlist';
				return;
			}

			if ($error = $this->model->updateRequest($_POST)) {
				$this->message = $error;
				$this->view = 'requestform';
				$this->data = $_POST;
				return;
			}

			$this->view = 'requestlist';
		}
	}
?>
