<?php

class WPP_Place_Data_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPP_Place_Data') );
	}

	function test_class_access() {
		$this->assertTrue( wp_places()->place-data instanceof WPP_Place_Data );
	}
}
