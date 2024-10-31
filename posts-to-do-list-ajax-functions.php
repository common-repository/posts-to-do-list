<?php

class posts_to_do_list_ajax_functions extends posts_to_do_list_core {

    //AJAX called as soon as something is put into the URL field of the new item form. It issues a request to the given URL and parses its source to find the <title> tag. Taken its value, it dies it
    static function posts_to_do_list_ajax_retrieve_title() {
        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_retrieve_title', 'nonce' );

        $remote_page_raw = wp_remote_request( $_REQUEST['new_item_url'] );

        //If remote request triggers an error, show it and exit
        if( is_wp_error( $remote_page_raw ) )
            die( "Error: ".$remote_page_raw->get_error_message() );

        //Retrieve page body and parse it for the page title
        $remote_page_body   = wp_remote_retrieve_body( $remote_page_raw );
        $remote_page_title  = preg_match( '#<title>(.*)</title>#i', $remote_page_body, $new_item_title );

        if( $remote_page_title == 0 )
            die( __( "Error: An error occurred while retrieving page title. You can still enter it yourself", 'posts-to-do-list' ) );

        die( ucfirst( trim( html_entity_decode( $new_item_title[1], ENT_QUOTES, 'UTF-8' ) ) ) );
    }

    //Retrieve users belonging to given user role
    static function posts_to_do_list_ajax_get_users_by_role() {
        check_ajax_referer( 'posts_to_do_list_ajax_get_users_by_role', 'nonce' );

        $args = array(
            'orderby' => 'display_name',
            'order' => 'ASC',
            'role' => $_REQUEST['user_role'],
            'count_total' => true,
            'fields' => array(
                'ID',
                'display_name'
            )
        );
        $args = apply_filters( 'ptdl_get_users_by_role_args', $args );

        $users_to_show = new WP_User_Query( $args );
        if( $users_to_show->get_total() == 0 )
            die( __( 'Error: No users found.', 'posts-to-do-list' ) );

        $html = '<option value="0" selected>Unassigned</option>';
        foreach( $users_to_show->results as $single )
            $html .= '<option value="'.$single->ID.'">'.$single->display_name.'</option>';

        die( $html );
    }

    //After an item is added, it returns its HTML by print_item to be shown. Only the timestamp and the item_id are missing from the js data, thus they are added here
    static function posts_to_do_list_ajax_print_item_after_adding() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_print_item_after_adding', 'nonce' );

        $ajax_item_data_array                   = new stdClass;
        $ajax_item_data_array->ID               = $wpdb->get_var( 'SELECT ID FROM '.self::$posts_to_do_list_db_table.' ORDER BY ID DESC LIMIT 0,1' );
        $ajax_item_data_array->item_timestamp   = time();

        foreach( $_REQUEST as $key => $value ) {
            $ajax_item_data_array->$key = $value;
        }

        echo posts_to_do_list_print_functions::posts_to_do_list_print_item( $ajax_item_data_array );
        exit;
    }

    //Marks an item as done, storing the marker and the time in which the action happened. If post was already marked as done, it clears the item_done field
    static function posts_to_do_list_ajax_mark_as_done() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_mark_as_done', 'nonce' );
        $_REQUEST['item_id'] = (int) $_REQUEST['item_id'];

        if( isset( $_REQUEST['checked'] ) AND $_REQUEST['checked'] == 'true' ) {
            $done_array = array(
                'marker'    => $_REQUEST['marker'],
                'date'      => time()
            );

            $update_data = array(
                'item_done' => serialize( $done_array )
            );

            $wpdb->update( self::$posts_to_do_list_db_table, $update_data, array( 'ID' => $_REQUEST['item_id'] ) );
        } else {
            $wpdb->query( 'UPDATE '.self::$posts_to_do_list_db_table.' SET item_done = NULL WHERE ID = '.$_REQUEST['item_id'] );
        }

        exit;
    }

    //Simply deletes an item
    static function posts_to_do_list_ajax_delete_item() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_delete_item', 'nonce' );

        $wpdb->query( 'DELETE FROM '.self::$posts_to_do_list_db_table.' WHERE ID = '.(int) $_REQUEST['item_id'] );
        exit;
    }

    //Assign an item to current user
    static function posts_to_do_list_ajax_i_ll_take_it() {
        global  $wpdb,
                $current_user;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_i_ll_take_it', 'nonce' );
        $_REQUEST['item_id'] = (int) $_REQUEST['item_id'];

        //PERMISSION CHECKS - Only for non-admins
        if( ! in_array( 'administrator', $current_user->roles ) ) {
            //Check user doesn't have pending assignment, if they're supposed to finish them before
            $cu_pending_items = $wpdb->get_var( 'SELECT ID FROM '.self::$posts_to_do_list_db_table.' WHERE item_author = '.$current_user->ID.' AND item_done IS NULL' );
            if( ! self::$posts_to_do_list_options['permission_users_can_be_greedy'] AND $cu_pending_items != NULL )
                die( __( 'Ooops! Don\'t get greedy, you still have an assignment pending!', 'posts-to-do-list' ) );

            //Check user is not claiming other people's posts, if they're not supposed to
            $item_author = $wpdb->get_var( 'SELECT item_author FROM '.self::$posts_to_do_list_db_table.' WHERE ID = '.$_REQUEST['item_id'] );
            if( ! self::$posts_to_do_list_options['permission_users_can_claim_others_items'] AND $item_author != 0 )
                die( __( 'Ooops! Looks like this post has already been taken!', 'posts-to-do-list' ) );
        }

        $wpdb->query( 'UPDATE '.self::$posts_to_do_list_db_table.' SET item_author = '.$current_user->ID.' WHERE ID = '.$_REQUEST['item_id'] );
        exit;
    }

    //Unassign an item fron current user
    static function posts_to_do_list_ajax_i_dont_want_it_anymore() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_i_dont_want_it_anymore', 'nonce' );
        $_REQUEST['item_id'] = (int) $_REQUEST['item_id'];

        $wpdb->query( 'UPDATE '.self::$posts_to_do_list_db_table.' SET item_author = 0 WHERE ID = '.$_REQUEST['item_id'] );
        exit;
    }

    //Perform a bit of cleaning of the given values, then add the new item
    static function posts_to_do_list_ajax_new_item_add() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_new_item_add', 'nonce' );

        $insert_data = array(
            'item_title'        => esc_html( stripslashes( trim( $_REQUEST['item_title'] ) ) ),
            'item_url'          => trim( $_REQUEST['item_url'] ),
            'item_timestamp'    => time(),
            'item_adder'        => (int) $_REQUEST['item_adder'],
            'item_keyword'      => esc_html( stripslashes( trim( $_REQUEST['item_keyword'] ) ) ),
            'item_notes'        => esc_html( stripslashes( trim( $_REQUEST['item_notes'] ) ) ),
            'item_author'       => (int) $_REQUEST['item_author'],
            'item_priority'     => (int) $_REQUEST['item_priority']
        );

        $wpdb->insert( parent::$posts_to_do_list_db_table, $insert_data );

        //If setting is enabled and post was assigned to some user, send notification email to them
        if( parent::$posts_to_do_list_options['send_email_users_on_assignment'] == 1 AND $insert_data['item_author'] != 0 ) {
            parent::posts_to_do_list_send_email_assignment( $insert_data );
        }

        exit;
    }

    //Save user note
    static function posts_to_do_list_ajax_save_user_note() {
        global $wpdb;

        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_save_user_note', 'nonce' );

        $wpdb->query( 'UPDATE '.self::$posts_to_do_list_db_table.' SET user_note = "'.esc_html( stripslashes( trim( $_REQUEST['note'] ) ) ).'" WHERE ID = '. (int) $_REQUEST['item_id'] );

        exit;
    }

    //AJAX alias for get_page
    static function posts_to_do_list_ajax_get_page() {
        //If request was really issued from the plugin
        check_ajax_referer( 'posts_to_do_list_ajax_get_page', 'nonce' );

        parent::posts_to_do_list_get_page( (int) $_REQUEST['posts_to_do_list_page'] );
        exit;
    }
}