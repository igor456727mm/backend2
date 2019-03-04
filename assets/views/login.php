<link href="/css/september.css" rel="stylesheet">
<script src="/js/bizaya.js"></script> 
<div class="container">
    <div class="row" style="margin-top: 0px;">
        <div class="col-md-4">
        </div>

        <div class="col-md-4">

        </div>
        <div class="col-md-4">
        </div>
    </div>
    <form method="POST">
    <div class="row" style="margin-top: 100px;">
        <div class="col-md-4">
        </div>
        <div class='col-md-4'>
    
        <fieldset>
            <div style="text-align: center; font-size: 20px; margin-top: 0px;">
                <? echo 'SIGN IN'; ?> </div>
            <span><? echo 'USERNAME'; ?></span><br/>
            <input class='signup_input' type="text" name="username" placeholder="Your name..."><br/>
            <span id='checking_psw' style="font-size: 10px; color: red;"></span><br/>
            <span><? echo 'PASSWORD'; ?></span><br/>
            <input class='signup_input' type="password" name="password">
            <span id='checking_psw' style="font-size: 10px; color: red;"></span><br/>
            <br/>
            
        </fieldset>
    
        </div>

        <div class="col-md-4">

        </div>
    </div>
        <div class="row">
                <div class="col-md-12" style="text-align:center;">
                    <button submitform='true' type="submit" id='submit' class="btn btn-success" style="width: auto;"><? echo 'Sign in'; ?></button>
                </div>

            </div>
        </form>
</div>