var FormCheckJPane = new Class({
	Extends: FormCheck,
	Implements: Options,
	
	/*
	Function: addError
		Private method

		Add error message
	*/
	addError : function(obj) {
		//determine position
		var coord = obj.target ? document.id(obj.target).getCoordinates() : obj.getCoordinates();

		if(!obj.element && this.options.display.indicateErrors != 0) {
			if (this.options.display.errorsLocation == 1) {
				var pos = (this.options.display.tipsPosition == 'left') ? coord.left : coord.right;
				var options = {
					'opacity' : 0,
					'position' : 'absolute',
					'float' : 'left',
					'left' : pos + this.options.display.tipsOffsetX
				};
				obj.element = new Element('div', {'class' : this.options.tipsClass, 'styles' : options}).injectInside(document.body);
				this.addPositionEvent(obj);
			} else if (this.options.display.errorsLocation == 2){
				obj.element = new Element('div', {'class' : this.options.errorClass, 'styles' : {'opacity' : 0}}).injectBefore(obj);
			} else if (this.options.display.errorsLocation == 3){
				obj.element = new Element('div', {'class' : this.options.errorClass, 'styles' : {'opacity' : 0}});
				//hack for position
				if($chk(document.id('error-message-'+obj.get('name').replace(/\[\]/, '')))){
					obj.element.inject(document.id('error-message-'+obj.get('name').replace(/\[\]/, '')));
				}else{
					if ($type(obj.group) == 'object' || $type(obj.group) == 'collection')
						obj.element.injectAfter(obj.group[obj.group.length-1]);
					else
						obj.element.injectAfter(obj);
				}
				//end hack
			}
		}
		if (obj.element && obj.element != true) {
			obj.element.empty();
			//hack for title
			if($chk(obj.get('title'))){
				obj.errors = [obj.get('title')];
			}
			//end hack
			if (this.options.display.errorsLocation == 1) {
				var errors = [];
				obj.errors.each(function(error) {
					errors.push(new Element('p').set('html', error));
				});
				var tips = this.makeTips(errors).injectInside(obj.element);
				if(this.options.display.closeTipsButton) {
					tips.getElements('a.close').addEvent('mouseup', function(){
						this.removeError(obj, 'tip');
					}.bind(this));
				}
				obj.element.setStyle('top', coord.top - tips.getCoordinates().height + this.options.display.tipsOffsetY);
			} else {
				obj.errors.each(function(error) {
					new Element('p').set('html',error).injectInside(obj.element);
				});
			}

			if (!this.options.display.fadeDuration || Browser.Engine.trident && Browser.Engine.version == 5 && this.options.display.errorsLocation < 2) {
				obj.element.setStyle('opacity', 1);
			} else {
				obj.fx = new Fx.Tween(obj.element, {
					'duration' : this.options.display.fadeDuration,
					'ignore' : true,
					'onStart' : function(){
						this.fxRunning = true;
					}.bind(this),
					'onComplete' : function() {
						this.fxRunning = false;
						if (obj.element && obj.element.getStyle('opacity').toInt() == 0) {
							obj.element.destroy();
							obj.element = false;
						}
					}.bind(this)
				});
				if(obj.element.getStyle('opacity').toInt() != 1) obj.fx.start('opacity', 1);
			}
		}
		if (this.options.display.addClassErrorToField && !obj.isChild){
			obj.addClass(this.options.fieldErrorClass);
			obj.element = obj.element || true;
		}

	},
	/*
	Function: onSubmit
		Private method

		Perform check on submit action
	*/
	onSubmit: function(event) {
		this.reinitialize();
		this.fireEvent('onSubmit');
		//start hack to find if there are invalid fields under panels
		this.fixTabs();
		this.fixSliders();
		//end hack
		this.validations.each(function(el) {
			var validation = this.manageError(el,'submit');
			if(!validation) this.form.isValid = false;
		}, this);

		if (this.form.isValid) {
			this.fireEvent('validateSuccess');
			//moved above to allow optional settings to this.form.submit and submitByAjax to be triggered by this option
			return (this.options.submitByAjax)? this.submitByAjax():this.options.submit;
			//if this.options.submit is false it can still rely on validateSuccess event
		} else {
			if (this.elementToRemove && this.elementToRemove != this.firstError && this.options.display.indicateErrors == 1) {
				this.removeError(this.elementToRemove);
			}
			this.focusOnError(this.firstError);
			this.fireEvent('validateFailure');
			return false;
		}
	},
	
	fixTabs: function(){
		var found = 0;
		var PanelIndex = 0;
		this.validations.each(function(el) {
			if(found != 1){				
				var validation = this.manageError(el,'testonly');
				if(!validation && (el.validation.contains('required') == false) && el.value.trim() == ''){
					//do nothing
				}else{
					if(!validation){
						//found invalid field, switch to its panel
						var parentPanel = el.getParents('dd');
						if(parentPanel.length > 0){
							//check if this field is inside a tabs section
							if(parentPanel[0].getParent('div').getParent('div').getFirst('dl').hasClass('tabs')){
								//show the parent Panel body
								var selectedPanel = 0;
								var PanelIndex = 0;
								parentPanel[0].getParent('div').getChildren('dd').each(function(tabBody){
									if(tabBody == parentPanel[0]){
										tabBody.setStyle('display', 'block');
										selectedPanel = PanelIndex;
									}else{
										tabBody.setStyle('display', 'none');								
									}
									PanelIndex = PanelIndex + 1;
								});
								//select the correct tab
								var tabCounter = 0;
								parentPanel[0].getParent('div').getParent('div').getFirst('dl').getChildren('dt').each(function(tab){
									if(tabCounter == selectedPanel){
										tab.addClass('open');
										tab.removeClass('closed');
									}else{
										tab.addClass('closed');
										tab.removeClass('open');								
									}
									tabCounter = tabCounter + 1;
									//remove error when changing tab
									tab.addEvent('click', function(){
										this.removeError(el);
									}.bind(this));
								}, this);
							}
						}
						found = 1;
					}
				}
			}
		}, this);
	},
	
	fixSliders: function(){
		var found = 0;
		var PanelIndex = 0;
		this.validations.each(function(el) {
			if(found != 1){				
				var validation = this.manageError(el,'testonly');
				if(!validation && (el.validation.contains('required') == false) && el.value.trim() == ''){
					//do nothing
				}else{
					if(!validation){
						//found invalid field, switch to its panel
						var parentPanel = el.getParents('div.pane-slider');
						if(parentPanel.length > 0){
							//check if this field is inside a tabs section
							if(parentPanel[0].getParent('div').getParent('div').hasClass('pane-sliders')){
								//show the parent Panel body
								parentPanel[0].getParent('div').getParent('div.pane-sliders').getChildren('div.panel').each(function(tabBody){
									if(tabBody.getFirst('div.pane-slider') == parentPanel[0]){
										tabBody.getFirst('div.pane-slider').removeClass('pane-hide');
										tabBody.getFirst('div.pane-slider').addClass('pane-down');
										tabBody.getFirst('div.pane-slider').setStyle('height', 'auto');
										tabBody.getFirst('h3.title').removeClass('pane-toggler');
										tabBody.getFirst('h3.title').addClass('pane-toggler-down');
									}else{
										tabBody.getFirst('div.pane-slider').addClass('pane-hide');
										tabBody.getFirst('div.pane-slider').setStyle('height', '0px');
										tabBody.getFirst('div.pane-slider').removeClass('pane-down');
										tabBody.getFirst('h3.title').addClass('pane-toggler');
										tabBody.getFirst('h3.title').removeClass('pane-toggler-down');								
									}
								});
							}
						}
						found = 1;
					}
				}
			}
		}, this);
	}
});