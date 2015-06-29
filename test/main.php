<?php

use lewiscowles\WordPress\Utils\MenuPageHelper;

class MainTest extends \PHPUnit_Framework_TestCase
{
	protected $menuHelper;
	

	public function setUp()
	{
		$dir = __DIR__;
		$path = __FILE__;
		$this->menuHelper = new MenuPageHelper( $dir, $path );
	}

	public function testConstruct() {
		$menuHelperInst = new MenuPageHelper(__DIR__,__FILE__);
		$this->assertTrue( $menuHelperInst instanceof  MenuPageHelper );
		$this->assertTrue( $this->menuHelper instanceof  MenuPageHelper );
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testRegisterSettingsException() {
		$menuHelperInst = new MenuPageHelper();
		$menuHelperInst->register_settings();
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testAddMenuException() {
		$menuHelperInst = new MenuPageHelper();
		$menuHelperInst->add_menu();
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testSettingsPageException() {
		$menuHelperInst = new MenuPageHelper();
		$menuHelperInst->settings_page();
	}
	
	public function testDataPathExist() {
		$this->assertTrue( file_exists( $this->menuHelper->data_file_path() ) );
	}
	
	public function testDataPathFormat() {
		$this->assertContains( __DIR__, $this->menuHelper->data_file_path() );
	}
	
	public function testDataParseWellFormed() {
		$this->assertTrue( is_array( $this->menuHelper->parse_data('[]') ) );
	}
	
	public function testDataMalFormed() {
		$this->assertFalse( $this->menuHelper->parse_data( '{bpb:[],}' ) === [] );
		$this->assertFalse( $this->menuHelper->parse_data( null ) === [] );
		$this->assertFalse( $this->menuHelper->parse_data( 12345 ) === [] );
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testGetDataFileNotExist() {
		$menuHelperInst = new MenuPageHelper( ( '/notexist/'.time() ) , md5( time() ) );
		$menuHelperInst->get_data_file();
	}
	
	public function testGetDataFileExist() {
		$this->menuHelper->get_data_file();
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testGetOptionFieldsInvalid() {
		$menuHelperInst = new MenuPageHelper( ( '/notexist/'.time() ) , md5( time() ) );
		$menuHelperInst->option_fields();
	}
	
	public function testGetOptionFields() {
		$this->menuHelper->option_fields();
	}
	
	public function testGetPageData() {
		$this->assertTrue( is_array( $this->menuHelper->get_page_data( 'nonexistant' ) ) );
	}
	
	public function testGetPage() {
		$this->assertEquals( $this->menuHelper->get_page_name(), 'default' );
		$_GET['page'] = 'bob';
		$this->assertEquals( $this->menuHelper->get_page_name(), 'bob' );
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function testGetData() {
		$menuHelperInst = new MenuPageHelper( ( '/notexist/'.time() ) , md5( time() ) );
		$this->assertEquals( $menuHelperInst->get_data(), [] );
	}
	
	public function testDashIconAsset() {
		$this->assertEquals( $this->menuHelper->parse_asset_url('dashicons dashicons-format-quote'), 'dashicons dashicons-format-quote' );
	}
	
	public function testURLIconAsset() {
		$this->assertEquals( $this->menuHelper->parse_asset_url('//google.co.uk/bob.jpg'), '//google.co.uk/bob.jpg' );
		$this->assertEquals( $this->menuHelper->parse_asset_url('http://google.co.uk/bob.jpg'), 'http://google.co.uk/bob.jpg' );
		$this->assertEquals( $this->menuHelper->parse_asset_url('https://google.co.uk/bob.jpg'), 'https://google.co.uk/bob.jpg' );
	}
	
	public function testLocalIconAsset() {
		$this->assertTrue( $this->menuHelper->parse_asset_url('/google.co.uk/bob.jpg') !== '//google.co.uk/bob.jpg' );
	}
        
        public function testMenuIconNull() {
        	$payload = array('bob'=>'dylan');
        	$this->assertTrue( is_null( $this->menuHelper->parse_menu_icon( $payload ) ) );
        }
        
        public function testMenuIconExists() {
        	
        	$payload = array('menuicon'=>'dashicons dashicons-format-quote');
        	$this->assertTrue( !is_null( $this->menuHelper->parse_menu_icon( $payload ) ) );
        }

	public function testParsingMenuTitle() {
		$payload = array('menu_title'=>'munchkin');
		$this->assertTrue( $this->menuHelper->parse_menu_title( $payload ) == 'munchkin' );
		$payload = array('page_title'=>'bob');
		$this->assertTrue( $this->menuHelper->parse_menu_title( $payload ) == 'bob' );
		$payload = array('page_title'=>'bob','menu_title'=>'pappus');
		$this->assertTrue( $this->menuHelper->parse_menu_title( $payload ) == 'pappus' );
        }

	public function testParseMenuPriority() {
		$payload = array('priority'=>50);
		$this->assertTrue( $this->menuHelper->parse_menu_priority( $payload ) == 50 );
		$payload = array('priority'=>'jim');
		$this->assertTrue( $this->menuHelper->parse_menu_priority( $payload ) == 0 );
		$payload = array('foglight'=>50);
		$this->assertTrue( $this->menuHelper->parse_menu_priority( $payload ) == 99 );
        }

	public function testMenuPage() {
		$payload = array(
			"title" => "Test", 
			"page_title" => "settings",  
			"sub_menu" => "settings", 
			"role" => "administrator", 
			"group" => "uniq-string", 
			"priority" => 5, 
			"icon" => "dashicons dashicons-format-quote" 
		);
		$this->expectOutputRegex( "/WordPress add_menu_page/" );
		$this->menuHelper->add_top_level_menu( $payload );
	}

	public function testSubMenuPage() {
		$payload = array(
			"title" => "Test", 
			"page_title" => "settings",  
			"sub_menu" => "settings", 
			"role" => "administrator", 
			"group" => "uniq-string", 
			"priority" => 5, 
			"icon" => "dashicons dashicons-format-quote" 
		);
		$this->expectOutputRegex( "/WordPress add_submenu_page/" );
		$this->menuHelper->add_sub_menu( $payload );
	}
	
	public function testParseMenuData() {
		$payload = array(
			"name" => "uniq-advanced-settings",
			"menu_submenu" => "settings",
			"menu_title" => "Test",
			"page_title" => "settings",
			"role" => "administrator"
		);
		$result = $this->menuHelper->parse_menu_data( $payload );
		$this->assertTrue( is_array( $result ) );
		$this->assertTrue( isset($result['title'] ) );
		$this->assertTrue( isset($result['group'] ) );
	}
	
	public function testBuildMenu() {
		$payload = array(
			"name" => "uniq-advanced-settings",
			"menu_submenu" => "settings",
			"menu_title" => "Test",
			"page_title" => "settings",
			"role" => "administrator"
		);
		$this->menuHelper->build_menu( $payload );
		$this->expectOutputRegex( "/WordPress add_submenu_page/" );
		
	}
	
	public function testUpdateViewUpdated() {
		$this->menuHelper->updated_view( array( 'settings-updated' => true ) );
		$this->expectOutputRegex( "/Settings Saved!/" );
	}
	
	public function testUpdateViewSettingsPage() {
		$this->menuHelper->settings_page();
		$this->expectOutputRegex( "/rel=[\"]stylesheet[\"]/" );
	}
	
	public function testRegisterSettings() {
		$this->menuHelper->register_settings();
		$this->assertTrue( true );
	}
	
	public function testAddMenu() {
		$this->menuHelper->add_menu();
		$this->assertTrue( true );
	}
	
	public function testGetDataReturnFormat() {
		$data = $this->menuHelper->get_data();
		$this->assertTrue( is_array( $data) );
	}
	
	public function testBigFace() {
		$menuHelperInst = new MenuPageHelper(__DIR__.'/sample',__FILE__);
		$_GET['page'] = 'cd2_test_main_level';
		$menuHelperInst->register_settings();
		$menuHelperInst->add_menu();
		$this->assertTrue( true );
	}

}
