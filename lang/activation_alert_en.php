<?php
namespace GDO\ActivationAlert\lang;
return [
    'cfg_activation_alert_mail_receiver' => 'Mail receiver for activation alerts',
        
    'mail_subj_user_activated_staff' => '[%s] New user activated',
    'mail_body_user_activated_staff' => '
    Hello %s,
    
    There has been a user activated on %s.
    
    Username: %s
    IP: %s
    
    Kind Regards,
    The %2$s robot',
];
