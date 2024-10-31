<?php

//Uninstall must have been triggered by WordPress, otherwise exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit;

global $wpdb;

//NOTE THAT UNISTALLING THE PLUGIN DELETES ALL THE SAVED SETTINGS
function posts_to_do_list_uninstall_procedure() {
    global $wpdb;
    
    if( $wpdb->query( 'SHOW TABLES FROM '.$wpdb->dbname.' LIKE "'.$wpdb->prefix.'posts_to_do_list"' ) )
        $wpdb->query( 'DROP TABLE '.$wpdb->prefix.'posts_to_do_list' );
    
    if( get_option( 'posts_to_do_list' ) )
        delete_option( 'posts_to_do_list' );
}

//If working on a multisite blog
if( function_exists( 'is_multisite' ) AND is_multisite() ) {
    
	//Get all blog ids; foreach them and call the uninstall procedure on each of them
	$blog_ids = $wpdb->get_col( 'SELECT blog_id FROM '.$wpdb->blogs );
	
    //Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
    foreach( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
        posts_to_do_list_uninstall_procedure();
	}
    
    //Go back to the main blog and return - so that if not multisite or not network activation, run the procedure once
	restore_current_blog();
	return;
}
posts_to_do_list_uninstall_procedure();

?>