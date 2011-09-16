<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('plugins_path');?>autoreply_with_filter/views/index.css">
<link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css_path');?>jquery-plugin/token-input-facebook.css" />
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-ui/ui.datepicker.min.js"></script>
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.min.js"></script>
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.tokeninput.min.js"></script>
<style type="text/css">
.ui-datepicker {z-index:10100;}
.left_aligned { margin-left:0; padding-left:0;}
.form_option { width: 100px;}
</style>
<?php //$this->load->view('js_init/message/js_compose'); ?>

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

<tr valign="top">
<td><?php echo lang('plugin_filter_to'); ?></td>
<td>
	<?php echo form_radio('filter_to','all',true,'id="filter_to_all"');?> <?php echo lang('plugin_all'); ?>
	<?php echo form_radio('filter_to','specific',false,'id="filter_to_specific"');?> <?php echo lang('plugin_specific'); ?><br/>
	<div id="person" style="display:none;">
		<textarea id="personvalue" style="width: 95%;" name="personvalue" /></textarea>
	</div>
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
	var sms_char = 160;
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

	
	$("input[name='filter_to']").click(function() {
			if($(this).val()=='all')  { $("#person").hide(); }
			if($(this).val()=='specific')  { $("#person").show(); }
	});
});

	$("#personvalue").tokenInput("<?php echo site_url('phonebook/get_phonebook');?>", {
		hintText:"<?php echo lang('tni_name_search')?>",
		noResultsText:"No results",
		searchingText: "<?php echo lang('tni_compose_searching'); ?>...",
		preventDuplicates: true,	
		method: "POST",
            classes: {
                tokenList: "token-input-list-facebook",
                token: "token-input-token-facebook",
                tokenDelete: "token-input-delete-token-facebook",
                selectedToken: "token-input-selected-token-facebook",
                highlightedToken: "token-input-highlighted-token-facebook",
                dropdown: "token-input-dropdown-facebook",
                dropdownItem: "token-input-dropdown-item-facebook",
                dropdownItem2: "token-input-dropdown-item2-facebook",
                selectedDropdownItem: "token-input-selected-dropdown-item-facebook",
                inputToken: "token-input-input-token-facebook"
            }

        });
		
</script>

</div>
