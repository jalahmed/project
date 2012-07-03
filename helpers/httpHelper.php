<?php
/**
 * acess control list class to manage user and group rights
 *
 * @copyright  2010 naraia.net (Timo Mayer)
 * @version    $Revision$ - $Date$
 * @author     $Author$
 * @since      File available since Release 1.0
 */



class HttpHelper
{

        public static function setHeader($name, $value, $overwrite = true)
        {
                header($name . ': ' .$value, $overwrite);
        }

        public static function setHttpError404()
        {
                header("HTTP/1.0 404 Not found");
        }

        public static function setHttpError403()
        {
                header("HTTP/1.0 403 Not allowed");
        }

        public static function setHttpError500()
        {
                header("HTTP/1.0 500 Internal Server Error");
        }

        public static function setRedirect($url)
        {
                header('Location: ' .$url);
        }

        /* used to removeMagicQuotes */
        private static function stripSlashesDeep($value)
        {
                $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
                return $value;
        }

        public static function removeMagicQuotes()
        {
                if(get_magic_quotes_gpc()) {
                        //only use request
                        $_REQUEST = self::stripSlashesDeep($_REQUEST);
                }
        }
}
?>