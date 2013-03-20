/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
window.addEvent('domready', function(){
	new FloatingTips('div.dragable', {
		content: function(e){
					if($chk($(e.get('id')+'_main_tooltip'))){
						return $(e.get('id')+'_main_tooltip');
					}
					if($chk($(e.get('id')+'_tooltip'))){
						return $(e.get('id')+'_tooltip');
					}
				},
		position: 'right',
		distance: 7,
		html: true,
	});
	new FloatingTips('a.toggler', {
		content: function(){return "Click to expand";},
		position: 'right',
		distance: 7,
		html: true,
	});
	var delete_icon_tip = new FloatingTips('img.delete_element', {});
	var edit_icon_tip = new FloatingTips('img.edit_element', {});
	var sort_icon_tip = new FloatingTips('img.sort_element', {});
	new FloatingTips('img.add_event', {
		content: function(){
			return $('add_event_tooltip');
		},
		html: true,
	});
});