
// ================= setting cookie ==================//
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// ================= getting cookie ==================//

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}



jQuery('.submit-vote').click(function(){
	var updatePersonVal = jQuery('input[name=polling]:checked').val();
	if (updatePersonVal == undefined) {
		alert("No voting sellected");
		return 0;
	}
	// ===================== cookies set =======================//
	var gettingCookie = getCookie("simple-polling");
	if (gettingCookie != "") {
		alert("You already contributed in a voting");
		return 0;
	}else{
		setCookie("simple-polling", "1", "360");
	}
	//getting person names
	var personA = jQuery('.p-name-1').text();
	var personB = jQuery('.p-name-2').text();
	//setting loader image
	jQuery('.inner-polling-simple').html('<img src="https://i.pinimg.com/originals/ed/23/68/ed23685339ada1b6d88008cbe1a11e98.gif" class="img-loader-polling">');
	jQuery.ajax({
		url : simple_polling_aj_var.simple_polling_ajax_url,
		type : 'post',
		data : {
			action : 'simple_polling_ajax_call_add_vote',
			updatePersonVal 	 : updatePersonVal
		},
		success : function( response ) {
			var result = response.split(',');
			var resultA = result[0];
			var resultB = result[1];
			console.log(result);
			var total 	= parseInt(resultA) + parseInt(resultB);
			if (resultA == "0" || resultB == "0") {
			var percentageA = Math.round(resultA / total * 100);
			var percentageB = Math.round(resultB / total * 100);	
		}else{

			//checking 0 value
			if (resultA == "0"){
				percentageA = 0
			}else{
				var percentageA = Math.round(resultA / total * 100);
			}

			if (resultB == "0"){
				percentageB = 0
			}else{
				var percentageB = Math.round(resultB / total * 100);
			}
			
			
		}
			
			 
		
			jQuery('.inner-polling-simple').html('<div class="simple-polling-container"><div class="result-simple-polling"><h3 class="person-names">'+personA+' ('+resultA+' Votes - '+percentageA+'%)</h3></div><div class="background-polling-line"><div class="result-a-percentage percentage-result" style="width:'+percentageA+'%"> </div></div><div class="result-simple-polling"><h3 class="person-names">'+personB+' ('+resultB+' Votes- '+percentageB+'%)</h3></div><div class="background-polling-line"><div class="result-a-percentage percentage-result" style="width:'+percentageB+'%"> </div></div></div>');

		}
	});
});