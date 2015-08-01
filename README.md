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

The first argument must be the attribute in the X.509 certificate that you want to use as username.


In order to let the webserver validate the certificate and pass it to owncloud, it must be configured properly.

Example Nginx configuration:
```

...
        ssl_client_certificate /etc/nginx/test-ca.crt;
        ssl_verify_client optional;
        ssl_verify_depth 2;
        ssl_crl   /etc/nginx/crl.pem;
...

        location ~ ^(.+?\.php)(/.*)?$ {
            try_files $1 = 404;

            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$1;
            fastcgi_param PATH_INFO $2;
            fastcgi_param HTTPS on;
            fastcgi_param SERVER_PORT $server_port;
            fastcgi_param SERVER_NAME $server_name;
            fastcgi_param SSL_CLIENT_CERT $ssl_client_cert;      ## Those two parameters are important and
            fastcgi_param SSL_CLIENT_VERIFY $ssl_client_verify;  ## must be passed to PHP in order to
                                                                 ## user_certificate to work
            fastcgi_pass unix:/home/paul/projects/owncloud/php-fpm.sock;
        }
```


## Issues

Once the user selects a certificate in the web browser, he can not logout. The browser will always send the
certificate and there is no way to tell the browser to stop (except for restarting the browser).
