<div class="dragable" id="cfaction_custom_event_switch">Custom Event Switcher</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_custom_event_switch_element">
	<label class="action_label" id="action_label_{n}" style="display: block; float:none!important;">Custom Event Switcher</label>
	<?php
		$events_listed = array();
		if(strlen(trim($action_params['events']))){
			$events_listed = explode(',', $action_params['events']);
		}
		foreach($events_listed as $ev_ls):
	?>
	<div id="cfactionevent_custom_event_switch_{n}_<?php echo $ev_ls; ?>" class="form_event good_event" style="background-color: #ccbb89 !important;">
		<label class="form_event_label">On <?php echo $ev_ls; ?></label>
	</div>
	<?php
		endforeach;
	?>
	<label id="action_label_hidden_{n}" style="display: none; float:none!important;"></label>
	<input type="hidden" name="chronoaction[{n}][action_custom_event_switch_{n}_events]" id="action_custom_event_switch_{n}_events" value="<?php echo $action_params['events']; ?>" />
	<textarea name="chronoaction[{n}][action_custom_event_switch_{n}_content1]" id="action_custom_event_switch_{n}_content1" style="display:none"><?php echo $action_params['content1']; ?></textarea>
    
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="custom_event_switch" />
</div>
<!--end_element_code-->
<script type="text/javascript"> 
//<![CDATA[
function buildEventsList(SID){
	var events_names = $('action_custom_event_switch_'+SID+'_events_config').get('value').split(',');
	$$('div[id^=cfactionevent_custom_event_switch_'+SID+']').each(function(custom_event){
		var custom_event_id_reg = new RegExp('cfactionevent_custom_event_switch_'+SID+'_');
		var custom_event_name = custom_event.get('id').replace(custom_event_id_reg, '');
		if(events_names.contains(custom_event_name) == false){
			custom_event.destroy();
		}else{
			events_names.erase(custom_event_name);
		}
	});
	
	events_names.each(function(event_name){
		var new_event = new Element('div', {'id':'cfactionevent_custom_event_switch_'+SID+'_'+event_name, 'class':'form_event good_event', 'style':'background-color: #ccbb89 !important;', 'html':'<label class="form_event_label">On '+event_name+'</label>'});
		//insert actions/events identifier(s)
		var last_identifier_name = $('cfaction_custom_event_switch_element_'+SID).getElement('input[name^=_form_actions_events_map]').get('name');
		new Element('input', {'type': 'hidden', 'name': last_identifier_name+'[events]['+new_event.get('id')+']'}).inject(new_event, 'top');
		new_event.inject($('action_label_hidden_'+SID), 'before');
		initializeActionsDroppables([new_event]);
	});
}
//]]>
</script>
<div class="element_config" id="cfaction_custom_event_switch_element_config">
    <?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'custom_event_switch_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<input type="button" name="action_custom_event_switch_refresh_button" id="action_custom_event_switch_refresh_button" value="Build Events List" onClick="buildEventsList('{n}')" />
		<br />
		<br />
		<?php echo $HtmlHelper->input('action_custom_event_switch_{n}_events_config', array('type' => 'text', 'label' => 'Events list', 'class' => 'big_input', 'smalldesc' => 'Comma separated list of events, no spaces.')); ?>
		<?php echo $HtmlHelper->input('action_custom_event_switch_{n}_content1_config', array('type' => 'textarea', 'label' => "Code", 'rows' => 20, 'cols' => 70, 'smalldesc' => 'The code here should return the deisred event name to be processed as a string.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>You should use PHP code with php tags.</li>
				<li>Your code should return a string containing the event name to be processed, e.g: return "success";</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>