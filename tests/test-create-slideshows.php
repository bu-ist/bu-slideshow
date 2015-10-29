<?php

class Test_BU_Slideshow_Create_Slideshows extends WP_UnitTestCase {

	function test_create_and_fetch_slideshow() {
		$expected_show_name = 'Awesome Test Slideshow';

		$show = BU_Slideshow::create_slideshow( $expected_show_name );
		$this->assertTrue( $show instanceof BU_Slideshow_Instance );
		$this->assertEquals( $show->name, $expected_show_name );

		$fetched_show = BU_Slideshow::get_slideshow( $show->id );
		$this->assertEquals( $fetched_show->name, $expected_show_name );
	}

	function test_minimum_required_caps(){
		$user = new WP_User( $this->factory->user->create() );
		$user->add_role( 'contributor' );
		wp_set_current_user( $user->ID );

		$slideshow_defaults = get_class_vars( 'BU_Slideshow' );

		$this->assertTrue( current_user_can( $slideshow_defaults['min_cap'] ) );
	}

	function test_admin_page_edit_slideshow_submission(){
		$show_name = 'Test Slideshow to Update';
		$expected_show_name_after_update = 'Updated Test Slideshow';

		// Create a user that can create slideshows
		$user = new WP_User( $this->factory->user->create() );
		$user->add_role( 'contributor' );
		wp_set_current_user( $user->ID );

		$show = BU_Slideshow::create_slideshow( $show_name );

		// Set POST vars to emulate form submission
		$_POST = array(
			'bu_slideshow_save_show'	=> 'save',
			'bu_slideshow_id' 		=> $show->id,
			'bu_slideshow_name' 		=> $expected_show_name_after_update,
			'bu_slideshow_nonce' 		=> wp_create_nonce( 'bu_update_slideshow' ),
			'bu_slideshow_height' 		=> '300',
			);

		ob_start();
		BU_Slideshow::edit_slideshow_page();
		$output = ob_get_contents();
		ob_end_clean();

		$updated_show = BU_Slideshow::get_slideshow( $show->id );

		$this->assertContains( 'Slideshow updated successfully', $output );
		$this->assertEquals( $updated_show->name, $expected_show_name_after_update );
	}
}
