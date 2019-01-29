<?php
/**
 * Copyright © 2014-2018 Andrej Pavlovic. All rights reserved.
 *
 * This code may not be used, copied, modified, sold, or extended without written
 * permission from Andrej Pavlovic (andrej.pavlovic@pokret.org).
 */

auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

layout_page_header( plugin_lang_get( 'title' ) );
layout_page_begin();

print_manage_menu( 'manage_plugin_page.php' );

?>


<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container">
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">
<fieldset>
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
    <h4 class="widget-title lighter">
        <i class="ace-icon fa fa-gear"></i>
        <?php echo plugin_lang_get( 'title' ) ?>
    </h4>
</div>

<?php echo form_security_field( 'plugin_' . PokretModifyBugNoteDateAPI::PLUGIN_NAME . '_config_edit' ) ?>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-condensed table-striped">


<tr>
<td class="category"><?php echo plugin_lang_get( 'date_threshold' ) ?></td>
<td>
	<select name="date_threshold"><?php print_enum_string_option_list( 'access_levels', plugin_config_get( 'date_threshold' ) ) ?></select>
</td>
</tr>


</table>
</div>
</div>
<div class="widget-toolbox padding-8 clearfix">
    <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'update_configuration' ) ?>" />
</div>
</div>
</div>
</fieldset>
</form>
</div>
</div>

<?php
layout_page_end();

