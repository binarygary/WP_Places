<?php

class WPP_Admin_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPP_Admin') );
	}

	function test_class_access() {
		$this->assertTrue( wp_places()->admin instanceof WPP_Admin );
	}
}
