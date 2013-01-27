<div class="dragable" id="cfaction_show_stopper">
Show Stopper
<?php //echo JHTML::tooltip('Will halt any future actions/events.', 'Show Stopper'); ?>
</div>
<div id="cfaction_show_stopper_main_tooltip" style="display:none;">
	<font class="tooltip-header">Show Stopper</font><br />
	<p class="tooltip_container">
		Exit the form processing routine, this action is useful when you want to stop the form processing at some point, please drag it to your desired event.
	</p>
</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_show_stopper_element">
	<label class="action_label" style="display: block; float:none!important;">Show Stopper</label>
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="show_stopper" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_show_stopper_element_config">
    
</div>