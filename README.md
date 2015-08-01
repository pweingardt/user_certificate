# User Certificate

This owncloud app allows you to use client certificates instead of passwords for authentication.
Let your webserver validate the certificate.

Place this app in **owncloud/apps/** and configure your `config.php` properly, e.g.

```
  'user_backends' =>
  array (
    0 =>
    array (
      'class' => 'OCA_User_Certificate',
      'arguments' =>
      array (
        0 => 'CN',
      ),
    ),
  ),
```

The first parameter must be the attribute in the X.509 certificate that you want to use as username.


## Issues

Once the user selects a certificate in the web browser, he can not logout. The browser will always send the
certificate and there is no way to tell the browser to stop (except for restarting the server).
