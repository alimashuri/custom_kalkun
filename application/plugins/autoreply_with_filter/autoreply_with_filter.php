<?php
/**
* Plugin Name: Sms Autoreply With Filter
* Plugin URI: http://mashuri.web.id/kalkun
* Version: 0.1 alpha
* Description: Sms Autoreply With Filter
* Author: Ali Mashuri
* Author URI: http://mashuri.web.id
*/

function autoreply_with_filter_initialize()
{
	$CI =& get_instance();
	$config['css_plugin'] = $CI->config->item('plugins_path').'autoreply_with_filter/views/';
	//$config['css_plugin'] = 'autoreply_with_filter/views/';
	return $config;
}

// Add hook for incoming message
add_action("message.incoming.before", "autoreply_with_filter", 13);
/**
* Function called when plugin first activated
* Utility function must be prefixed with the plugin name
* followed by an underscore.
* 
* Format: pluginname_activate
* 
*/
function autoreply_with_filter_activate()
{
    return true;
}

/**
* Function called when plugin deactivated
* Utility function must be prefixed with the plugin name
* followed by an underscore.
* 
* Format: pluginname_deactivate
* 
*/
function autoreply_with_filter_deactivate()
{
    return true;
}

/**
* Function called when plugin first installed into the database
* Utility function must be prefixed with the plugin name
* followed by an underscore.
* 
* Format: pluginname_install
* 
*/
function autoreply_with_filter_install()
{    
	$CI =& get_instance();
	
	// check if table already exist
	if (!$CI->db->table_exists('plugin_autoreply_with_filter'))
	{
		$db_driver = $CI->db->platform();
		$db_prop = get_database_property($db_driver);
		execute_sql(APPPATH."plugins/autoreply_with_filter/media/".$db_prop['file']."_autoreply_with_filter.sql");
	}	
    return true;
}


function autoreply_with_filter($sms)
{
	//$config = autoreply_with_filter_initialize();
    $CI =& get_instance();
    $CI->load->model('autoreply_with_filter/Autoreply_with_filter_model');
    $data = $CI->Autoreply_with_filter_model->get_setting(1);
	if($data->num_rows()==1){
		if($data->row('enable') == 'true' or $data->row('enable') == true) {
			$CI->Autoreply_with_filter_model->set_autoreply($sms);
		}
	}
   
}
