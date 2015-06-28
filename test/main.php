<?php

use lewiscowles\WordPress\Utils\MenuPageHelper;

class MainTest extends \PHPUnit_Framework_TestCase
{
	protected $menuHelper;

	public function setUp()
	{
		$this->menuHelper = new MenuPageHelper();

	}

	public function testConstruct() {
		$menuHelperInst = new MenuPageHelper();
		$this->assertTrue( $menuHelperInst instanceof  MenuPageHelper );
		$this->assertTrue( $this->menuHelper instanceof  MenuPageHelper );
	}
	
	/**
	 * @expectedException \Exception
     	 * @expectedExceptionMessage There seems to be an issue with the format of our data
	 */
	public function testRegisterSettingsException() {
		$this->menuHelper->register_settings();
	}
	
	/**
	 * @expectedException \Exception
     	 * @expectedExceptionMessage There seems to be an issue with the format of our data
	 */
	public function testAddMenuException() {
		$this->menuHelper->add_menu();
	}
	
	/**
	 * @expectedException \Exception
     	 * @expectedExceptionMessage There seems to be an issue with the format of our data
	 */
	public function testSettingsPageException() {
		$this->menuHelper->settings_page();
	}
	
	public function testDataFilesExist() {
		$this->assertTrue( file_exists( $this->menuHelper->getDataFilePath() ) );
	}

}
