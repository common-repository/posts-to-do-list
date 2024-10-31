<?php

class posts_to_do_list_print_functions extends posts_to_do_list_core {

	static function posts_to_do_list_print_new_item_form() {
		global $wpdb, $wp_roles; ?>
<div style="text-align: center;"><a href="#" title="<?php _e( 'Add new entry', 'posts-to-do-list' ); ?>" id="posts_to_do_list_new"><?php _e( 'Add new post', 'posts-to-do-list' ); ?></a></div>
<div id="posts_to_do_list_new_content" style="display: none;">
	<br />
	<div id="new_item_form">
		<form method="post" id="new_item_form">
			<label for="new_item_URL" style="font-weight: bold; font-size: bigger;"><?php _e( 'URL', 'posts-to-do-list' ); ?></label><br />
			<input type="text" name="new_item_URL" id="new_item_URL" style="margin-bottom: 10px; width: 250px;" />
			<br />
			<label for="new_item_title" style="font-weight: bold; font-size: 1em;" id="new_item_title_label"><?php _e( 'Title (mandatory)', 'posts-to-do-list' ); ?></label><br />
			<input type="text" name="new_item_title" id="new_item_title" style="margin-bottom: 10px; width: 250px;" />
			<br />
			<label for="new_item_keyword" style="font-weight: bold; font-size: bigger; margin-bottom: 50px;"><?php _e( 'Keyword', 'posts-to-do-list' ); ?></label><br />
			<input type="text" name="new_item_keyword" id="new_item_keyword" style="margin-bottom: 10px; width: 250px;" />
			<br />
			<label for="new_item_notes" style="font-weight: bold; font-size: bigger;"><?php _e( 'Notes', 'posts-to-do-list' ); ?></label><br />
			<textarea rows="2" name="new_item_notes" id="new_item_notes" style="margin-bottom: 10px; width: 250px;" /></textarea>
			<br />
			<div style="margin-bottom: 5px;">
				<label for="posts_to_do_list_author" style="font-weight: bold; font-size: bigger;"><?php _e( 'Assign post to specific user', 'posts-to-do-list' ); ?></label><br />
				Role <select id="posts_to_do_list_roles" style="margin-top: 10px;">
					<option value="0"><?php _e( 'Any', 'posts-to-do-list' ); ?></option>

					<?php
					foreach( $wp_roles->role_names as $role => $role_name )
						echo '<option value="'.$role.'">'.$role_name.'</option>';
					?>

				</select>
			</div>

			<?php _e( 'User', 'posts-to-do-list' ); ?> <select id="posts_to_do_list_author">
				<option value="0"><?php _e( 'Unassigned', 'posts-to-do-list' ); ?></option>

		<?php $all_users = $wpdb->get_results( 'SELECT ID, display_name FROM '.$wpdb->users.' ORDER BY display_name ASC' );
		foreach( $all_users as $single ) {
			echo '<option value="'.$single->ID.'">'.stripslashes( $single->display_name ).'</option>';
		} ?>

			</select>
			<br />
			<br />
			<label for="item_priority" style="font-weight: bold; font-size: bigger;"><?php _e( 'Set priority', 'posts-to-do-list' ); ?></label><br />
			<select id="item_priority">
				<option value="7"><?php _e( 'A matter of life or death', 'posts-to-do-list' ); ?></option>
				<option value="6"><?php _e( 'Highest', 'posts-to-do-list' ); ?></option>
				<option value="5"><?php _e( 'High', 'posts-to-do-list' ); ?></option>
				<option value="4" selected="selected"><?php _e( 'Normal', 'posts-to-do-list' ); ?></option>
				<option value="3"><?php _e( 'Low', 'posts-to-do-list' ); ?></option>
				<option value="2"><?php _e( 'Lowest', 'posts-to-do-list' ); ?></option>
				<option value="1"><?php _e( 'Lower than hell', 'posts-to-do-list' ); ?></option>
			</select>
			<br />
			<div id="new_item_loading" style="display: none; float: right;"><img src="<?php echo parent::$posts_to_do_list_ajax_loader; ?>" alt="<?php _e( 'Loading...', 'posts-to-do-list' ); ?>" title="<?php _e( 'Loading...', 'posts-to-do-list' ); ?>" /></div>
			<p style="float: left; color: red;" id="new_item_error"></p>
			<span style="float: right;"><input type="submit" name="posts_to_do_list_new_submit" id="new_item_submit" value="<?php _e( 'Add', 'posts-to-do-list' ); ?>" class="button-secondary" disabled="disabled" style="opacity: 0.50;" /></span>
		</form>
	</div>
</div>
	<?php }

	//After page data has been got and sorted, it is printed. If no rows are available, an information paragraph is shown
	static function posts_to_do_list_print_page( $selected_data ) {

		if( count( $selected_data ) == 0 ) {
			echo '<p id="posts_to_do_list_no_posts_available">'.__( 'No posts available. Add a new item!', 'posts-to-do-list' ).'</p>';
		} else {

			foreach( $selected_data as $single ) {
				posts_to_do_list_print_functions::posts_to_do_list_print_item( $single );
			}
		}
	}

	//Prints each single item
	static function posts_to_do_list_print_item( $single ) {
		global $current_user;

		if( $current_user->ID != 0 )
			$userdata = get_userdata( $current_user->ID );

		$item_title         = esc_html( stripslashes( $single->item_title ) );
		$item_title_display = $item_title;
		$item_title_style   = ' style="text-decoration: none;"'; //This is the default style
		$item_url           = esc_html( stripslashes( $single->item_url ) );
		$item_date_added    = date( 'd/m/Y', $single->item_timestamp );
		$item_adder         = get_userdata( $single->item_adder )->display_name;
		$item_keyword       = esc_html( stripslashes( $single->item_keyword ) );
		$item_notes         = do_shortcode( nl2br( esc_html( stripslashes( $single->item_notes ) ) ) );
		$item_author        = __( 'Unassigned', 'posts-to-do-list' ); //This is the default assigment
		$item_priority      = parent::posts_to_do_list_get_textual_priority( $single->item_priority );
		$item_done_details  = @unserialize( $single->item_done );
		$item_user_note		= esc_html( $single->user_note );

		//If post was assigned to someone, fetch their display name
		if( $single->item_author != 0 )
			$item_author = get_userdata( $single->item_author )->display_name;

		$item_title_style = '';

		//If post has already been marked as done, check the related checkbox and strike the title
		if( is_array( $item_done_details) ) {
			$done_checked = ' checked="checked"';
			$item_title_style .= 'text-decoration: line-through;';
		}

		//If item is assigned to current user, highlight that
		if( $current_user->ID == $single->item_author AND $current_user->ID != 0 )
			$item_title_display = '* '.$item_title;
		else if( $item_author != __( 'Unassigned', 'posts-to-do-list' ) AND empty( $done_checked ) )
			$item_title_style .= 'color: red;';
		?>

		<div class="item_to_do" style="margin-bottom: 5px;">
			<strong><a class="item_to_do_link" href="<?php echo $item_url; ?>" title="<?php echo $item_title; ?>" style="<?php echo $item_title_style; ?>"><?php echo $item_title_display; ?></a></strong>
			<div class="item_to_do_content" style="display: none; margin-left: 10px;">
				<div class="content_left" style="margin-top: 2px;">
					<strong><?php _e( 'Added on', 'posts-to-do-list' ); ?></strong> <?php echo $item_date_added; ?> (<?php echo $item_adder; ?>)<br />
					<strong><?php _e( 'Assigned to', 'posts-to-do-list' ); ?></strong>: <span class="assigned"><?php echo $item_author; ?></span><br />
					<strong><?php _e( 'Priority', 'posts-to-do-list' ); ?></strong>: <?php echo $item_priority; ?><br />
					<strong><?php _e( 'Keyword', 'posts-to-do-list' ); ?></strong>: <?php echo $item_keyword; ?><br />
					<strong><?php _e( 'Notes', 'posts-to-do-list' ); ?></strong>: <?php echo $item_notes; ?><br />

		<?php if( is_array( $item_done_details ) )
			echo '<p class="marked_as_done_p" style="font-style: italic;">'.__( 'Marked as done by', 'posts-to-do-list' ).' '.get_userdata( $item_done_details['marker'] )->display_name.' ('.date( 'Y-m-d', $item_done_details['date'] ).')</p>'; ?>

				</div>
				<div style="margin-top: 8px;">
					<textarea style="width: 98%;" rows="2" class="ptdl-user-note" name="ptdl-user-note" id="ptdl-user-note" placeholder="<?php _e( 'Leave a note', 'posts-to-do-list' ); ?>" rel="<?php echo $single->ID; ?>"><?php echo $item_user_note; ?></textarea>

					<div style="float: left; width: 50%;">

		<?php if( current_user_can( 'edit_posts' ) ) { ?>

						&rArr; &nbsp;<a href="<?php echo admin_url().'post-new.php?post_title='.$item_title; ?>"><?php _e( 'Write it!', 'posts-to-do-list' ); ?></a><br />

		<?php }

		if( strlen( $item_url ) > 0 ) { ?>

						&rArr; &nbsp;<a href="<?php echo $item_url; ?>" title="<?php _e( 'Go to source', 'posts-to-do-list' ); ?>" target="_blank"><?php _e( 'Go to source', 'posts-to-do-list' ); ?></a><br />

		<?php } ?>
					</div>

					<div style="float: right; width: 50%;">

		<?php if( $current_user->ID != 0 ) { ?>

						&rArr; &nbsp;<?php _e( 'Mark as done', 'posts-to-do-list' ); ?> <input type="checkbox" name="mark_as_done" class="mark_as_done" value="<?php echo $single->ID; ?>"<?php echo @$done_checked; ?> /><br />

		<?php }

		if( $current_user->ID != 0 AND $single->item_author != $current_user->ID ) { ?>

						&rArr; &nbsp;<a href="#" class="item_i_ll_take_it" title="<?php _e( 'If you plan to write this post, assign it to yourself so that other writers will not start writing it too', 'posts-to-do-list' ); ?>" rel="<?php echo $single->ID; ?>"><?php _e( 'Assign to me', 'posts-to-do-list' ); ?></a>

						<?php } else if( isset( $userdata ) AND $single->item_author == $current_user->ID AND array_intersect( parent::$posts_to_do_list_options['permission_item_unassign_roles'], $userdata->roles ) ) { ?>

						&rArr; &nbsp;<a href="#" class="item_i_dont_want_it_anymore" title="<?php _e( 'If you are not going to write this anymore.', 'posts-to-do-list' ); ?>" rel="<?php echo $single->ID; ?>"><?php _e( 'Unassign from me', 'posts-to-do-list' ); ?></a>

							<?php } ?>

					</div>
					<div style="clear: both;"></div>
					<div style="margin: 0 auto; width: 50%">

		<?php
		//If current user belong to a user role that can delete items, show link
		if( isset( $userdata ) AND array_intersect( parent::$posts_to_do_list_options['permission_item_delete_roles'], $userdata->roles ) ) { ?>

						&rArr; &nbsp;<a href="#" class="item_delete" rel="<?php echo $single->ID; ?>"><?php _e( 'Delete item', 'posts-to-do-list' ); ?></a>

		<?php } ?>

					</div>
				</div>
			</div>
			<div style="clear: both;"></div>
		</div>

	<?php }

	static function posts_to_do_list_print_detailed_stats( $detailed_stats_array ) {
		$n = 0;
		foreach( $detailed_stats_array as $single ) {

			if( $n % 2 == 0 )
				$row_alternate = ' class="alternate"';

			echo '<tr'.@$row_alternate.'>
			<td>'.$single['display_name'].'</td>
			<td>'.$single['added_items'].'</td>
			<td>'.$single['assigned_items'].'</td>
			<td>'.$single['total_marked_as_done_items'].'</td>
			<td>'.$single['assigned_marked_as_done_items'].'</td>
			<td>'.$single['still_to_do_items'].'</td>
			<td>'.$single['created_posts'].'</td>
			<td>'.$single['published_posts'].'</td>';

			unset( $row_alternate );
			++$n;
		}
	}

	static function posts_to_do_list_print_general_stats( $general_stats_array ) {
		echo '<tr>
			<td>'.$general_stats_array['added_items'].'</td>
			<td>'.$general_stats_array['marked_as_done_items'].'</td>
			<td>'.$general_stats_array['still_to_do_items'].'</td>
			<td>'.$general_stats_array['created_posts'].'</td>
			<td>'.$general_stats_array['published_posts'].'</td>';
	}

}
