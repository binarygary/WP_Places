<?php

class WPP_Content_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPP_Content') );
	}

	function test_class_access() {
		$this->assertTrue( wp_places()->content instanceof WPP_Content );
	}
}
