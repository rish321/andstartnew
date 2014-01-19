function chk_rating()
{

//	return false;	


	form = document.forms[0];
	
	mobileno = form.mobileno;
	id_proof = form.id_proof;
	rating = form.rating;


	if(mobileno.value == '' || id_proof.value == '' || rating.value == '' )
	{
		alert("You must provide all the requested field.");
		return false;
	}
	
	//return true;
	data(form);
// to be removed
	return false; 
}


function data(form)
{
	mobileno = form.mobileno.value;
	id_proof = form.id_proof.value;
	rating = form.rating.value;

	var params = "{mobileno:'"+mobileno+"', id_proof:'"+id_proof+"', rating:"+rating+" }";

//	alert(params);
//	return false;
	
	var json = JSON.stringify(eval('('+params+')'));
	
	if (typeof String.prototype.startsWith != 'function') {
		// see below for better implementation!
		String.prototype.startsWith = function (str){
			return this.indexOf(str) == 0;
		};
	}


	$.post('http://10.2.8.180/andsmartnew/rating_result.php',{ json_obj:json }, 
		function(data){
	//		alert(data);
			if(data.startsWith("Success"))
			{
				alert("Rating Successfully Recieved.");
	//			document.getElementById("responce_table").innerHTML=data;
				//alert(data);
				window.location.replace("http://10.2.8.180/andsmartnew/index.html");
			}
			else	
				alert("Either phoen no. don't match with ID proof or phoen no. does not exist.");
		}
	  ); 

	
	/*
//	alert(params);return false;

	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	//alert("here2");
	
	xmlhttp.open("POST", url, true);
//	xmlhttp.open("GET",url,true);

	//Send the proper header information along with the request
	xmlhttp.setRequestHeader("Content-type", 'text/html; charset=utf-8');
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//document.getElementById("responce").innerHTML=xmlhttp.responseText;
			var df = xmlhttp.responseText;
			if(df == "Success")
				window.location.replace("/andsmart/index.html?responce=Thanks%20for%20registration.");
				//alert('success');
			else
				alert('fail');
		}
	}
	
	//http.onreadystatechange = function() {//Call a function when the state changes.
	//	if(http.readyState == 4 && http.status == 200) {
	//		alert(http.responseText);
	//	}
	//} 

	xmlhttp.send(params); */
 
//	var xmlhttp;
//	xmlhttp.open("GET","http://10.2.8.180/PHP/getAllCustomers.php",true);
//	xmlhttp.send(); 
} 
