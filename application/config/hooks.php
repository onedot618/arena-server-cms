<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// SET UP OVERRIDES
$override_file = str_replace('.php', '.override.php', __FILE__);
if( file_exists( $override_file ) ) include ( $override_file );
