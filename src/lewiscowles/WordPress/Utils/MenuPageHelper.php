<?php

namespace lewiscowles\WordPress\Utils;

class MenuPageHelper {

	protected $_dir = '';
	protected $_path = '';
	protected $_data;

	public function __construct( $dir=__DIR__, $path=__FILE__ ) {
		$this->_dir = $dir;
		$this->_path = $path;
		$this->option_fields();
		\add_action( 'admin_init', array( $this, 'register_settings' ) );
		\add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	public function add_menu() {
		if( !is_array( $this->_data ) ) { throw new \Exception('There seems to be an issue with the format of our data'); return; }

		//create new top-level menu
		foreach( $this->_data as $optGroup ) {
			$this->build_menu( $optGroup );
		}
	}

	public function register_settings() {
		if( !is_array( $this->_data ) ) { throw new \Exception('There seems to be an issue with the format of our data'); return; }

		foreach( $this->_data as $optGroup ) {
			foreach($optGroup['options'] as $option) {
				\register_setting( $optGroup['group'], $option['name'] );
			}
		}
	}

	public function settings_page() {
		$page = isset($_GET['page']) ? strtolower( $_GET['page'] ) : 'default';
		if( !is_array( $this->_data ) ) { throw new \Exception('There seems to be an issue with the format of our data'); return; }
		if(isset($_GET['settings-updated'])) {
			if($_GET['settings-updated']) : ?>
				<div id="message" class="updated fade"><p><strong>Settings Saved!</strong></p></div>
    		<?php else: ?>
        		<div id="message" class="error"><p><strong>Failure Saving Changes!</strong></p></div>
    		<?php endif;
    	}
		$myOptions = $this->_data[ $page ];
		if( file_exists( $this->_dir.'/views/'.$page.'-page.php')) {
			include_once $this->_dir.'/views/'.$page.'-page.php';
		} else {
			include_once $this->_dir.'/views/settings-page.php';
		}
	}

	protected function menuIconURL( $string='' ) {
		if( ( stripos($string, '/') !== false ) ) {
			return \plugins_url( $string, $this->_path );
		} else {
			return $string; // URL
		}
		return null;
	}

	protected function build_menu( $optGroup ) {
		if( !is_array( $this->_data ) ) { throw new Exception('There seems to be an issue with the format of our data'); return; }

		$menu_title = ( isset( $optGroup['menu_title'] ) ? $optGroup['menu_title'] : $optGroup['page_title'] );
		$menu_img = ( isset( $optGroup['menuicon'] ) ? $this->menuIconURL( $optGroup['menuicon'] ) : null );

		if( !isset( $optGroup['menu_submenu'] ) ) {
			\add_menu_page(
				$optGroup['page_title'],
				$menu_title,
				$optGroup['role'],
				strtolower( $optGroup['name'] ),
				array( $this, 'settings_page' ),
				$menu_img,
				isset( $optGroup['priority'] ) ? $optGroup['priority'] : null
			);
		} else {
			\add_submenu_page(
				$optGroup['menu_submenu'],
				$optGroup['page_title'],
				$menu_title,
				$optGroup['role'],
				strtolower( $optGroup['name'] ),
				array( $this, 'settings_page' )
			);
		}
	}

	protected function option_fields() {
		$menuData = @file_get_contents( $this->_dir . '/data/menu.json' );
		if( strlen($menuData."") < 1 ) {
			$this->_data = json_decode( $menuData, true );
		} else {
			throw new Exception( "An issue loading the menu file '{$this->_dir}/data/menu.json'" );
		}
	}
}
