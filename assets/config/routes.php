<?php

return array(
    'apisetmetval' => array('/api/setmetval/token=<token>&met_id=<met_id>&val=<val>(&dim_id=<dim_id>)(&dim_val=<dim_val>)', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'setmetval'
        )
    ),
    'apigetchart' => array('/api/getchart/token=<token>&met_id=<met_id>&period=<period>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getchart'
        )
    ),
    'pairs' => array('/hq/getassetpairs/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'getassetpairs'
        )
    ),
    'ohlc' => array('/hq/getohlc/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'getohlc'
        )
    ),
    'orderbook' => array('/hq/getorderbook/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'getorderbook'
        )
    ),
    'hq' => array('/hq/hq/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'hq'
        )
    ),
    'manageorders' => array('/hq/manageorders/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'manageorders'
        )
    ),
    'addorder' => array('/hq/addorder/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'addorder'
        )
    ),
    'stoploss' => array('/hq/stoploss/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'stoploss'
        )
    ),
    'updatebasebal' => array('/hq/updatebasebal/exchcode=<exchcode>', array(
            //Make 'fairies' the default controller
            'controller' => 'hq',
            //Default action
            'action' => 'updatebasebal'
        )
    ),
    'getusertoken' => array('/main/getusertoken/username=<username>&password=<password>', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'getusertoken'
        )
    ),
    'apigetdimlist' => array('/api/getdimlist/token=<token>&met_id=<met_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getdimlist'
        )
    ),
    'apigetmetriclist' => array('/api/getmetriclist/token=<token>&proc_id=<proc_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getmetriclist'
        )
    ),
    'apigetusermodellist' => array('/api/getusermodellist/token=<token>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getusermodellist'
        )
    ),
    'apigetprocmet' => array('/api/getprocmet/proc_id=<proc_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getprocmet'
        )
    ),
    'apigetrqstlist' => array('/api/getrqstlist/sys_id=<sys_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getrqstlist'
        )
    ),
    'apiaddprocess' => array('/api/addprocess/proc_id=<proc_id>&dom_id=<dom_id>&mod_id=<mod_id>&x=<x>&y=<y>&name=<name>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'addprocess'
        )
    ),
    'apicheckemail' => array('/api/checkemail/name=<name>&domain=<domain>', array(
            //Make 'fairies' the default controller
            'controller' => 'apiopen',
            //Default action
            'action' => 'checkemail'
        )
    ),
    'apicheckname' => array('/api/checkname/name=<name>', array(
            //Make 'fairies' the default controller
            'controller' => 'apiopen',
            //Default action
            'action' => 'checkname'
        )
    ),
    'apiregister' => array('/api/register/name=<name>&email=<email>&psw=<psw>', array(
            //Make 'fairies' the default controller
            'controller' => 'apiopen',
            //Default action
            'action' => 'register'
        )
    ),
    'apilogin' => array('/apiopen/login/username=<username>&password=<password>', array(
            //Make 'fairies' the default controller
            'controller' => 'apiopen',
            //Default action
            'action' => 'login'
        )
    ),
    'metriclist' => array('/metriclist/proc_id=<proc_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'metriclist'
        )
    ),
    'mainactivate' => array('/main/activate/uid=<uid>&act_key=<act_key>', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'activate'
        )
    ),
    'apisendemail' => array('/api/sendemail', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'sendemail'
        )
    ),
    'editprocess' => array('/editprocess/proc_id=<proc_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'editprocess'
        )
    ),
    'getprocesslist' => array('/api/getprocesslist/token=<token>&mod_id=<mod_id>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'getprocesslist'
        )
    ),
    'editmetric' => array('/editmetric/met_id=<met_id>(&mode=<mode>)', array(
            //Make 'fairies' the default controller
            'controller' => 'editmetric',
            //Default action
            'action' => 'index'
        )
    ),
    'apitoken' => array('/api/tokens/api=<api>',
        array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'tokens'
        )
    ),
    'apiaddprocesslayer' => array('/api/addprocesslayer/layer_id=<layer_id>&proc_id=<proc_id>&type=<type>&x=<x>&height=<height>&name=<name>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'addprocesslayer'
        )
    ),
    'default' => array('(/<controller>(/<action>(/lang=<lang>)(/guid=<guid>)))', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'index'
        )
    ),
    'api_pushevent' => array('/api/pushevent/token=<token>', array(
            //Make 'fairies' the default controller
            'controller' => 'api',
            //Default action
            'action' => 'pushevent'
        )
    ),
    'signup' => array('(/main(/signup(&lng=<lang>)))', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'signup'
        )
    ),
    'eula' => array('(/<controller>(/type=<type>))', array(
            //Make 'fairies' the default controller
            'controller' => 'eula',
            //Default action
            'action' => 'index'
        )
    ),
    'newphoto' => array('(/main/newphoto(/obj_id=<obj_id>))', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'newphoto'
        )
    ),
    'landing' => array('(/landing)', array(
            //Make 'fairies' the default controller
            'controller' => 'main',
            //Default action
            'action' => 'landing'
        )
    ),
);
