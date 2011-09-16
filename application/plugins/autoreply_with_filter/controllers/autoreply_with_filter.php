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
		$this->load->model('Autoreply_with_filter_model', 'plugin_model');
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
			switch($this->input->post('filter_to')) 
			{
				// Phonebook
				case 'specific':
				$tmp_dest = explode(',', $this->input->post('personvalue'));
				foreach ($tmp_dest as $key => $tmp)
				{
					if (trim($tmp)!='')
					{
						list ($id, $type) = explode(':', $tmp);
						// Person
						if ($type=='c')
						{
							// Already sent, no need to send again
							if (in_array($id, $dest)) 
							{
								continue;	
							}
							$dest[] = $id;
						}
						// Group
						else
						{
							$param = array('option' => 'bygroup', 'group_id' => $id);
							foreach ($this->Phonebook_model->get_phonebook($param)->result() as $group)
							{
								// Already sent, no need to send again
								if (in_array($group->Number, $dest)) 
								{
									continue;	
								}
								$dest[] = $group->Number;
							}
						}
					}
				}
				break;
				case "":
				
				break;
			}
			$this->plugin_model->save_setting();
			redirect('plugin/autoreply_with_filter');
		}
	}
}
	
/* End of file sms_to_email.php */
/* Location: ./application/plugins/sms_to_email/controllers/sms_to_email.php */
