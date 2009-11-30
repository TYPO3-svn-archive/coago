function coago(counter, addressStatic, addressRegenerate, cachePeriod, onLoading, refresh) {
	
	var http_req_1 = false;
	if(navigator.appName === 'Microsoft Internet Explorer') {
		http_req_1 = new ActiveXObject('Microsoft.XMLHTTP');
	 } else {
		http_req_1 = new XMLHttpRequest();
	 }

	 http_req_1.open('GET', addressStatic, true);
	 http_req_1.setRequestHeader('Content-type', 'text/html');
	 http_req_1.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT' );	 			
	 http_req_1.send(null);

	 if(onLoading){
		document.getElementById('ncc-' + counter).innerHTML = onLoading;
	 }
	 http_req_1.onreadystatechange=function() {
			if(http_req_1.readyState === 4) {
		
					ageInSeconds = (new Date() - Date.parse(http_req_1.getResponseHeader('Last-Modified'))) / 1000;

					if( (http_req_1.status === 200) && ( (ageInSeconds < cachePeriod) || ('cachePeriod' === '0') ) ) {		  	
						document.getElementById('ncc-' + counter).innerHTML = http_req_1.responseText;
					} else {
						var http_req_2 = false;
						if(navigator.appName === 'Microsoft Internet Explorer') {
							http_req_2 =  new ActiveXObject('Microsoft.XMLHTTP');
						} else {
							http_req_2 = new XMLHttpRequest();
						}

						http_req_2.open('GET', addressRegenerate, true);
						http_req_2.setRequestHeader('Content-type', 'text/html');
						http_req_2.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT' );		 			
						http_req_2.send(null);

						http_req_2.onreadystatechange=function() {
							 if( http_req_2.readyState === 4 ) {
							 if( http_req_2.status === 200 ) {
								 document.getElementById('ncc-' + counter).innerHTML = http_req_2.responseText;
							 }
						  }
					   }
					}
				}
			 }		 
		if(refresh){
			setTimeout('coago('+counter+',\"'+addressStatic+'\",\"'+addressRegenerate+'\",'+cachePeriod+',\''+onLoading+'\','+refresh+')', refresh);
		}
}

function coagoftu(counter, addressStatic, onLoading, refresh) {
	
	var http_req_1 = false;
	if(navigator.appName === 'Microsoft Internet Explorer') {
		http_req_1 = new ActiveXObject('Microsoft.XMLHTTP');
	 } else {
		http_req_1 = new XMLHttpRequest();
	 }

	http_req_1.open('GET', addressStatic, true);
	http_req_1.setRequestHeader('Content-type', 'text/html');
	http_req_1.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT' );	 			
	http_req_1.send(null);

	if(onLoading){
		document.getElementById('ncc-' + counter).innerHTML = onLoading;
	}
	http_req_1.onreadystatechange=function() {
			if(http_req_1.readyState === 4) {				
						document.getElementById('ncc-' + counter).innerHTML = http_req_1.responseText;
					}
	}
			 		 
	if(refresh){
		setTimeout('coagoftu('+counter+',\"'+addressStatic+'\",\''+onLoading+'\','+refresh+')', refresh);
	}
}

function coagoGetCookie(c_name){
if (document.cookie.length>0) {
  c_start=document.cookie.indexOf(c_name + '=');
  if (c_start!=-1) {
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(';',c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  }
return '';
}