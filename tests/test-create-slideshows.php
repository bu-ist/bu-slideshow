<?php

class Test_BU_Slideshow_Create_Slideshows extends WP_UnitTestCase {

	function test_create_and_fetch_slideshow() {
		$expected_show_name = "Awesome Test Slideshow";

		$show = BU_Slideshow::create_slideshow( $expected_show_name );
		$this->assertTrue( $show instanceof BU_Slideshow_Instance );
		$this->assertEquals( $show->name, $expected_show_name );

		$fetched_show = BU_Slideshow::get_slideshow( $show->id );
		$this->assertEquals( $fetched_show->name, $expected_show_name );
	}
}

