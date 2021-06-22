<?php
 return array (
  'driver' => env('MAIL_DRIVER', 'sendmail '),
  'host' => env('MAIL_HOST', 'localhost'),
  'port' => env('MAIL_PORT', 2525 ),
  'from' => 
  array (
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Weblizar SHOP'),
  ),
  'encryption' => env('MAIL_ENCRYPTION', 'tls'),
  'username' => env('MAIL_USERNAME'),
  'password' => env('MAIL_PASSWORD'),
  'sendmail' => '/usr/sbin/sendmail -bs',
  
   'markdown' => 
  array (
    'theme' => 'default',
    'paths' => 
    array (
      0 => '/jn/jewelnidhi.com/resources/views/vendor/mail',
    ),
  ),
) ;