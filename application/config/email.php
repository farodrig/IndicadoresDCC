<?php

$config['protocol'] = 'smtp'; // puede ser mail, sendmail, or smtp
$config['mailpath'] = '/usr/sbin/sendmail'; // The server path to Sendmail
$config['smtp_host'] = 'ssl://smtp.gmail.com'; //change this
$config['smtp_port'] = '465';
$config['smtp_user'] = ''; //change this
$config['smtp_pass'] = ''; //change this
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n"; //use double quotes to comply with RFC 822 standard

?>