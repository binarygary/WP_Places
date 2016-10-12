<?php

class WPP_Meta_boxes_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPP_Meta_boxes') );
	}

	function test_class_access() {
		$this->assertTrue( wp_places()->meta-boxes instanceof WPP_Meta_boxes );
	}
}
