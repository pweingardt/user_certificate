<?php

class OCA_User_Certificate extends \OC_User_Backend implements \OCP\Authentication\IApacheBackend {

    private $attribute;

    function __construct($args) {
        $this->attribute = $args;
    }

    public function isSessionActive() {
        return $_SERVER['SSL_CLIENT_VERIFY'] === "SUCCESS";
    }

    public function getLogoutAttribute() {
        return null;
    }

    public function getCurrentUserId() {
        /**
         * only if the webserver has validated the certificate, proceed
         */
        if(isset($_SERVER['SSL_CLIENT_CERT']) &&
                $_SERVER['SSL_CLIENT_VERIFY'] === "SUCCESS") {

            /**
             * for some reasone nginx adds some leading whitespaces, remove them
             */
            $certificate = preg_replace("#^\s+#m", "", $_SERVER['SSL_CLIENT_CERT']);
            $parseResult = openssl_x509_parse($certificate);

            /**
             * Get the username from the certificate
             */
            $user = $parseResult["subject"][$this->attribute];


			//trigger creation of user home and /files folder
			\OC_Util::setupFS($user);
			\OC::$server->getUserFolder($user);

            return $user;
        } else {
            return null;
        }
    }

}

?>
