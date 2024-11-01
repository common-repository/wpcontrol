jQuery( document ).ready( function( event ) {
	
	window.onload = function(){wpcontrol_disable_selection(document.body);};
	document.oncontextmenu = wpcontrol_nocontext;
	document.onkeydown = wpcontrol_disable_enter_key;
	document.onselectstart = wpcontrol_disable_copy_IE;
	if(navigator.userAgent.indexOf('MSIE')==-1) {
		document.onmousedown = wpcontrol_disable_copy;
		document.onclick = wpcontrol_reenable;
	}

	function wpcontrol_nocontext(e) {
		return false;
	}
	
	function wpcontrol_disable_selection(target){
		//For IE
		if (typeof target.onselectstart!="undefined")
		target.onselectstart = wpcontrol_disable_copy_IE;
		
		//For Firefox
		else if (typeof target.style.MozUserSelect!="undefined")
		{target.style.MozUserSelect="none";}
		
		//All other
		else
		target.onmousedown=function(){return false}
		target.style.cursor = "default";
	}

	function wpcontrol_disable_copy_IE() {
		var e = e || window.event;
		var elemtype = window.event.srcElement.nodeName;
		elemtype = elemtype.toUpperCase();
		if (elemtype == "IMG") {return false;}
		if (elemtype != "TEXT" && elemtype != "TEXTAREA" && elemtype != "INPUT" && elemtype != "PASSWORD" && elemtype != "SELECT" && elemtype != "OPTION" && elemtype != "EMBED"){
			return false;
		}
	}

	function wpcontrol_disable_enter_key(e){
		var elemtype = e.target.tagName;
		elemtype = elemtype.toUpperCase();
		if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED"){
			elemtype = 'TEXT';
		}
		
		if (e.ctrlKey){
		var key;
		if(window.event)
			key = window.event.keyCode;     //IE
		else
			key = e.which;
		
		if (elemtype!= 'TEXT' && (key == 26 || key == 43 || key == 65 || key == 67 || key == 73 || key == 83 || key == 85 || key == 86  || key == 88 || key == 97 || key == 99 || key == 120)){
			return false;
		}else
			return true;
		}
	}

	function wpcontrol_disable_copy(e){	
		var e = e || window.event;
		var elemtype = e.target.tagName;
		elemtype = elemtype.toUpperCase();
		
		if (elemtype == "TEXT" || elemtype == "TEXTAREA" || elemtype == "INPUT" || elemtype == "PASSWORD" || elemtype == "SELECT" || elemtype == "OPTION" || elemtype == "EMBED"){
			elemtype = 'TEXT';
		}
		var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
		if (elemtype == "IMG" && e.detail >= 2) {return false;}
		if (elemtype != "TEXT") {
			if (isSafari)
				return true;
			else
				return false;
		}	
	}

	function wpcontrol_reenable(){
		return true;
	}

});





