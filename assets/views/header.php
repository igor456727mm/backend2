<?$this->token == ''?>
<div class="container">

    <div class="row" style="color:white; ">

        <div class="col-md-6">
            <a href='/main'>
                <img src="/img/logo.png">
                
                <label style="font-size: 10px"> 
                    
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




                    <a href='/main/login' style=" padding-left: 5px;"> 
                        <?
                        if ($this->token == '') {
                          echo '';
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
                       echo '';

                       } else {
                        echo '';
                       }
                       ?>
                </a>
            </div>
        </div>

    </div>

</div>

</div>
