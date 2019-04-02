<?php

return array(
    'default' => array(
 
        //Type can be either 'smtp', 'sendmail' or 'native'
        'type'        => 'native',
 
        //Settings for smtp connection
        'hostname'    => 'smtp.beget.ru',
        'port'        => '25',
        'username'    => 'info',
        'password'    => 'cnfaa99',
        'encryption'  => null, // 'ssl' and 'tls' are supported
        'timeout'  => null, // timeout in seconds, defaults to 5
 
        //Sendmail command (for sendmail), defaults to "/usr/sbin/sendmail -bs"
        'sendmail_command' => null,
 
        //Additional parameters for native mail() function, defaults to "-f%s"
        'mail_parameters'  => null
    )
);
