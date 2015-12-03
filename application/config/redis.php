<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// function for loading redis object.
function redis($name = 'default')
{
    static $list;
    if (!isset($list)) $list = array();
    if (isset($list[$name])) return $list[$name];
    $servers = config_item('redis_servers');
    if (!isset($servers[$name])) throw new Exception("invalid redis server: $name");
    return $list[$name] = new \Predis\Client($servers[$name]['conn'], $servers[$name]['options']);
}

$config = array(
    'redis_servers' => array(
        'default' => array(
            'conn'    => 'tcp://127.0.0.1:6379',
            'options' => array('prefix' => 'arena:'),
        ),
    ),
);

// SET UP OVERRIDES
$override_file = str_replace('.php', '.override.php', __FILE__);
if (file_exists($override_file)) include($override_file);
