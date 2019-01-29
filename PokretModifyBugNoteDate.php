<?php
/**
 * Copyright © 2014-2018 Andrej Pavlovic. All rights reserved.
 *
 * This code may not be used, copied, modified, sold, or extended without written
 * permission from Andrej Pavlovic (andrej.pavlovic@pokret.org).
 */

require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class PokretModifyBugNoteDatePlugin extends MantisPlugin
{
	function register()
	{
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page = 'config';

		$this->version = '1.1.1';
		$this->requires = array(
			'MantisCore' => '2.3.1',
		);

		$this->author = 'Andrej Pavlovic';
		$this->contact = 'andrej.pavlovic@pokret.org';
		$this->url = 'https://www.pokret.org/';
	}
	
	function init()
	{
		require_once( 'core/api.php' );
	}
	
	function config()
	{
		return array(
			'date_threshold' => MANAGER,
		);
	}
	
	function hooks()
	{
		return array(
			'EVENT_BUGNOTE_EDIT_FORM' => 'bugnote_edit_form',
			'EVENT_BUGNOTE_EDIT' => 'bugnote_edit',
		);
	}
	
	function bugnote_edit_form($p_event_name, $p_bug_id, $p_bugnote_id)
	{
		if (!$this->can_modify_bugnote_submitted_date($p_bug_id, $p_bugnote_id)) {
			return;
		}
		
		$t_date_submitted = bugnote_get_field( $p_bugnote_id, "date_submitted" );
		
		$date = date('Y-m-d-H-i-s', $t_date_submitted);
		$date = explode('-', $date);
		?>
			<tr class="row-2">
				<td class="center" colspan="2">
					<b><?php echo plugin_lang_get( 'date_submitted') ?></b><br />
					<select name="pokret_modify_date_submitted[]">
						<?php print_year_option_list( $date[0] ); ?>
					</select>
					
					<select name="pokret_modify_date_submitted[]">
						<?php print_month_option_list( $date[1] ); ?>
					</select>
					
					<select name="pokret_modify_date_submitted[]">
						<?php print_day_option_list( $date[2] ); ?>
					</select>
					
					<select name="pokret_modify_date_submitted[]">
						<?php for($i = 0; $i < 24; $i++): ?>
							<option value="<?php echo $i ?>" <?php if($i == $date[3]):?>selected="selected"<?php endif; ?>><?php echo $i?></option>
						<?php endfor; ?>
					</select>
					
					<select name="pokret_modify_date_submitted[]">
						<?php for($i = 0; $i < 60; $i++): ?>
							<option value="<?php echo $i ?>" <?php if($i == $date[4]):?>selected="selected"<?php endif; ?>><?php echo $i?></option>
						<?php endfor; ?>
					</select>
					
					<select name="pokret_modify_date_submitted[]">
						<?php for($i = 0; $i < 60; $i++): ?>
							<option value="<?php echo $i ?>" <?php if($i == $date[5]):?>selected="selected"<?php endif; ?>><?php echo $i?></option>
						<?php endfor; ?>
					</select>
				</td>
			</tr>
		<?php
	}

	function bugnote_edit($p_event_name, $p_bug_id, $p_bugnote_id)
	{
		if (!$this->can_modify_bugnote_submitted_date($p_bug_id, $p_bugnote_id)) {
			return;
		}
		
		$c_submitted_date = gpc_get_int_array('pokret_modify_date_submitted');
		
		$timestamp = mktime($c_submitted_date[3], $c_submitted_date[4], $c_submitted_date[5], $c_submitted_date[1], $c_submitted_date[2], $c_submitted_date[0]);
		
		PokretModifyBugNoteDateAPI::bugnote_set_date_submitted($p_bug_id, $p_bugnote_id, $timestamp);
	}
	
	private function can_modify_bugnote_submitted_date($p_bug_id, $p_bugnote_id) {
		$t_bugnote_type = bugnote_get_field($p_bugnote_id, 'note_type');
		
		// do not allow date change for reminder notes
		if ($t_bugnote_type == REMINDER)
			return false;
		
		// ensure user allowed to modify date
		if (!access_has_bug_level( plugin_config_get( 'date_threshold' ), $p_bug_id ))
			return false;
		
		return true;
	}
}
