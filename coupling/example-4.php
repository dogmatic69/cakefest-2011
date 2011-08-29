<?php
	/**
	 * pesudo example
	 */
	class Events {
		public function trigger($class, $method) {
			// find classes that are callable

			// get data from each callable class

			// return data to calling method
		}
	}

	class Sitemap {
		public function output() {
			$data = Events::trigger($this, 'sitemap');
			echo '<sitemap>';
			foreach($data as $plugin => $rows) {
				echo sprintf('<%s>', $plugin);
				foreach($rows as $row) {
					echo sprintf('<row name="%s" created="%s" uuid="%s">', $row[$plugin]['name'], $row[$plugin]['created'], $row[$plugin]['id']);
				}
				echo sprintf('</%s>', $plugin);
			}
			echo '</sitemap>';
		}
	}

	class Post extends Model {
		public function sitemap() {
			return $this->find(
				'all',
				array(
					'limit' => 10,
					'order' => array(
						'Post.created' => 'desc'
					)
				)
			);
		}
	}

	class Product extends Model {
		public function sitemap() {
			return $this->find(
				'all',
				array(
					'limit' => 10,
					'order' => array(
						'Product.created' => 'desc'
					)
				)
			);
		}
	}


	/**
	 * from infinitas
	 */
	 class WebmasterController extends AppController {
		public function admin_rebuild(){
			$siteMaps = $this->Event->trigger('siteMapRebuild');
			$map = array();
			foreach($siteMaps['siteMapRebuild'] as $plugin){
				foreach($plugin as $link){
					if(!isset($link['url'])){
						continue;
					}

					$time = strtotime(isset($link['last_modified']) ? $link['last_modified'] : Configure::read('Webmaster.last_modified'));
					$lastModified = date('Y-m-d\Th:mP', $time);
					$changeFreq = isset($link['change_frequency']) ? $link['change_frequency'] : Configure::read('Webmaster.change_frequency');
					$priority = isset($link['priority']) ? $link['priority'] : Configure::read('Webmaster.priority');
					$map['urlset'][] = array(
						'url' => array(
							'loc' => $link['url'],
							'lastmod' => $lastModified,
							'changefreq' => $changeFreq,
							'priority' => $priority
						)
					);
				}
			}

			Cache::write('sitemap', $map, 'webmaster');

			return $map;
		}
	 }

	 /**
	  * view is simple
	  */
	?>
		<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
			<?php echo $this->Xml->serialize($map, array('format' => 'tags')); ?>
		</urlset>
	<?php

	/**
	 * some examples of plugins that have this implemented
	 */

	/**
	 * Loosely coupled site map generator
	 */
	class SiteMapsController extends WebmasterAppController{
		/**
		 * other code
		 */
		 
		public function admin_rebuild(){
			$siteMaps = $this->Event->trigger('siteMapRebuild');
			$map = array();
			foreach($siteMaps['siteMapRebuild'] as $plugin){
				foreach($plugin as $link){
					if(!isset($link['url'])){
						continue;
					}

					$time = strtotime(isset($link['last_modified']) ? $link['last_modified'] : Configure::read('Webmaster.last_modified'));
					$lastModified = date('Y-m-d\Th:mP', $time);
					$changeFreq = isset($link['change_frequency']) ? $link['change_frequency'] : Configure::read('Webmaster.change_frequency');
					$priority = isset($link['priority']) ? $link['priority'] : Configure::read('Webmaster.priority');
					$map['urlset'][] = array(
						'url' => array(
							'loc' => $link['url'],
							'lastmod' => $lastModified,
							'changefreq' => $changeFreq,
							'priority' => $priority
						)
					);
				}
			}

			Cache::write('sitemap', $map, 'webmaster');

			return $map;
		}

		/**
		 * other code
		 */
	}
	
	/**
	 * actual site maps
	 */
	final class ContactEvents extends AppEvents {
		/**
		 * other events
		 */

		public function onSiteMapRebuild($event){
			$Branch = ClassRegistry::init('Contact.Branch');
			$newest = $Branch->getNewestRow();
			$frequency = $Branch->Contact->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(
					array(
						'plugin' => 'contact',
						'controller' => 'branches',
						'action' => 'index',
						'admin' => false,
						'prefix' => false
					),
					true
				),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			foreach($Branch->find('list') as $branch){
				$return[] = array(
					'url' => Router::url(
						array(
							'plugin' => 'contact',
							'controller' => 'branches',
							'action' => 'view',
							'slug' => $branch,
							'admin' => false,
							'prefix' => false
						),
						true
					),
					'last_modified' => $newest,
					'change_frequency' => $frequency
				);
			}

			unset($Branch);

			return $return;
		}

		/**
		 * other events
		 */
	}

	final class CategoriesEvents extends AppEvents {
		/**
		 * other events
		 */

		public function onSiteMapRebuild($event){
			$Category = ClassRegistry::init('Categories.Category');
			$newest = $Category->getNewestRow();
			$frequency = $Category->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(
					array(
						'plugin' => 'categories',
						'controller' => 'categories',
						'action' => 'index',
						'admin' => false,
						'prefix' => false
					),
					true
				),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			foreach($Category->find('list') as $category){
				$return[] = array(
					'url' => Router::url(
						array(
							'plugin' => 'categories',
							'controller' => 'categories',
							'action' => 'view',
							'slug' => $category,
							'admin' => false,
							'prefix' => false
						),
						true
					),
					'last_modified' => $newest,
					'change_frequency' => $frequency
				);
			}

			return $return;
		}

		/**
		 * other events
		 */
	}
