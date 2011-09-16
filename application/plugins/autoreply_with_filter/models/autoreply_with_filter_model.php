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
 * SMS_to_email_model Class
 *
 * @package		Kalkun
 * @subpackage	Plugin
 * @category	Models
 */
class Autoreply_with_filter_model extends Model {
	
	function Autoreply_with_filter_model()
	{
		parent::Model();
	}	
	
	function get_setting($uid)
	{
		$this->db->from('plugin_autoreply_with_filter');
		$this->db->where('id_user', $uid);
		
		return $this->db->get();
	}
	
	function save_setting()
	{
		$this->db->set('wordlist', $this->input->post('word_list'));
		$this->db->set('textreply', $this->input->post('text_reply'));
		$this->db->set('enable', $this->input->post('enable_plugins'));

		if ($this->input->post('mode')=='edit')
		{
			$this->db->where('id_user', $this->session->userdata('id_user'));
			$this->db->update('plugin_autoreply_with_filter');
		}
		else
		{
			$this->db->set('id_user', $this->session->userdata('id_user'));
			$this->db->insert('plugin_autoreply_with_filter');				
		}
	}
	
	function set_autoreply($data){
		$data['coding'] = 'default';
		$data['class'] = '1';
		//$data['dest'] = $sms->SenderNumber;
		//$data['message'] = $sms->TextDecoded;
		$data['date'] = date('Y-m-d H:i:s');
		$data['delivery_report'] = 'default';
		$data['uid'] = '1';	
		$data['wordlist'] = $this->get_data('wordlist');	
		$data['textreply'] = $this->get_data('textreply');	
		$this->send_messages_autoreply($data);
	}
	
	function get_data($option){
		$data = $this->get_setting(1);
		if($data->num_rows()==1){
			if($option == 'wordlist'){
				return explode(',',$data->row('wordlist'));
			}else{
				return $data->row('textreply');
			}
		}		
	}
	
	function send_messages_autoreply($data)
	{
		$CI =& get_instance();
		$CI->load->model('Message_model');
		// default values
    	$data = $CI->Message_model->_default(array('SenderID' => NULL, 'CreatorID' => '', 'validity' => '-1'), $data);
    	
        // check if wap msg
        if(isset($data['type']) AND $data['type']=='waplink') { $CI->Message_model->_send_wap_link($data); return ;} 
		
		$keyword_found = false;
		foreach($data['wordlist'] as $keyword){
			if(preg_match('/\b'.$keyword.'\b/i', $data['message'])){
				$keyword_found = true;break;
			}else{
				$keyword_found = false;
			}
		}
		$data['message'] = $data['textreply'];
        if($data['dest']!=NULL && $data['date']!=NULL && $data['message']!=NULL)
		{	
			// Check coding
			switch($data['coding'])
			{
				case 'default':
					$standar_length = 160;
					$data['coding'] = 'Default_No_Compression';
				break;
				
				case 'unicode':
					$standar_length = 70;
					$data['coding'] = 'Unicode_No_Compression';
				break;
			}

			// Check message's length
			$messagelength = $CI->Message_model->_get_message_length($data['message'], $data['coding']);			

			// Multipart message
			if($messagelength > $standar_length)
			{
				$UDH_length = 7;
				$multipart_length = $standar_length - $UDH_length; 
			
				// generate UDH
				$UDH = "050003";
				$UDH .= strtoupper(dechex(mt_rand(0, 255)));
				$data['UDH'] = $UDH;
						
				// split string
				$tmpmsg = $CI->Message_model->_get_message_multipart($data['message'], $data['coding'], $multipart_length);			
				
				// count part message
				$part = count($tmpmsg);
				if($part < 10) $part = '0'.$part;
				
				// insert first part to outbox and get last outbox ID
				$data['option'] = 'multipart';
				$data['message'] = $tmpmsg[0];
				$data['part'] = $part;
				$outboxid = $CI->Message_model->_send_message_route($data);
                $this->Kalkun_model->add_sms_used($data['uid']);	// FIXME
				
				// insert the rest part to Outbox Multipart
				for($i=1; $i<count($tmpmsg); $i++) 
				{
				    $CI->Message_model->_send_message_multipart($outboxid, $tmpmsg[$i], $i, $part, $data['coding'], $data['class'], $UDH);
                    $this->Kalkun_model->add_sms_used($data['uid']);		
                }
			}
			else 
			{
				$data['option'] = 'single';
				$CI->Message_model->_send_message_route($data);
				$this->Kalkun_model->add_sms_used($data['uid']); // FIXME		
			}	
		}
		else 
		{
			echo 'Parameter invalid';	
		}
	}
	
}

/* End of file sms_to_email_model.php */
/* Location: ./application/plugins/sms_to_email/models/sms_to_email_model.php */
