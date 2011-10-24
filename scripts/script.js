function disable_radio(rad) {
	if(rad=='No') {
		document.getElementById('attending2').disabled=false;
		document.getElementById('attending1').checked=false;
		document.getElementById('attending2').checked=true;
		document.getElementById('attending1').disabled=true;
	}
	if(rad=='Yes') {
		document.getElementById('attending1').disabled=false;
		document.getElementById('attending2').checked=false;
		document.getElementById('attending1').checked=true;
		document.getElementById('attending2').disabled=true;
	}
}
