<?
return array(
    'default' => array(
        'model' => 'user',
 
        //Login providers
        'login' => array(
            'password' => array(
                'login_field' => 'NAME',
                'password_field' => 'PSW'
            ),
            'facebook' => array(
 
                //Facebook App ID and Secret
                'app_id' => '138626646318836',
                'app_secret' => '49451a54b61464645321d9fbcb70000',
 
                //Permissions to request from the user
                'permissions'  => array('user_about_me'),
 
                'fbid_field' => 'fb_id',
 
                //Redirect user here after he logs in
                'return_url' => '/main'
            )
        ),
 
        //Role driver configuration
        'roles' => array(
            'driver' => 'relation',
            'type' => 'has_many',
 
            //Field in the roles table
            //that holds the models name
            'name_field' => 'code',
            'relation' => 'roles'
        )
    )
);