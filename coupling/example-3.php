<?php
	############################################################################
	# Bootstrap - load up classes and configs etc
	############################################################################
	function pr($var){
		echo '<pre>'; print_r($var); echo '</pre>';
	}

	/**
	 * the events class is loaded early in the request, the earlier its loaded
	 * the sooner it can be used by other classes to do stuff
	 */
	class EventClass {
		/**
		 * keep track of what classes have events
		 * @var array
		 */
		private $__classCache = array();

		/**
		 * cache of class objects
		 * @var <type>
		 */
		private $__classes = array();

		/**
		 * singletons :/
		 *
		 * @staticvar array $instance instance of this class
		 * @return EventClass
		 */
		static function getInstance(){
			static $instance = array();
			if (!$instance) {
				$instance[0] = new EventClass();
				$instance[0]->__getAvailableEvents();
			}
			
			return $instance[0];
		}

		/**
		 * the method that classes call to communicate with each other
		 *
		 * @param string $method the event being triggered
		 * @param array $data any data you are passing to the other class
		 */
		public function trigger($method = null, $data = array()){
			$_methodName = $this->__methodName($method);
			$return = array();
			foreach($this->getEventsToCall($method) as $class){
				$return[$class][] = call_user_func_array(array($this->__classes[$class], $_methodName), $data);
			}

			return $return;
		}

		/**
		 * figure out what classes there are with methods that are callable
		 */
		private function __getAvailableEvents(){
			$classes = get_declared_classes();
			foreach($classes as $class){
				$methods = get_class_methods($class);

				if(empty($methods)){
					continue;
				}

				foreach($methods as $method){
					if(substr($method, 0, 2) === 'on'){
						$this->__classCache[$class][] = $method;
					}
				}

				if(isset($this->__classCache[$class])){
					$this->__classes[$class] = new $class();
				}
			}
		}

		/**
		 * get a list of events that would be called for a given method passed in
		 * 
		 * @param string $method the name like someEvent or Class.someEvent
		 */
		public function getEventsToCall($method){
			$method = explode('.', $method);
			if(count($method) == 1){
				$classes = array_keys($this->__classCache);
				$method = $method[0];
			}
			else{
				$classes = array($method[0]);
				$method = $method[1];
			}

			$method = $this->__methodName($method);

			$return = array();
			foreach($classes as $class){
				if(isset($this->__classCache[$class]) && in_array($method, $this->__classCache[$class])){
					$return[$class] = $method;
				}
			}

			return array_keys($return);
		}

		/**
		 * convert the passed name to the actual event name
		 *
		 * @param string $method the passed name
		 * @return string the actual name
		 */
		private function __methodName($method){
			$method = explode('.', $method);
			if(count($method) == 1){
				$method = $method[0];
			}
			else{
				$method = $method[1];
			}

			return 'on' . ucfirst($method);
		}
	}


	/**
	 * base class
	 */
	class Controller {
		public function __construct() {
			$this->Event = EventClass::getInstance();
		}

		public function output($array){
			return sprintf('<ul><li>%s</li></ul>', implode('</li><li>', $array));
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
		 * here we trigger an event to any class that has a 'helloWorld' event
		 */
		public function view(){
			echo sprintf('<h1>%s - view</h1>', __CLASS__);

			echo '<h3>Everybody that has the helloWorld event</h3>';
			$return = array();
			foreach($this->Event->trigger('helloWorld') as $r){
				$return[] = current($r);
			}

			if(!empty($return)){
				echo $this->output($return);
			}
		}

		/**
		 * some events for this class
		 */
		public function onHelloWorld($data = null){
			return 'Blogs Controller Here';
		}
	}

	/**
	 * some user stuff
	 */
	class UsersController extends Controller {
		/**
		 * here we trigger an event directly to the BlogsController class
		 *
		 * and then to something that does not exist.
		 */
		public function view(){
			echo sprintf('<h1>%s - view</h1>', __CLASS__);

			echo '<h3>Only BlogsController when its around</h3>';
			$return = array();
			foreach($this->Event->trigger('BlogsController.helloWorld') as $r){
				$return[] = current($r);
			}

			if(!empty($return)){
				echo $this->output($return);
			}

			echo '<h3>OldController is not here any more</h3>';
			$return = array();
			foreach($this->Event->trigger('OldController.helloWorld') as $r){
				$return[] = current($r);
			}

			if(!empty($return)){
				echo $this->output($return);
			}
		}

		/**
		 * some events for this class
		 */
		public function onHelloWorld($data = null){
			return 'Users Controller Here';
		}
	}

	/**
	 * some cms stuff
	 */
	class CmsController extends Controller {
		public function classAndMethod(){
			echo sprintf('<h1>%s</h1>', __CLASS__);
			echo '<h3>Just checking what would happen if only BlogsController.helloWorld was called</h3>';
			pr($this->Event->getEventsToCall('BlogsController.helloWorld'));
		}

		public function methodOnly(){
			echo '<h3>Just checking what would happen if only helloWorld was called</h3>';
			pr($this->Event->getEventsToCall('helloWorld'));
		}

		public function madeUpClassAndMethod(){
			echo '<h3>Just checking what would happen if the event did not exist</h3>';
			pr($this->Event->getEventsToCall('madeUpName'));
		}

		public function madeUpMethod(){
			echo '<h3>Just checking what would happen if the class and event did not exist</h3>';
			pr($this->Event->getEventsToCall('MissingClass.madeUpName'));
		}

		/**
		 * some events for this class
		 */
		public function onHelloWorld($data = null){
			return 'Cms Controller Here';
		}
	}

	############################################################################
	# Dispacher - process the request and run it
	############################################################################

	/**
	 * show what happens with various calls
	 */
	$Cms = new CmsController();
	$Cms->classAndMethod();
	$Cms->methodOnly();
	$Cms->madeUpClassAndMethod();
	$Cms->madeUpMethod();

	/**
	 * do some actual calls
	 */
	$Blog = new BlogsController();
	$Blog->view();

	$User = new UsersController();
	$User->view();