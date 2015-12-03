<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['arena']['disable_site']    = FALSE;
$config['arena']['maintenance']     = FALSE;
$config['arena']['maintenance_msg'] = 'maintenance break!';
$config['arena']['allow_ips']       = array(
	'175.195.203.93'
);
$config['arena']['cdn']             = "//cdn.myarena.com";
$config['arena']['datadir']         = "/www/docs/data/";
$config['arena']['REVISION']        = "2015110901";
$config['apc_config_id']            = 'arena/config';
$config['cms']['cms_allowed']       = array(
	'175.195.203.93'
);
$config['cms']['realm']             = 'ARENA CMS';
$config['cms']['user']              = 'admin';
$config['cms']['pass']              = '2016';

// SET UP OVERRIDES
$override_file = str_replace('.php', '.override.php', __FILE__);
if (file_exists($override_file)) include($override_file);
