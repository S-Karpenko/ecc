/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* license		Commercial
* the JS code in this file is not licensed under the GPL license and you should get a written permission from webmaster@chronoengine.com to be able use it outside the Chronoforms package!
* Visit http://www.ChronoEngine.com for regular updates and information.
**/

//vars for the drag box position
var drag_position = null;
var drag_position_y = 0;
window.addEvent('scroll', function() {
	//get window scroll
	var window_scroll = window.getScroll();
	//get the drag box position for 1 time only
	if(drag_position == null){
		drag_position = $('drag_box').getPosition();
		drag_position_y = drag_position.y;
	}
	//do the magic
	if(window_scroll.y > drag_position_y){
		$('drag_box').setStyles({'position': 'fixed', 'top' : 0});
	}else{
		$('drag_box').setStyles({'position': 'relative'});
	}
});
//main wizard logic
window.addEvent('domready', function() {
	//EASY MODE CODE
	if(EASY_MODE == true){		
		containers.each(function(container){
			$('easy_div_'+container).getElements('div[id^=cfaction_]').each(function(action){
				//add edit event
				action.addEvent('click', function(event){
					if($('action_settings') == null){
						var settings = new Element('div', {'id' : 'action_settings', 'class' : 'settings actions_accordion_pane'});
						settings.inject($('action_settings_hidden'));
					}
					showFieldSettings(this, $('action_settings'));
				}.bindWithEvent(action));
				
				//delet events
				action.getElements('div[id^=cfactionevent_]').each(function(cfactionevent){
					cfactionevent.destroy();
				});
				
				if(action.getElement('.action_label') != null){
					action.getElement('.action_label').set('html', action.getElement('.action_label').get('html') + ' <font style="color:#f00;">(Click to configure)</font>');
				}
				
				//add clear div
				var clear = new Element('div', {'html' : '<div class="clear">&nbsp;</div>'});
				action.adopt(clear.getFirst().clone());
			});
		});
	}	
	//END EASY MODE
	//elements accordion
	var elementsAccordion = new Accordion($('elements_accordion'), 'a.element_toggler', 'div.elements_accordion_pane', {
		opacity: false,
		show: 0,
		alwaysHide: false
	});
	//actions accordion
	var actionsAccordion = new Accordion($('actions_accordion'), 'a.action_toggler', 'div.actions_accordion_pane', {
		opacity: false,
		show: 0,
		alwaysHide: false
	});
	//add clear div for all config items exist in page
	var clear = new Element('div', {'html' : '<div class="clear">&nbsp;</div>'});
	$$('.element_config_item').each(function(element){
		element.adopt(clear.getFirst().clone());
	});
	$$('.validation_rule').each(function(element){
		element.adopt(clear.getFirst().clone());
	});
	$$('.wizard_element').each(function(wizard_element){
		if(wizard_element.hasClass('input_container_element_view') == false){
			wizard_element.adopt(clear.getFirst().clone());
		}
	});
	if(EASY_MODE == false){
		//tie few tabs together
		$('actions-panel-tab').addEvent('click', function(event){
			switchTab('events');
		});
		$('elements-panel-tab').addEvent('click', function(event){
			switchTab('preview');
		});
		$('events-panel-tab').addEvent('click', function(event){
			switchTab('actions');
		});
		$('preview-panel-tab').addEvent('click', function(event){
			switchTab('elements');
		});
	}
	//IE is crap, a fix for the ondragstart event so that sortables may work fine
	document.ondragstart = function(){
		return false;
	};
	var droppable_container_temp = [];
	//manage old fields events
	var old_containers = [];
	$('droppable_area_elements').getElements('div[class*=wizard_element]').each(function(wizard_element){
		var element_tools = $$('div.element_tools')[0].clone(true, true);
		var field_id = wizard_element.getElement('input[id^=chronofield_id]').get('value');
		var element_tools = element_tools.set({'class' : 'element_tools_visible', 'html': element_tools.get('html').replace(/{n}/g, field_id)});				
		element_tools.inject(wizard_element.getFirst(), 'before');
		//add events to the element div
		wizard_element.addEvent('mouseover', function(){this.addClass('element_div_hover')});
		wizard_element.addEvent('mouseout', function(){this.removeClass('element_div_hover')});
				
		//add tools events
		//add delete event
		wizard_element.getElement('.delete_element').addEvent('click', function(event){
			this.getElement('.delete_element').floatingTipsHide();
			this.destroy();
			event.stopPropagation();
		}.bindWithEvent(wizard_element));
		//add edit event
		wizard_element.getElement('.edit_element').addEvent('click', function(event){
			if($('field_settings') == null){
				var settings = new Element('div', {'id' : 'field_settings', 'class' : 'settings elements_accordion_pane'});
				settings.inject($('field_settings_hidden'));
			}
			showFieldSettings(this, $('field_settings'));
		}.bindWithEvent(wizard_element));				
		//add sort event
		wizard_element.getElement('.sort_element').addEvent('click', function(event){
			event.stopPropagation();
		}.bindWithEvent(wizard_element));
		//var sortable_elements = new Sortables($('droppable_area_elements'), {clone:true, opacity:0.4, handle: '.sort_element'});
		//if this was a container then make it a droppable
		if(wizard_element.hasClass('input_container_element_view')){
			initializeElementsDroppables([wizard_element]);
			//var sortable_elements = new Sortables(wizard_element, {clone:true, opacity:0.4, handle: '.sort_element'});
			//console.log(wizard_element);
			if(old_containers.contains(wizard_element) == false){
				old_containers.include(wizard_element);
			}
		}
		
	});
	//fix old conatiners sortables
	old_containers.each(function(old_container){
		var sortable_container_elements = new Sortables(old_container, {clone:true, opacity:0.4, handle: '.sort_element'});
		sortable_container_elements.removeItems($$('.element_tools_visible'));
	})
	//make old elements sortable
	var sortable_elements = new Sortables($('droppable_area_elements'), {clone:true, opacity:0.4, handle: '.sort_element'});
	//manage old actions events
	var old_events = [];
	$('droppable_area_actions').getElements('div[class*=wizard_element]').each(function(wizard_element){
		var field_id = wizard_element.getChildren('input[id^=chronoaction_id]')[0].get('value');
		var element_tools = $$('div.element_tools')[0].clone(true, true);
		var element_tools = element_tools.set({'class' : 'element_tools_visible', 'id': 'element_tools_'+field_id, 'html': element_tools.get('html').replace(/{n}/g, field_id)});
		wizard_element.getFirst().set('html', wizard_element.getFirst().get('html')+"<font style='color:#f00'> ("+field_id+")</font>");
		element_tools.inject(wizard_element.getFirst(), 'before');
		//add events to the element div
		wizard_element.addEvent('mouseover', function(){this.addClass('element_div_hover')});
		wizard_element.addEvent('mouseout', function(){this.removeClass('element_div_hover')});
				
		//add tools events
		//add delete event
		wizard_element.getElement('.delete_element').addEvent('click', function(event){
			this.getElement('.delete_element').floatingTipsHide();
			this.destroy();
			//clearFieldSettings($('action_settings'));
			event.stopPropagation();
		}.bindWithEvent(wizard_element));
		//add edit event
		wizard_element.getElement('.edit_element').addEvent('click', function(event){
			if($('action_settings') == null){
				var settings = new Element('div', {'id' : 'action_settings', 'class' : 'settings actions_accordion_pane'});
				settings.inject($('action_settings_hidden'));
			}
			showFieldSettings(this, $('action_settings'));
		}.bindWithEvent(wizard_element));				
		//add sort event
		wizard_element.getElement('.sort_element').addEvent('click', function(event){
			event.stopPropagation();
		}.bindWithEvent(wizard_element));
		//insert action event map idetifiers
		var last_identifier_name = wizard_element.getParent('.form_event').getElement('input[name^=_form_actions_events_map]').get('name');
		new Element('input', {'type': 'hidden', 'name': last_identifier_name+'[actions]['+wizard_element.get('id').replace(/_element_/, '_')+']'}).inject(wizard_element, 'top');
		//check if action has any events and add the identifiers to them if so
		if(wizard_element.getElements('div.form_event').length > 0){
			wizard_element.getElements('div.form_event').each(function(wizard_element_event){
				//var event_name = wizard_element_event.get('id').replace(wizard_element.get('id').replace(/_element_/, '_').replace(/cfaction_/, 'cfactionevent_')+'_', '');
				new Element('input', {'type': 'hidden', 'name': last_identifier_name+'[actions]['+wizard_element.get('id').replace(/_element_/, '_')+'][events]['+wizard_element_event.get('id')+']'}).inject(wizard_element_event, 'top');
			})
			//also make them valid droppables
			initializeActionsDroppables(wizard_element.getElements('div.form_event'));
		}
		if(old_events.contains(wizard_element.getParent('.form_event')) == false){
			old_events.include(wizard_element.getParent('.form_event'));
		}
	});
	//make old actions sortable
	old_events.each(function(event_div){
		var sortable_actions = new Sortables(event_div, {clone:true, opacity:0.4, handle: '.sort_element'});
		sortable_actions.removeItems(event_div.getChildren('label'));
	});
	
	initializeElementsDroppables([$('droppable_area_elements')]);
	
	//actions
	//initializeActionsDroppables([$('FormOnLoadEvent'), $('FormOnSubmitEvent')]);
	initializeActionsDroppables($('droppable_area_actions').getElements('.main_event'));
	
	var form_save_event = function(){
		$('sbox-content').getElement('.form_save_button').addEvent('click', function(){
			$('ChronoformName').set({'value' : $('sbox-content').getElement('.chronoform_name').get('value')});
			submitform('ccms_adminform', 'wizard', 0, 0, 0, 0);
		});
	}
	SqueezeBox.initialize();
	SqueezeBox.assign($('wizard_save'), {
		size: {x: 200, y: 200},
		url: '#form_save_box',
		onOpen: form_save_event
	});
});

var droppable_container_temp = [];
function initializeElementsDroppables(droppables){
	var element_count = $('max_field_index').get('value').toInt();
	$('elements_accordion').getElements('.dragable').makeGhostDraggable({
		droppables: droppables,//[$('droppable_area_elements')],//'.droppable',
		opacity: 1,
		onStart:function(element){
			droppable_container_temp.empty();
		},
		onEnter:function(element, droppable){
			droppable_container_temp.include(droppable);
		},
		onLeave:function(element, droppable){
			droppable_container_temp.erase(droppable);
		},
		onDrop: function(element, droppable) {
			if (droppable) {
				var element_count = $('max_field_index').get('value').toInt();
				//create the element div to be insterted in the view pane
				var real_element_id = element.get('id')+'_element';
				var real_element = $(real_element_id).clone(true, true).set({'class':element.get('id')+'_element_view wizard_element preview_element', 'id':real_element_id+'_'+element_count});
				//insert tools
				var element_tools = $$('div.element_tools')[0].clone(true, true).set({'class' : 'element_tools_visible'});
				
				element_tools.inject(real_element);
				//end tools injection
				//add clear div
				var clear = new Element('div', {'html' : '<div class="clear">&nbsp;</div>'});
				real_element.adopt(clear.getFirst().clone());
				//
				real_element.set({'html': real_element.get('html').replace(/{n}/g, element_count)});
				//add events to the element div
				real_element.addEvent('mouseover', function(){this.addClass('element_div_hover')});
				real_element.addEvent('mouseout', function(){this.removeClass('element_div_hover')});
								
				//add tools events
				//add delete event
				real_element.getElement('.delete_element').addEvent('click', function(event){
					this.getElement('.delete_element').floatingTipsHide();
					this.destroy();
					event.stopPropagation();
				}.bindWithEvent(real_element));
				real_element.getElement('.delete_element').floatingTips();
				//add edit event
				real_element.getElement('.edit_element').addEvent('click', function(event){
					if($('field_settings') == null){
						var settings = new Element('div', {'id' : 'field_settings', 'class' : 'settings elements_accordion_pane'});
						settings.inject($('field_settings_hidden'));
					}
					showFieldSettings(this, $('field_settings'));
				}.bindWithEvent(real_element));
				real_element.getElement('.edit_element').floatingTips();
				//add sort event
				real_element.getElement('.sort_element').addEvent('click', function(event){
					event.stopPropagation();
				}.bindWithEvent(real_element));
				real_element.getElement('.sort_element').floatingTips();
				//check which droppable will be used
				//console.log(droppable_container_temp);
				if(droppable_container_temp.length > 0){
					droppable = droppable_container_temp[droppable_container_temp.length - 1];
					real_element.inject(droppable);
					droppable_container_temp.empty();
				}
				if(droppable.hasClass('input_container_element_view')){
					var container_id = droppable.get('id').replace(/input_container_element_/, '');
					if($("container_id_"+element_count) != null){
						$("container_id_"+element_count).set('value', container_id);
					}
				}
				//reset sorting
				var sortable_elements = new Sortables(droppable, {clone:true, opacity:0.4, handle: '.sort_element'});
				sortable_elements.removeItems($$('.element_tools_visible'));
				element_count = element_count + 1;
				//update element count
				$('max_field_index').set('value', element_count);
				//if this was a container then make it a droppable
				if(element.get('id') == 'input_container'){
					initializeElementsDroppables([real_element]);
				}
			}
		}
	});
}

function clearFieldSettings(){
	$('field_settings').empty();
	$('action_settings').setStyle('display', 'none');
	$('field_settings').setStyle('display', 'none');
}

var droppable_container_temp = [];
//var events_actions_map = {'MYFORM': {}};
function initializeActionsDroppables(droppables){
	var action_count = $('max_action_index').get('value').toInt();
	$('actions_accordion').getElements('.dragable').makeGhostDraggable({
		droppables: droppables,//[$('droppable_area_actions')],
		//stopPropagation: true,
		onStart:function(element){
			droppable_container_temp.empty();
		},
		onEnter:function(element, droppable){
			droppable_container_temp.include(droppable);
		},
		onLeave:function(element, droppable){
			droppable_container_temp.erase(droppable);
		},
		onDrop: function(element, droppable, event){
			if(droppable){
				var action_count = $('max_action_index').get('value').toInt();
				//create the element div to be insterted in the view pane
				var real_element_id = element.get('id')+'_element';
				var real_element = $(real_element_id).clone(true, true).set({'class':element.get('id')+'_element_view wizard_element form_action', 'id':real_element_id+'_'+action_count});
				//insert tools
				var element_tools = $$('div.element_tools')[0].clone(true, true).set({'class' : 'element_tools_visible'});
				real_element.getFirst().set('html', real_element.getFirst().get('html')+"<font style='color:#f00'> ("+action_count+")</font>");
				element_tools.inject(real_element);
				//end tools injection
								
				//add clear div
				var clear = new Element('div', {'html' : '<div class="clear">&nbsp;</div>'});
				real_element.adopt(clear.getFirst().clone());
				real_element.set({'html': real_element.get('html').replace(/{n}/g, action_count)});
				//add events to the element div
				real_element.addEvent('mouseover', function(){this.addClass('element_div_hover')});
				real_element.addEvent('mouseout', function(){this.removeClass('element_div_hover')});
								
				//add tools events
				//add delete event
				real_element.getElement('.delete_element').addEvent('click', function(event){
					this.getElement('.delete_element').floatingTipsHide();
					this.destroy();
					//clearFieldSettings($('action_settings'));
					event.stopPropagation();
				}.bindWithEvent(real_element));
				real_element.getElement('.delete_element').floatingTips();
				//add edit event
				real_element.getElement('.edit_element').addEvent('click', function(event){
					if($('action_settings') == null){
						var settings = new Element('div', {'id' : 'action_settings', 'class' : 'settings actions_accordion_pane'});
						settings.inject($('action_settings_hidden'));
					}
					showFieldSettings(this, $('action_settings'));
				}.bindWithEvent(real_element));
				real_element.getElement('.edit_element').floatingTips();
				//add sort event
				real_element.getElement('.sort_element').addEvent('click', function(event){
					event.stopPropagation();
				}.bindWithEvent(real_element));
				real_element.getElement('.sort_element').floatingTips();
				
				//check where to drop the element
				var injected = false;
				if(droppable_container_temp.length > 0){
					droppable = droppable_container_temp[droppable_container_temp.length - 1];
					//insert actions/events identifier(s)
					var last_identifier_name = droppable.getElement('input[name^=_form_actions_events_map]').get('name');
					new Element('input', {'type': 'hidden', 'name': last_identifier_name+'[actions]['+element.get('id')+'_'+action_count+']'}).inject(real_element, 'top');
					//check if the action has any events and insert the identifiers
					real_element.getElements('div.form_event').each(function(form_event){
						new Element('input', {'type': 'hidden', 'name': last_identifier_name+'[actions]['+element.get('id')+'_'+action_count+'][events]['+form_event.get('id')+']'}).inject(form_event, 'top');
					})
					//finally inject the element
					real_element.inject(droppable);
					injected = true;
					droppable_container_temp.empty();
					//update the actions counter only after the new action is injected
					action_count = action_count + 1;
					//update action count
					$('max_action_index').set('value', action_count);
				}else{
					//real_element.inject(droppable);
				}
				//check for events in the new action
				if(real_element.getElements('div.form_event').length > 0){
					initializeActionsDroppables(real_element.getElements('div.form_event'));
				}
				
				//reset sorting
				if(injected){
					var sortable_actions = new Sortables(droppable, {
							clone:true, 
							opacity:0.4, 
							handle: '.sort_element',
							onStart: function(element, clone){
								if($chk(element.getElement('.mceEditor'))){
									var id = element.getElement('.mceEditor').get('id').replace(/_parent/, '');
									tinyMCE.execCommand("mceRemoveControl", false, id);
								}
							}
						}
					);
					sortable_actions.removeItems(droppable.getChildren('label'));
				}
				//actions counter removed
			}
		}
	});
}

function showFieldSettings(container_div, settings){
	//if(settings.retrieve('settings_for') != container_div.get('id')){
		var field_type = container_div.get('id').replace(/_element_[0-9]*/, '');
		var fcountre = new RegExp(field_type+'_element_');
		var field_count = container_div.get('id').replace(fcountre, '');
		settings.empty();
		//inject the settings area
		var ElementSettingsClone = $(field_type+'_element_config').clone(true, true).setStyle('display', 'block').set({'html': $(field_type+'_element_config').get('html').replace(/{n}/g, field_count)});
		ElementSettingsClone.inject(settings);
		//if settings has a tabs box then initialize it
		if(ElementSettingsClone.getElements('.tabs_box').length > 0){
			//ApplyTabEventsToContainer(ElementSettingsClone.getElements('.tabs_box')[0]);
		}
		settings.getElements('[name$=config]').each(function(config){
			var target_config_element = container_div.getElement('[id='+config.get('name').replace(/_config/, '')+']');
			if(config.get('rule') == 'bool'){
				if(target_config_element.get('value').toInt() == 1){
					config.set({'checked' : 'checked'});
				}else{
					//do nothing
					config.erase('checked');
				}
			}else if(config.get('rule') == 'split'){
				var splitter = new RegExp(config.get('splitter'));
				var selections = target_config_element.get('value').split(splitter);
				if(config.get('tag') == 'select'){
					config.getChildren('option').each(function(option){
						if(selections.contains(option.get('value'))){
							option.set({'selected' : 'selected'});
						}else{
							option.erase('selected');
						}
					});
				}else{
					if(selections.contains(config.get('value'))){
						config.set({'checked' : 'checked'});
					}else{
						//do nothing
						config.erase('checked');
					}
				}
			}else{
				config.set({'value' : target_config_element.get('value')});
			}
		});
		settings.store('settings_for', container_div.get('id'));
		//fix some stuff
		if($chk(settings.getElement('.loadingimg_div'))){
			settings.getElement('.loadingimg_div').setStyle('display', 'none');
		}
		settings.setStyle('display', 'block');
		var parentsize = settings.getSize();
		var childsize = settings.getFirst('.element_config').getSize();
		if(parentsize.y < childsize.y){
			settings.setStyle('height', childsize.y);
		}else{
			settings.setStyle('height', childsize.y);
		}
		//load the squeeze box and insert the buttons
		if($chk($('sbox-btn-apply'))){
			$('sbox-btn-apply').destroy();
		}
		//load the element's onload function if exists
		var fn_name = field_type+'_onload';
		if(typeof window[fn_name] == 'function'){
			window[fn_name](field_count);
		}
		//open in squeeze box
		SqueezeBox.initialize();
		SqueezeBox.open(settings, {
			handler: 'adopt',
			size: {x: 600, y: 500},
			onOpen: function(content){
				//var applyButton = content.getNext().clone(true, true).set({'id': 'sbox-btn-apply', 'text' : 'Apply'});
				var applyButton = new Element('a').set({'id': 'sbox-btn-apply', 'text' : 'Apply'});
				applyButton.addEvent('click', function(e){
					deactivateEditor(content);
					saveFieldSettings(container_div, settings);
					//activateEditor(content);
					return false;
				});
				applyButton.inject(content, 'after');
			},
			onClose: function(content){
				deactivateEditor(content);
				if($chk($('sbox-btn-apply'))){
					$('sbox-btn-apply').destroy();
				}
			}
		});
	//}
}

function saveFieldSettings(container_div, settings){
	var field_type = container_div.get('id').replace(/_element_[0-9]*/, '');
	var fcountre = new RegExp(field_type+'_element_');
	var field_count = container_div.get('id').replace(fcountre, '');
	settings.getElements('[name$=config]').each(function(config){
		var target_config_element = container_div.getElement('[id='+config.get('name').replace(/_config/, '')+']');
		if(config.get('rule') == 'bool'){
			if(config.get('checked')){
				target_config_element.set({'value' : '1'});
			}else{
				target_config_element.set({'value' : '0'});	
			}
		}else if(config.get('rule') == 'split'){
			var splitter_escaped = escape(config.getProperty('splitter'));
			var new_splitter_escaped = splitter_escaped.replace('%5Cn', '%0A');
			var new_splitter = unescape(new_splitter_escaped);
			target_config_element.set({'value' : ''});
			var config_group = new Array();
			//$('field_settings').getElements('[name='+config.get('name')+']').each(function(config_group_item){
			settings.getElements('[name='+config.get('name')+']').each(function(config_group_item){
				if(config.get('tag') == 'select'){
					config.getChildren('option').each(function(option){
						if(option.get('selected') == true){
							config_group.include(option.get('value'));
						}else{
							
						}
					});
				}else{
					if(config_group_item.get('checked')){	
						config_group.include(config_group_item.get('value'));
					}else{
							
					}
				}
			});			
			target_config_element.set({'value' : config_group.join(new_splitter)});			
		}else{
			target_config_element.set({'value' : config.get('value')});
		}
		//reflect options
		if(config.get('operation') == 'multi_option'){
			//fields changes
			if(container_div.getChildren('.options_refelctor')[0] != null){
				if(config.get('operation_fieldtype') == 'select'){
					container_div.getChildren('.options_refelctor')[0].empty();
					config.get('value').split("\n").each(function(option){
						var option_details = option.split('=');
						if(option_details.length > 1){					
							new Element('option', {'value': option_details[0], 'text': option_details[1]}).inject(container_div.getChildren('.options_refelctor')[0]);
						}
					});
				}else{
					container_div.getChildren('.options_refelctor')[0].empty();
					var field_id = container_div.getElement('[id='+field_type+'_'+field_count+'_input_id]').get('value');
					config.get('value').split("\n").each(function(option){
						var option_details = option.split('=');
						if(option_details.length > 1){					
							new Element('input', {'id': field_id+option_details[0], 'type': config.get('operation_fieldtype')}).inject(container_div.getChildren('.options_refelctor')[0]);
							new Element('label', {'for': field_id+option_details[0], 'text': option_details[1]}).inject(container_div.getChildren('.options_refelctor')[0]);
						}
					});
				}
			}			
		}
		//reflect label change
		if(container_div.getFirst('label.updatable_label') != null){
			if(settings.getElements('[name$=label_text_config]')[0] != null){
				container_div.getFirst('label.updatable_label').set('text', settings.getElements('[name$=label_text_config]')[0].get('value'));
			}
		}
		//add on change event for config fields to enable the apply button
		if($chk($('sbox-btn-apply'))){
			if(config.get('type') == 'checkbox' || config.get('type') == 'radio' || config.get('tag') == 'select'){
				config.addEvent('change', function(){
					activateSaveButton();
				});
				config.addEvent('click', function(){
					activateSaveButton();
				});
			}else{
				config.addEvent('keydown', function(){
					activateSaveButton();
				});
			}
		}
	});
	//settings.getElement('input[id$=save_settings_button]').set({'disabled' : true, 'value' : 'Saved'});
	if($chk($('sbox-btn-apply'))){
		$('sbox-btn-apply').set('text', 'Saved');
		$('sbox-btn-apply').setStyle('opacity', 0.7);
	}
}

function deactivateEditor(content){
	if($chk(content.getElement('.mceEditor'))){
		var id = content.getElement('.mceEditor').get('id').replace(/_parent/, '');
		tinyMCE.execCommand("mceRemoveControl", false, id);
	}
}

function activateEditor(content){
	if($chk(content.getElement('.text_editor'))){
		var id = content.getElement('.text_editor').get('id').replace(/_parent/, '');
		tinyMCE.execCommand("mceAddControl", false, id);
	}
}

function activateSaveButton(){
	$('sbox-btn-apply').set('text', 'Apply');
	$('sbox-btn-apply').setStyle('opacity', 1);
}

function ShowAddEventDialogue(){
	var event_box = $('add_event_box').clone(true, true).setStyle('display', 'block');
	event_box.getElement('input[name=add_event_button]').addEvent('click', function(event){
		addNewEvent();
	});
	event_box.set('id', 'add_event_box_new');
	event_box.inject($('add_event_box'), 'after');
	
	SqueezeBox.initialize();
	SqueezeBox.open($('add_event_box_new'), {
		handler: 'adopt',
		size: {x: 500, y: 170}
	});
	/*var event_box = $('add_event_box').clone(true, true).setStyle('display', 'block');
	event_box.getElement('input[name=add_event_button]').addEvent('click', function(event){
		addNewEvent();
	});*/
	//event_box.inject($('sbox-content'));
}

function addNewEvent(){
	var new_event = new Element('div', {'id' : 'FormOn'+$('sbox-content').getElement('input[name=event_name]').get('value')+'Event', 'class' : 'form_event main_event good_event'});
	var new_event_label = new Element('label', {'text' : 'On '+$('sbox-content').getElement('input[name=event_name]').get('value'), 'class': 'form_event_label'});
	new_event_label.inject(new_event);
	var new_event_hidden = new Element('input', {'type' : 'hidden', 'name' : '_form_actions_events_map[myform][events]['+$('sbox-content').getElement('input[name=event_name]').get('value')+']', 'value' : ''});
	new_event_hidden.inject(new_event);
	//add the new event delete icon
	var delete_icon = $$('img.delete_element')[0].clone().setStyles({'float' : 'right', 'position' : 'absolute', 'top' : '7px', 'right' : '10px'});				
	delete_icon.inject(new_event);
	new_event.getElement('.delete_element').addEvent('click', function(event){
		this.getElement('.delete_element').floatingTipsHide();
		this.destroy();
		event.stopPropagation();
	}.bindWithEvent(new_event));
	new_event.getElement('.delete_element').floatingTips();
	//inject the new event box into wizard body
	new_event.inject($('EventsOperations'), 'before');
	initializeActionsDroppables([new_event]);
	SqueezeBox.close();
}

window.addEvent('domready', function(){
	//configure old events delete icons
	$$('.main_event').each(function(main_event){
		if(main_event.getElement('.delete_element') != null){
			main_event.getElement('.delete_element').addEvent('click', function(event){
				this.getElement('.delete_element').floatingTipsHide();
				this.destroy();
				event.stopPropagation();
			}.bindWithEvent(main_event));
			main_event.getElement('.delete_element').floatingTips();
		}
	});
});

function openSaveBox(SqueezeBox){
	SqueezeBox.initialize();
	SqueezeBox.open($('form_save_box'), {
		handler: 'adopt',
		size: {x: 300, y: 200}
	});
}

function switch_fullscreen(){
	if($('element-box').hasClass('fullscreen')){
		$('element-box').removeClass('fullscreen');
		$('element-box').addClass('normalscreen');
		$('top-strap').setStyle('display', 'none');
		$('fullscreen').set('src', 'components/com_chronoforms/images/fullscreen.png');
		var total_size = $('element-box').getSize();
		$("drop_box").setStyle('width', total_size.x - 300);
		morph_width_change();
	}else{
		$('element-box').removeClass('normalscreen');
		$('element-box').addClass('fullscreen');
		$('top-strap').setStyle('display', 'block');
		$('fullscreen').set('src', 'components/com_chronoforms/images/normalscreen.png');
		morph_width_change();
	}
}

window.addEvent('domready', function(){
	morph_width_change();
});

window.addEvent('resize', function(){
	morph_width_change();
});

function morph_width_change(){
	var total_size = $('element-box').getSize();
	var width = total_size.x;
	$("drag_box").morph({'width' : 250});
	$("drop_box").morph({'width' : (width - (250 + 50))});
}

function collapseContainer(ID, reverse){
	var collapsed = $("input_container_"+ID+"_collapsed").get('value');
	if(reverse == true){
		collapsed = collapsed.toInt();
		collapsed = 1 - collapsed;
		//console.log(collapsed);
	}
	if(collapsed == 1){
		//$("input_container_element_"+ID).setStyle('min-height', 100);
		$("input_container_element_"+ID).setStyle('height', 'auto');
		//$("input_container_element_"+ID).morph({'height' : 'auto'});
		$("input_container_element_"+ID).getChildren('div[class*=wizard_element]').each(function(wizard_element){
			wizard_element.setStyle('display', 'block');
		});
		$("input_container_"+ID+"_collapsed").set('value', 0);
		$("input_container_collapse_toggler_"+ID).set('text', "Collapse");
	}else{
		$("input_container_element_"+ID).setStyle('min-height', 5);
			
		$("input_container_element_"+ID).getChildren('div[class*=wizard_element]').each(function(wizard_element){
			wizard_element.setStyle('display', 'none');
		});
		$("input_container_element_"+ID).setStyle('height', 5);
		$("input_container_"+ID+"_collapsed").set('value', 1);
		$("input_container_collapse_toggler_"+ID).set('text', "Expand");
	}	
	return false;
}
//set the containers states
window.addEvent('domready', function(){
	$('droppable_area_elements').getElements('.input_container_element_view').each(function(container){
		var container_id = container.get('id').replace(/input_container_element_/, '');
		collapseContainer(container_id, true);
	});
});