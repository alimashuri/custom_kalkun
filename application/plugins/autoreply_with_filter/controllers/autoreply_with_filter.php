<?php
/**
 * Kalkun
 * An open source web based SMS Management
 *
 * @package		Kalkun
 * @author		Kalkun Dev Team
 * @license		http://kalkun.sourceforge.net/license.php
 * @link		http://kalkun.sourceforge.net
 */

// ------------------------------------------------------------------------

/**
 * SMS_to_email Class
 *
 * @package		Kalkun
 * @subpackage	Plugin
 * @category	Controllers
 */
include_once(APPPATH.'plugins/Plugin_Controller.php');

class Autoreply_with_filter extends Plugin_Controller {
	
	function Autoreply_with_filter()
	{
		parent::Plugin_Controller();		
		$this->load->model('Autoreply_with_filter_model', 'plugin_model','Phonebook_model');
	}
	
	function index()
	{
		$data['title'] = 'Autoreply with filter v.0.1 (alpha)';
		$data['main'] = 'index';
		$data['settings'] = $this->plugin_model->get_setting($this->session->userdata('id_user'));
		if ($data['settings']->num_rows()==1) $data['mode'] = 'edit';
		else $data['mode'] = 'add';
		$this->load->view('main/layout', $data);
	}
	
	function save()
	{
		if ($_POST)
		{
			$this->plugin_model->save_setting();
			redirect('plugin/autoreply_with_filter');
		}
	}
}
	
/* End of file sms_to_email.php */
/* Location: ./application/plugins/sms_to_email/controllers/sms_to_email.php */
