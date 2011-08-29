<?php
	/**
	 * low cohesion example of a blog controller
	 */
	class BlogController extends AppController {
		public function add_post() {
			// ... code ...
		}

		public function edit_post($id = null) {
			// ... code ...
		}

		public function view_post($id = null) {
			// ... code ...
		}

		public function delete_post($id = null) {
			// ... code ...
		}

		/**
		 * low cohesion within the method as its doing more than one thing
		 */
		public function edit_tag($id = null) {
			if($id) {
				// ... code ...

				$this->Tag->edit($id, $this->data);

				// ... code ...
			}

			else {
				// ... code ...

				$this->Tag->create($this->data);

				// ... code ...
			}
		}

		public function delete_tag($id = null) {
			// ... code ...
		}

		public function list_subscribers() {
			// ... code ...
		}

		public function login() {
			// ... code ...
		}

		public function logout() {
			// ... code ...
		}
	}