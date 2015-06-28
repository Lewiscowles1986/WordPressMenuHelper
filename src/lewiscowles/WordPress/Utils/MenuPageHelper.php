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
		\add_action( 'admin_menu', array( $this, 'add_menu' ), 99 );
	}
	
	public function get_page_name() {
		return isset($_GET['page']) ? strtolower( $_GET['page'] ) : 'default';
	}
	
	public function get_page_data( $page ) {
		return isset($this->_data[ $page ]) ? $this->_data[ $page ] : [];
	}

	public function add_menu() {
		if( !is_array( $this->_data ) ) { throw new \Exception('There seems to be an issue with the format of our data'); return; }

		//create new top-level menu
		foreach( $this->_data as $name => $optGroup ) {
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
	
	public function updated_view( array $data ) {
		if(isset($data['settings-updated'])) {
			if($data['settings-updated']) : ?>
				<div id="message" class="updated fade"><p><strong>Settings Saved!</strong></p></div>
    		<?php else: ?>
        		<div id="message" class="error"><p><strong>Failure Saving Changes!</strong></p></div>
    		<?php endif;
    		}
	}

	public function view_css() { ?>
	<link rel="stylesheet" href="<?php echo plugins_url('/css/settings-page.css', __FILE__); ?>" /> <?php
	}

	public function settings_page() {
		$page = $this->get_page_name();
		$this->updated_view( $_GET );
		$this->view_css();
		$this->render_page($page,$this->get_page_data( $page ));
	}
	
	public function render_page($page,$myOptions) {
		$specific_view = $this->_dir.'/views/'.$page.'-page.php';
		if( file_exists( $specific_view ) ) {
			include_once $specific_view;
		} else { // switched to library built-in view (using.inc to avoid coverage for view code)
			$built_in = __DIR__.'/views/settings-page.inc';
			if( file_exists($built_in) ) {
				include_once $built_in;
			} else {
				throw new \Exception('Built in View Removed, Don\'t modify Libraries... Extend them!');
			}
		}
	}

	public function parse_asset_url( $string='' ) {
		$protocol_terminator_pos = stripos($string, '//');
		if( ( $protocol_terminator_pos === false ) && ( stripos($string, 'dashicon') !== false ) ) { // not url or dashicon
			return \plugins_url( $string, $this->_path );
		} else { // dashicon or URL
			return $string; // URL
		}
		return null;
	}
	
	public function parse_menu_icon( &$data ) {
		return ( isset( $data['menuicon'] ) ? $this->parse_asset_url( $data['menuicon'] ) : null );
	}
	
	public function parse_menu_title( &$data ) {
		return ( isset( $data['menu_title'] ) ? $data['menu_title'] : $data['page_title'] );
	}
	
	public function parse_menu_priority( &$data ) {
		return ( isset( $data['priority'] ) ? max( intval( $data['priority'] ), 0) : 99 );
	}
	
	public function parse_menu_data( &$data ) {
		$out = [
			'title' => $this->parse_menu_title( $data ),
			'page_title' => $data['page_title'],
			'sub_menu' => $data['menu_submenu'],
			'role' => $data['role'],
			'group' => strtolower( $data['name'] ),
			'priority' => $this->parse_menu_priority( $data ),
			'icon' => $this->parse_menu_icon( $data )
		];
		return $out;
	}
	
	public function add_top_level_menu( &$data ) {
		\add_menu_page(
			$data['page_title'],
			$data['title'],
			$data['role'],
			$data['group'],
			array( $this, 'settings_page' ),
			$data['icon'],
			$data['priority']
		);
	}
	
	public function add_sub_menu( &$data ) {
		\add_submenu_page(
			$data['sub_menu'],
			$data['page_title'],
			$data['title'],
			$data['role'],
			$data['group'],
			array( $this, 'settings_page' )
		);
	}

	public function build_menu( $optGroup ) {
		$data = $this->parse_menu_data( $optGroup );
		if( !isset( $optGroup['menu_submenu'] ) ) {
			$this->add_top_level_menu( $data );
		} else {
			$this->add_sub_menu( $data );
		}
	}
	
	public function get_data() {
		return $this->_data;
	}
	
	public function parse_data( $data ) {
		return json_decode( $data, true );
	}
	
	public function data_file_path() {
		return $this->_dir . '/data/menu.json';
	}
	
	public function get_data_file() {
		$data_file_path = $this->data_file_path();
		if( file_exists( $data_file_path ) && (is_readable( $data_file_path ) ) ) {
			return file_get_contents( $data_file_path );
		} else {
			throw new \Exception( "An issue loading the menu file '{$data_file_path}'" );
		}
		return "";
	}

	public function option_fields() {
		$menuData = $this->get_data_file();
		if( strlen($menuData."") > 0 ) {
			$this->_data = $this->parse_data( $menuData );
		} else {
			trigger_error('Data file was malformed, falling back to empty array...', \E_USER_NOTICE );
			$this->_data = [];
		}
	}
}
