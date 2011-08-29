<?php
	############################################################################
	# Bootstrap - load up classes and configs etc
	############################################################################
	function pr($var){
		echo '<pre>'; print_r($var); echo '</pre>';
	}

	/**
	 * base class
	 */
	class Controller {
		public $controllers = array('BlogsController', 'UsersController', 'OtherController');
		public function output($array){
			return sprintf('<ul><li>%s</li></ul>', implode('</li><li>', (array)$array));
		}
	}

	############################################################################
	# Your cool code (components / models etc
	############################################################################

	/**
	 * some blog stuff
	 */
	class BlogsController extends Controller {
		/**
		 * call the onHelloWorld method in all classes
		 */
		public function view(){
			echo sprintf('<h1>%s - view</h1>', __CLASS__);

			echo '<h3>calling methods in other classes</h3>';
			$return = array();
			foreach($this->controllers as $controller){
				if(class_exists($controller)){
					$Controller = new $controller();
					$return[] = $Controller->onHelloWorld('');
				}
			}

			if(!empty($return)){
				echo $this->output($return);
			}
		}

		public function onHelloWorld($data = null){
			return 'Blogs Controller Here';
		}
	}

	/**
	 * some user stuff
	 */
	class UsersController extends Controller {
		/**
		 * call the method only in the blogs controller
		 */
		public function view(){
			echo sprintf('<h1>%s - view</h1>', __CLASS__);

			echo '<h3>Only BlogsController when its around</h3>';

			$return = array();
			if(class_exists('BlogsController')){
				$Controller = new BlogsController();
				$return = $Controller->onHelloWorld('');
			}

			if(!empty($return)){
				echo $this->output($return);
			}
		}

		public function onHelloWorld($data = null){
			return 'Users Controller Here';
		}
	}

	/**
	 * some other controller
	 */
	class OtherController extends Controller {
		/**
		 * here we trigger an event to any class that has a 'helloWorld' event
		 */
		public function view(){
			echo sprintf('<h1>%s - view</h1>', __CLASS__);

			echo '<h3>nothing to see here, move along</h3>';
		}

		public function onHelloWorld($data = null){
			return 'Other Controller Here';
		}
	}

	############################################################################
	# Dispacher - process the request and run it
	############################################################################

	/**
	 * do some actual calls
	 */
	$Blog = new BlogsController();
	$Blog->view();

	$User = new UsersController();
	$User->view();