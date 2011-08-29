<?php
	/**
	 * better cohesion example of a posts, tags and users controller
	 */

	class UsersController extends AppController {
		public function add() {
			// ... code ...
		}

		public function edit($id = null) {
			// ... code ...
		}

		public function view($id = null) {
			// ... code ...
		}

		public function delete($id = null) {
			// ... code ...
		}
	}

	class AccessController extends AppController {
		public function login() {
			// ... code ...
		}

		public function logout() {
			// ... code ...
		}

		public function register() {
			// ... code ...
		}

		public function forgot_username() {
			// ... code ...
		}

		public function forgot_password() {
			// ... code ...
		}
	}