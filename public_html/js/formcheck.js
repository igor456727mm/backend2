/*---------------------------------------------------------------------------------------*/
//
// Form fields checking module.
// Register fileds with formRegister(submit, fields);
// Check filed with checkField(name,pattern,value)
// There should be ajax procedures /api/checkname for every field with ajaxcheck_fl=true;
/*---------------------------------------------------------------------------------------*/

var ready = new Array();
var submit;
var fields = new Array();

fields=document.getElementsByTagName('formfield');

function checkPattern(pattern, value)
{
		var res= pattern.exec(value);
		
		if (res) {
				return true;
		}
		
		return false;
}


function checkField(name,pattern,value, ajaxcheck_fl)
{

		if (!checkPattern(pattern,value))
				{
						document.getElementById('checking_'+name).innerHTML = 'The '+name+' has wrong format.';
				  document.getElementById('checkname'+name).src = '/img/email2.png';
				  ready[name] = false;
				  checkFields();
				  return 0;
				}
				
		if (ajaxcheck_fl)
		{

		var $url = "/api/check"+name;
		document.getElementById('checking_'+name).innerHTML = 'Checking...';

		httpRequest = new XMLHttpRequest();
		httpRequest.open('POST', $url, true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send("name=" + name + "&domain=" + domain);
		//httpRequest.send(null);

		httpRequest.onreadystatechange = function() {
				if (httpRequest.readyState == 4) {
						if (httpRequest.status == 200) {
								console.log(httpRequest.responseText);
								if (httpRequest.responseText == 0)
								{
										document.getElementById('checking_email').innerHTML = '';
										document.getElementById('checkemail').src = '/img/email1.png';
										ready_email = true;
								}
								else
								{
										document.getElementById('checking_email').innerHTML = 'The email exists. Please choose another email.';
										document.getElementById('checkemail').src = '/img/email2.png';
										ready_email = false;
								}
						} else {
								document.getElementById('checking_email').innerHTML = 'No connection to server. Please try again.';
								document.getElementById('checkemail').src = '/img/email2.png';
								ready_email = false;
						}
				} else {
						// still not ready
				}
		 }
		}
		checkFields();
}

function checkName(name)
{

		if (name.length == 0)
		{
				document.getElementById('checking_name').innerHTML = 'The name must be filled.';
				document.getElementById('checkname').src = '/img/email2.png';
				ready_name = false;
				checkFields();
				return 0;
		}

		var $url = "/api/checkname/";
		console.log($url);
		document.getElementById('checking_name').innerHTML = 'Checking...';

		httpRequest = new XMLHttpRequest();
		httpRequest.open('POST', $url, true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send("name=" + name);


		httpRequest.onreadystatechange = function() {
				if (httpRequest.readyState == 4) {
						if (httpRequest.status == 200) {
								console.log(httpRequest.responseText);
								if (httpRequest.responseText == 0)
								{
										document.getElementById('checking_name').innerHTML = '';
										document.getElementById('checkname').src = '/img/email1.png';
										ready_name = true;
								}
								else
								{
										document.getElementById('checking_name').innerHTML = 'The name exists. Please choose another name.';
										document.getElementById('checkname').src = '/img/email2.png';
										ready_name = false;
								}
						} else {
								document.getElementById('checking_name').innerHTML = 'No connection to server. Please try again.';
								document.getElementById('checkname').src = '/img/email2.png';
								ready_name = false;
						}
				} else {
						// still not ready
				}
				checkFields();
		};

}

function checkPassword(psw)
{
		if ((psw.length >= 8))
		{

				document.getElementById('checkpsw').src = '/img/email1.png';
				document.getElementById('checking_psw').innerHTML = '';
				ready_psw = true;
		}
		else
		{

				document.getElementById('checkpsw').src = '/img/email2.png';
				document.getElementById('checking_psw').innerHTML = 'The password must not be less then 8 symbols.';
				ready_psw = false;
		}
		checkFields();
}

function checkFields()
{
		if (ready_email && ready_name && ready_psw)
		{
				document.getElementById('submit').disabled = false;
		}
		else
		{
				document.getElementById('submit').disabled = true;
		}
}

function submitUserInfo()
{
		document.getElementById('submit').disabled = true;
		var $url = "/api/register/";
		document.getElementById('registering').innerHTML = 'Registering...';
		var name = document.getElementById('name').value;
		var email = document.getElementById('email').value;
		var psw = document.getElementById('psw').value;

		httpRequest = new XMLHttpRequest();
		httpRequest.open('POST', $url, true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpRequest.send("name=" + name + "&email=" + email + "&psw=" + psw);

		httpRequest.onreadystatechange = function() {
				if (httpRequest.readyState == 4) {
						if (httpRequest.status == 200) {
								console.log(httpRequest.responseText);
								if (httpRequest.responseText == 1)
								{
										document.getElementById('registering').innerHTML = 'Your account is registered. Please activate it with the link in the email.';
										document.getElementById('registered').src = '/img/email1.png';
										ready_name = true;
								}
								else
								{
										document.getElementById('registering').innerHTML = 'Some problem ocurs. Please try later. ';
										document.getElementById('registered').src = '/img/email2.png';
										ready_name = false;
								}
						} else {
								document.getElementById('registering').innerHTML = 'No connection to server. Please try again.';
								document.getElementById('registered').src = '/img/email2.png';
								ready_name = false;
						}
				} else {
						// still not ready
				}
		};

}

