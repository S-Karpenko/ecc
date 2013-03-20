<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
$mainframe = JFactory::getApplication();
$uri = JFactory::getURI();
jimport('joomla.html.pane');
//$editor = JFactory::getEditor();
$tinycode = '
tinyMCE.init({
	mode : "textareas",
	relative_urls: false,
	theme: "advanced",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,hr,removeformat,sub,sup,|,charmap,emotions,media,advhr",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	editor_selector : "mce_editor"';
$tinycode .= '
});
function toggleEditor(id){
	if (!tinyMCE.get(id)){
		tinyMCE.execCommand("mceAddControl", false, id);
	}else{
		tinyMCE.execCommand("mceRemoveControl", false, id);
	}
}
function genAutoTemplate(ID){
	if($("ChronoformId").get("value") == ""){
		$("toolbar-apply").floatingTips({
			content: function(){
				return "Please save your form first!";
			},
			position: "bottom",
			className: "floating-tip-error",
			distance: 3
		});
		var scroller = new Fx.Scroll(window);
		scroller.toTop();
		$("toolbar-apply").floatingTipsShow();
		var ed = tinyMCE.get("chronoaction_"+ID+"_action_email_"+ID+"_content1_");
		ed.setContent("Please save your form first!");
	}else{
		var Acturl = "index.php?option=com_chronoforms&task=action_task&action_name=email&fn=generate_auto_template";
		var a = new Request.HTML({
					url: Acturl,
					method: "get",
					onRequest: function(){
						//$("action_email_"+ID+"_content1_config").empty();
						//$("action_email_"+ID+"_content1_config").set("value", "Working....Please wait!");
						var ed = tinyMCE.get("chronoaction_"+ID+"_action_email_"+ID+"_content1_");
						ed.setProgressState(1);
					},
					onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
						if(responseHTML != ""){
							//$("action_email_"+ID+"_content1_config").empty();
							//$("action_email_"+ID+"_content1_config").set("value", responseHTML);
							var ed = tinyMCE.get("chronoaction_"+ID+"_action_email_"+ID+"_content1_");
							ed.setProgressState(0);
							ed.setContent(responseHTML);
						}
					}
				});
		a.send("form_id="+$("ChronoformId").get("value"));
	}
}
window.addEvent("domready", function(){
	$$(".auto_template_generation").floatingTips({
		//position: "right",
	});
});

';
?>

<?php
$jversion = new JVersion();
if($jversion->RELEASE > 1.5):
?>
<script type="text/javascript" src="<?php echo $uri->root(); ?>media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<?php else: ?>
<script type="text/javascript" src="<?php echo $uri->root(); ?>plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<?php endif; ?>

<script type="text/javascript"> 
//<![CDATA[
<?php echo $tinycode; ?>
//]]>
</script>

<?php
	$pane = JPane::getInstance('tabs');
	echo $pane->startPane('emails');
	$email_c = 1;
?>
<?php for($i = 10; $i <= 12; $i++): ?>
<?php echo $pane->startPanel('Email '.$email_c, 'emails_email_'.$i); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_enabled]', array('type' => 'select', 'label' => "Enabled", 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
	
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_to]', array('type' => 'text', 'label' => "To *", 'class' => 'medium_input', 'smalldesc' => 'List of recipient(s) email address(es) separated by comma,e.g: me@domain.com OR x@dom.com,z@dom.com')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_subject]', array('type' => 'text', 'label' => "Subject *", 'class' => 'medium_input', 'smalldesc' => 'Email subject.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_fromname]', array('type' => 'text', 'label' => "From name *", 'default' => $mainframe->getCfg('fromname'), 'class' => 'medium_input', 'smalldesc' => 'The name of sender.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_fromemail]', array('type' => 'text', 'label' => "From email *", 'default' => $mainframe->getCfg('mailfrom'), 'class' => 'medium_input', 'smalldesc' => 'The email address of the sender.')); ?>
	<input type="button" class="auto_template_generation" title="Click to auto generate an email template from your form contents." name="action_email_refresh_button" id="action_email_refresh_button_<?php echo $i; ?>" value="Generate Auto Template" onClick="genAutoTemplate('<?php echo $i; ?>')" style="margin-left:160px; color: #fff; background-color: #2E8CD9;" />
	<br />
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_content1]', array('type' => 'textarea', 'label' => "Email template", 'class' => 'mce_editor', 'rows' => 20, 'cols' => 85, 'style' => 'width: 600px; height: 300px;', 'smalldesc' => 'You may use the curly brackets formula to get fields data from the form data array, e.g: {field_name}.')); ?>
	<?php //echo $editor->display('chronoaction['.$i.'][action_email_'.$i.'_content1]', '', 600, 400, 30, 85, 1); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_attachments]', array('type' => 'text', 'label' => "Attachments fields name", 'class' => 'big_input', 'value' => '', 'smalldesc' => 'Fields to be attached to this email message, find fields names inside the fields settings box.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_recordip]', array('type' => 'select', 'label' => "Include IP address", 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => '')); ?>
	
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_dto]', array('type' => 'text', 'label' => "Dynamic To", 'class' => 'medium_input', 'smalldesc' => 'A field name holding an email address to which the email will be sent.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_dreplytoname]', array('type' => 'text', 'label' => "Dynamic Reply to name", 'class' => 'medium_input', 'smalldesc' => 'A field name holding a string which should appear when the email receiver hits the reply button.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_dreplytoemail]', array('type' => 'text', 'label' => "Dynamic Reply to email", 'class' => 'medium_input', 'smalldesc' => 'A field name holding an email address which should be used when the email receiver hits the reply button.')); ?>
	
	
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_email_'.$i.'_replace_nulls]', array('type' => 'hidden', 'value' => 1)); ?>
	
	<?php echo $HtmlHelper->input('chronoaction_id['.$i.']', array('type' => 'hidden', 'value' => $i)); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][type]', array('type' => 'hidden', 'value' => 'email')); ?>
	
<?php echo $pane->endPanel(); ?>
<?php $email_c++; ?>
<?php endfor; ?>

<?php echo $pane->endPane(); ?>