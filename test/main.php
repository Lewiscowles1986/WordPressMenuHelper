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
	 * @expectedException Exception
	 */
	public function testRegisterSettingsException() {
		$this->menuHelper->register_settings();
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testAddMenuException() {
		$this->menuHelper->add_menu();
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testSettingsPageException() {
		$this->menuHelper->settings_page();
	}

}
