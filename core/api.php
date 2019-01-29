<?php
/**
 * Copyright © 2014-2018 Andrej Pavlovic. All rights reserved.
 *
 * This code may not be used, copied, modified, sold, or extended without written
 * permission from Andrej Pavlovic (andrej.pavlovic@pokret.org).
 */

class PokretModifyBugNoteDateAPI
{
	const PLUGIN_NAME = 'PokretModifyBugNoteDate';
	

	static function bugnote_date_submitted_update( $p_bugnote_id, $p_date_submitted ) {
		$c_bugnote_id = db_prepare_int( $p_bugnote_id );
		$t_bugnote_table = db_get_table( 'mantis_bugnote_table' );
	
		$query = "UPDATE $t_bugnote_table
		SET date_submitted=" . db_param() . "
		WHERE id=" . db_param();
		db_query_bound( $query, Array( $p_date_submitted, $c_bugnote_id ) );
	
		# db_query errors if there was a problem so:
		return true;
	}
	
	static function bugnote_set_date_submitted($p_bug_id, $p_bugnote_id, $p_date_submitted) {
		$t_old_date_submitted = bugnote_get_field($p_bugnote_id, 'date_submitted');
		
		if ( $t_old_date_submitted == $p_date_submitted ) {
			return true;
		}
		
		// update date submitted
		self::bugnote_date_submitted_update($p_bugnote_id, $p_date_submitted);
	
		$t_bug_id = bugnote_get_field( $p_bugnote_id, 'bug_id' );
	
		# updated the last_updated date
		bugnote_date_update( $p_bugnote_id );
		bug_update_date( $t_bug_id );
	
		# insert a new revision
		$t_user_id = auth_get_current_user_id();
		$t_normal_date_format = config_get( 'normal_date_format' );
		$revision_text = sprintf('%s: %s', lang_get( 'date_submitted'), date( $t_normal_date_format, $t_old_date_submitted));
		$t_revision_id = bug_revision_add( $t_bug_id, $t_user_id, REV_BUGNOTE, $revision_text, $p_bugnote_id );
	
		# log new bugnote
		history_log_event_special( $t_bug_id, BUGNOTE_UPDATED, bugnote_format_id( $p_bugnote_id ), $t_revision_id );
	
		return true;
	}
}
