jQuery(document).ready(function($){

$("#reorder").tableDnD({
	onDragClass: "myDragClass",
	onDrop: function(table, row) {
		var rows = table.tBodies[0].rows;
		for (var i=0; i<rows.length; i++) {
		$(".ordering").eq(i).val(i);
		}
	}
});

//ADD OPTION WHEN CREATING NEW POLL

$('a#options-add').click(function() { 
	var tr_id = parseInt($("tr.dragable:last").attr('id')) + 1;
	$("tr.dragable:last")
	.after("<tr class=\"dragable\" id=\""+tr_id+"\"><td align=\"center\" ><b>"+tr_id+"</b></td><td><input class=\"inputbox checkit\" type=\"text\" name=\"polloption[]\" id=\"polloption"+ tr_id +"\" value=\"\" size=\"60\" /><input type=\"hidden\" name=\"ordering[]\" id=\"ordering"+(tr_id-1)+"\" value=\""+(tr_id-1)+"\" class=\"ordering\" /></td><td><div style=\"position:relative;\"><input type=\"hidden\" size=\"7\"  name=\"color[]\" id=\"color"+(tr_id-1)+"\" value=\"#ff00ff\" class=\"colorpicker\" />&nbsp;</div></td><td></td></tr>");
	$("tr.dragable:last").hide();
	$(".colorpicker:last").colorPicker();
	$("#reorder").tableDnDUpdate();
 	$("tr.dragable:last").css({'backgroundColor':'#99cc00'})
		.fadeIn(400, function callback() {
				$(this).animate({'backgroundColor':'#f6f6f6'},500);
				});
	$("input[name^='polloption']").focus();

});

//REMOVE OPTIONS			
$('a#options-remove').click(function() {
	
	if($("tr:visible:last input.inputbox").val()=='') {
		$("tr:visible:last")
			.animate({'backgroundColor':'#fb6c6c'}, 400)
			.fadeOut(400, function callback() {
				$(this).remove();
				});
	} 
	else {
		if(confirm('Are you sure you want to delete this option?')) {
			$("tr:visible:last").animate({'backgroundColor':'#fb6c6c'}, 400)
				.fadeOut(400, function callback() {
				$(this).remove(); 
				});
		}
	}
	return false;
});

	
// ADD OPTION WHEN EDITING
$('a#options-add-extra').click(function() { 

	var tr_id = parseInt($("tr.dragable:last").attr('id')) + 1;
	$("tr.dragable:last")
	.after("<tr class=\"dragable\" id=\""+tr_id+"\"><td align=\"center\" ><b>"+tr_id+"</b></td><td><input class=\"inputbox checkit\" type=\"text\" name=\"polloptionextra[]\" id=\"polloptionextra"+ tr_id +"\" value=\"\" size=\"60\" /><input type=\"hidden\" name=\"extra_ordering[]\" id=\"extra_ordering"+tr_id+"\" value=\""+(tr_id-1)+"\" class=\"ordering\" /></td><td><div style=\"position:relative;\"><input type=\"hidden\" size=\"7\"  name=\"extra_colors[]\" id=\"extra_colors"+(tr_id-1)+"\" value=\"#ff00ff\" class=\"colorpicker\" />&nbsp;</div></td><td></td></tr>");
	$("tr.dragable:last").hide();
	$(".colorpicker:last").colorPicker();
	$("#reorder").tableDnDUpdate();
 	$("tr.dragable:last").css({'backgroundColor':'#99cc00'})
		.fadeIn(400, function callback() {
				$(this).animate({'backgroundColor':'#f6f6f6'},500);
				});
	$("input[name^='polloptionextra']").focus();	
	$("input#is_there_extra").val(1);
	
});
	
//REMOVE OPTIONS WHEN EDITING		
$('a#options-remove-extra').click(function() {
	$("tr:visible:last").css({'backgroundColor':'#fb6c6c'});
	if($("tr:visible:last input.inputbox").val()=='') {
		$("tr:visible:last")
			.animate({'backgroundColor':'#fb6c6c'}, 400)
			.fadeOut(400, function callback() {
				//$(this).remove();
				});
	} 
	else {
		if(confirm('Are you sure you want to delete this option?')) {
			$("tr:visible:last").animate({'backgroundColor':'#fb6c6c'}, 400)
				.fadeOut(400, function callback() {
				$("tr:hidden:first input.inputbox").val("");
				//$(this).remove(); 
				});
		} else {
			$("tr:visible:last").animate({'backgroundColor':'#f6f6f6'});
		}
	}
	return false;
});				
		
// RESET VOTES

$('a#options-reset').click(function() {
	if(confirm('Are you sure you want to reset the votes?')) {
		$("input#reset").val(1);
		$(".vote").text("0");
		$(this).hide();
		$("#options-reset-box span").fadeIn(400);
	}	
	this.preventDefault();
});	
		
//COLORPICKER 
$(function() {    
//add new colors $.fn.colorPicker.addColors(['000', '000', 'fff', 'fff']);
	$('.colorpicker').each(function(i){
		$(this).colorPicker();
	});
//fires an event when the color is changed
//$('#color1').change(function(){
//});
});
				
});
