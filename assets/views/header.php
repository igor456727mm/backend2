
<div class="container">

    <div class="row" style="color:white; ">

        <div class="col-md-6">
            <a href='/hqex'>
                <label style="font-size: 40px"> 
                    HQEX
                </label>
                <label style="font-size: 10px"> 
                    BETA. Commision 0.25% until 15.03.2018.
                </label>
            </a> 
        </div>
        <div class="col-md-6 pull-right" style=" text-align: right; ">
            <div class="row <? if ($this->token == '') { ?>hidden<?}?>">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 hidden-sm" >


                </div>


            </div>
            <div class="row"> 
                <div class="col-md-7">

                </div>
                <div class="col-md-5" style="padding: 10px;">

                    <a href="#" onclick="changeLng('en');" 
                       style=" height: 12px;">Eng</a>
                    <span style=" height: 12px;">&nbsp;|</span>
                    <a href="#" onclick="changeLng('ru');" style=" height: 12px;">Рус</a>



                    <a href='/main/login' style=" padding-left: 5px;"> 
                        <?
                        if ($this->token == '') {
                        echo $localizator->__('Login');
                        } else {
                        echo $this->user->NAME;
                        }
                        ?>
                    </a>
                    <a href= <?
                       if ($this->token == '') {
                       echo '/main/signup';
                       } else {
                       echo '/main/logout';
                       }
                       ?>  style=" padding-left: 5px;"
                       > 
                       <?
                       if ($this->token == '') {
                       echo $localizator->__('Sign up');
                       } else {
                       echo $localizator->__('Logout');
                       }
                       ?>
                </a>
            </div>
        </div>

    </div>

</div>

</div>
