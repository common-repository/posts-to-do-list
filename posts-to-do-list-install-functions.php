<?php

class posts_to_do_list_install extends posts_to_do_list_core {

    //Called on activation. Creates table and option
    static function posts_to_do_list_do_install() {
        global $wpdb;

        //If working on a multisite blog
    	if ( function_exists( 'is_multisite' ) AND is_multisite() ) {

            //If it is a network activation run the activation function for each blog id
    		if ( isset( $_GET['networkwide'] ) AND ( $_GET['networkwide'] == 1 ) ) {
    			//Get all blog ids; foreach them and call the install procedure on each of them
    			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM ".$wpdb->blogs );

                foreach ( $blog_ids as $blog_id ) {
    				switch_to_blog( $blog_id );
    				self::posts_to_do_list_create_table();
                    self::posts_to_do_list_create_option();
    			}

                //Go back to the main blog and return - so that if not multisite or not network activation, run the procedure once
    			restore_current_blog();
    			return;
    		}
    	}

        self::posts_to_do_list_create_table();
        self::posts_to_do_list_create_option();
    }

    //Called when creating a new blog on multiste. If plugin was activated with a network-wide activation, activate and install it on the new blog too
    static function posts_to_do_list_new_blog_install( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
        global $wpdb;

    	if ( is_plugin_active_for_network( basename( __DIR__ ).'/posts-to-do-list.php' ) ) {
    		switch_to_blog( $blog_id );
    		self::posts_to_do_list_create_table();
            self::posts_to_do_list_create_option();
    		restore_current_blog();
    	}
    }

    static function posts_to_do_list_create_table() {
        global $wpdb;

        $wpdb->query( 'CREATE TABLE IF NOT EXISTS `'.parent::$posts_to_do_list_db_table.'` (
            `ID` int(10) NOT NULL AUTO_INCREMENT,
            `item_title` text NOT NULL,
            `item_url` text,
            `item_timestamp` int(15) NOT NULL,
            `item_keyword` text,
            `item_notes` text,
            `item_done` text,
            `item_author` int(15) NOT NULL DEFAULT "0",
            `item_priority` int(1) NOT NULL DEFAULT "4",
            `item_adder` int(15) NOT NULL,
            `user_note` text,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;' );
    }

    static function posts_to_do_list_create_option() {
        if( ! get_option( 'posts_to_do_list' ) ) {
            update_option( 'posts_to_do_list', array(
                'current_version'                           => parent::$newest_version,
                'items_per_page'                            => 12,
                'permission_new_item_add_roles'             => array(
                    'subscriber',
                    'contributor',
                    'author',
                    'editor',
                    'administrator'
                ),
                'permission_item_delete_roles'              => array(
                    'author',
                    'editor',
                    'administrator'
                ),
                'permission_item_unassign_roles'              => array(
                    'author',
                    'editor',
                    'administrator'
                ),
                'permission_users_can_see_others_items'     => 1,
				'permission_users_can_claim_others_items' 	=> 0,
				'permission_users_can_be_greedy'			=> 1,
                'publication_time_range'                    => 'month',
                'send_email_users_on_assignment'            => 1,
                'show_widget_non_logged_in'  		        => 0,
            ) );
            parent::posts_to_do_list_update_options_variable();
        }
    }
}
