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
	
	public function testDataFilesExist() {
		$this->assertTrue( file_exists( $this->menuHelper->data_file_path() ) );
	}
	
	public function testDataFileFormat() {
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

}
