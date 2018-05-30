<link href="/css/september.css" rel="stylesheet">
<link rel="stylesheet" href="/css/jquery-ui.css">
<script src="/js/jquery-1.10.2.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/jquery-easyModal.js"></script>
<!--
<link rel="stylesheet" href="/css/joint.min.css" />
<script src="/js/joint.min.js"></script>
!-->

<div class="container">
    <div class="row" style="margin-top: 0px;">
        <div class="col-md-4">
        </div>

        <div class="col-md-4">

        </div>
        <div class="col-md-4">
        </div>
    </div>
    <div class="row" style="margin-top: 100px;">
        <div class="col-md-4">
        </div>
        <div class='col-md-4'>

            <span>EMAIL</span><br/>
            <input class='signup_input' id='email' autocomplete="off" 
                   onkeyup='checkEmail(this.value);' onchange='checkEmail(this.value);' 
                   type="text" name="email" placeholder="<? echo $localizator->__('Your email...'); ?>" value=''>
            <span id='checkemail' class="glyphicon">
            </span>
            <span id='checking_email' style="font-size: 10px; margin-top: 0px; color: red;"> </span><br/>
            <span><? echo $localizator->__('PASSWORD'); ?></span><br/>
            <input  class='signup_input' id='psw' autocomplete="off" class="password" 
                    onkeyup='checkPassword(this.value);' onchange='checkPassword(this.value);' 
                    type="password" placeholder="<? echo $localizator->__('Your password...'); ?>"  style="height: 28px;">
            <span id='checkpsw' class="glyphicon">
            </span>
            <span id='checking_psw' style="font-size: 10px; color: red;"></span><br/>


            <div class="row">
                <div class="col-md-12" style="text-align:center;">
                    <button submitform='true' id='submit' disabled onclick="submitUserInfo();" class="btn btn-success" style="width: auto;"><? echo $localizator->__('Create account'); ?></button>
                </div>

            </div>


            <span id='registered' ></span>
            <span id='registering' ></span>
            <!-- </form>!-->

        </div>

        <div class="col-md-4">

        </div>
    </div>
</div>


<script>
    var ready_email = false;
    var ready_name = true;
    var ready_psw = false;

    function checkEmail(email)
    {

        var name = email.substring(0, email.indexOf('@'));
        var domain = email.substring(email.indexOf('@') + 1, email.length);
        if (name.length * domain.length == 0)
        {
            document.getElementById('checking_email').innerHTML = 
                    '<? echo $localizator->__('The email format is wrong.'); ?>';
            document.getElementById('checkemail').src = '/img/email2.png';
            ready_email = false;
            checkFields();
            return 0;
        }
        var domain = email.substring(email.indexOf('@') + 1, email.length);

        var $url = "/apiopen/checkemail";

        console.log($url);
        document.getElementById('checking_email').innerHTML = '<? echo $localizator->__('Checking...'); ?>';

        httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', $url, true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send("name=" + name + "&domain=" + domain);
        //httpRequest.send(null);

        httpRequest.onreadystatechange = function () {
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
                        document.getElementById('checking_email').innerHTML = 
                                '<? echo $localizator->__('The email exists. Please choose another email.'); ?>';
                        document.getElementById('checkemail').src = '/img/email2.png';
                        ready_email = false;
                    }
                } else {
                    document.getElementById('checking_email').innerHTML = 
                            '<? echo $localizator->__('No connection to server. Please try again.'); ?>';
                    document.getElementById('checkemail').src = '/img/email2.png';
                    ready_email = false;
                }
            } else {
                // still not ready
            }
            checkFields();
        };

    }

    function checkName(name)
    {

        if (name.length == 0)
        {
            document.getElementById('checking_name').innerHTML = '<? echo $localizator->__('The name must be filled.'); ?>';
            document.getElementById('checkname').src = '/img/email2.png';
            ready_name = false;
            checkFields();
            return 0;
        }

        var $url = "/apiopen/checkname/";
        console.log($url);
        document.getElementById('checking_name').innerHTML = '<? echo $localizator->__('Checking...'); ?>';

        httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', $url, true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send("name=" + name);


        httpRequest.onreadystatechange = function () {
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
                        document.getElementById('checking_name').innerHTML =
                                '<? echo $localizator->__('The name exists. Please choose another name.'); ?>';
                        document.getElementById('checkname').src = '/img/email2.png';
                        ready_name = false;
                    }
                } else {
                    document.getElementById('checking_name').innerHTML = 
                            '<? echo $localizator->__('No connection to server. Please try again.'); ?>';
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
            document.getElementById('checking_psw').innerHTML = 
                    '<? echo $localizator->__('The password must not be less then 8 symbols.'); ?>';
            ready_psw = false;
        }
        checkFields();
    }

    function checkFields()
    {
        if (ready_email && ready_psw)
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
        var $url = "/apiopen/register/";
        document.getElementById('registering').innerHTML = 'Registering...';
        var email = document.getElementById('email').value;
        var psw = document.getElementById('psw').value;

        httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', $url, true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send("email=" + email + "&psw=" + psw);

        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState == 4) {
                if (httpRequest.status == 200) {
                    console.log(httpRequest.responseText);
                    $j=JSON.parse(httpRequest.responseText);
                    if ($j['Result'] == 'token')
                    {
                        document.getElementById('registering').innerHTML = 'Your account is registered. Please activate it with the link in your email.';
                        document.getElementById('registered').src = '/img/email1.png';
                        document.location='/hqex';
                        ready_name = true;
                    }
                    else
                    {
                        document.getElementById('registering').innerHTML = 'Some problem ocurs. Please contact support.';
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
</script>