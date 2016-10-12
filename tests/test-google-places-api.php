<?php

class WPP_Google_places_api_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPP_Google_places_api') );
	}

	function test_class_access() {
		$this->assertTrue( wp_places()->google-places-api instanceof WPP_Google_places_api );
	}
}
