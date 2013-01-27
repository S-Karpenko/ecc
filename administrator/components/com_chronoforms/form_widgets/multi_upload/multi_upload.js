var MultiUpload = new Class({});
MultiUpload.addAttachment = function(source, name, limit, path){
	if(parseInt(source.getParent().getFirst('.multi-upload-limit').get('value')) < limit){
		var file = new Element('div', {'style':'float:left; clear:left;', 'html':'<a href="javascript:void(0)" class="multi-upload-remove-attachment" onclick="MultiUpload.removeAttachment(this)"><img src="'+path+'images/delete.png" width="16" height="16"></a> <input type="file" name="'+name+'" value="" class="multi-upload-file">'});
		file.inject(source.getParent().getPrevious('.multi-upload-attachments-wrapper'));
		source.getParent().getFirst('.multi-upload-limit').set('value', parseInt(source.getParent().getFirst('.multi-upload-limit').get('value')) + 1);
	}
}
MultiUpload.removeAttachment = function(source){
	source.getParent().destroy();
}
MultiUpload.fixLimit = function(source, ID){
	if(source.checked == true){
		$(ID).set('value', parseInt($(ID).get('value')) + 1);
	}else{
		$(ID).set('value', parseInt($(ID).get('value')) - 1);
	}
}