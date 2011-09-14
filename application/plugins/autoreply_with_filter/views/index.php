<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('plugins_path');?>autoreply_with_filter/views/index.css">
<div id="window_container">
<div id="window_title"><?php echo $title; ?></div>
<div id="window_content">
<?php echo form_open('plugin/autoreply_with_filter/save', array('id' => 'settingsForm'));?>
<table width="100%" cellpadding="5">
<tr valign="top">
	<td width="175px"><?php echo lang('plugin_enable_autoreply_filter'); ?></td>
	<td>
	<?php 
	$email_forward = array('true' => lang('tni_yes'), 'false' => lang('tni_no'));
	if($settings->num_rows()==1)
	{
		$email_forward_act = $settings->row('enable'); 
	}
	else
	{
		$email_forward_act = 'false';	
	}
	echo form_dropdown('enable_plugins', $email_forward, $email_forward_act);  
	?>
	</td>
</tr>

<tr valign="top">
<td><?php echo lang('plugin_word_list'); ?></td>
<td>
<input type="text" name="word_list" class="email" value="<?php if($settings->num_rows()==1) echo $settings->row('wordlist');?>" /><span><?php echo lang('plugin_atention'); ?></span></td>
</tr>

<tr valign="top">
<td><?php echo lang('plugin_text_reply'); ?></td>
<td>
	<textarea class="word_count" id="text_reply" cols="50" rows="5" name="text_reply"><?php if($settings->num_rows()==1) echo $settings->row('textreply');?></textarea>
	<div style="float: left"><span class="counter">19 characters / 1 message(s)</span></div> 
</td>
</tr>
</table>
<br />
<input type="hidden" name="mode" value="<?php echo $mode;?>" /> 
<div align="center"><input type="submit" id="submit" value="<?php echo lang('kalkun_save'); ?>" /></div>
<?php echo form_close();?>

		<div class="clear"><small> 
		<strong>Version:</strong> 0.1 alpha&nbsp;&nbsp;
		<strong>Author:</strong> <a href="http://mashuri.web.id">Ali Mashuri</a>		</small></div> 

<script type="text/javascript">
$(document).ready(function() {
	$('.word_count').each(function(){   
	var length = $(this).val().length;  
	var message = Math.ceil(length/sms_char);
	$(this).parent().find('.counter').html( length + ' characters / ' + message + ' message(s)');  
		$(this).keyup(function(){  
			var new_length = $(this).val().length;  
			var message = Math.ceil(new_length/sms_char);
			 $(this).parent().find('.counter').html( new_length + ' characters / ' + message + ' message(s)');  
		});  
	});
});
</script>

</div>
