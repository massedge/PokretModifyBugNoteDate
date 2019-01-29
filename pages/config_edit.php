<?php
/**
 * Copyright © 2014-2018 Andrej Pavlovic. All rights reserved.
 *
 * This code may not be used, copied, modified, sold, or extended without written
 * permission from Andrej Pavlovic (andrej.pavlovic@pokret.org).
 */

form_security_validate( 'plugin_' . PokretModifyBugNoteDateAPI::PLUGIN_NAME . '_config_edit' );
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

// Clean data
$f_date_threshold = gpc_get_int( 'date_threshold' );


// Store data
plugin_config_set( 'date_threshold', $f_date_threshold );

form_security_purge( 'plugin_' . PokretModifyBugNoteDateAPI::PLUGIN_NAME . '_config_edit' );

print_successful_redirect( plugin_page( 'config', true ) );
