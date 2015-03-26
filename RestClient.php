<?php
        class RestClient {

	 /**
	* zanox connect ID
	*
	* @var string $connectId zanox connect id
	*
	* @access private
	*/
	private $connectId = '';
	/**
	* zanox shared secret key
	*
	* @var string $secrectKey secret key to sign messages
	* @access private
	*/
	private $secretKey = '';


/**
* Set the connectId
*
* @param string $connectId zanox connectId
*
* @access public
* @final
*
* @return void
*/
final public function setConnectId( $connectId )
{
$this->connectId = $connectId;
}
/**
* Returns the connectId
*
* @access public
* @final
*
* @return string zanox connect id
*/
final public function getConnectId()
{
return $this->connectId;
}

/**
* Set SecretKey
*
* @param string $secretKey zanox secret key
*
* @access public
* @final
*
* @return void
*/
final public function setSecretKey( $secretKey )
{
$this->secretKey = $secretKey;
}



        /**
	* Returns hash based nonce.
	*
	* @see http://en.wikipedia.org/wiki/Cryptographic_nonce
	*
	* @access public
	* @final
	*
	* @return string md5 hash-based nonce
	*/
	final public function getNonce ()
	 {
	   $mt = microtime();
	   $rand = mt_rand();
	   return md5($mt . $rand);
	}

	 /**
	* Returns the crypted hash signature for the message.
	*
	* Builds the signed string consisting of the rest action verb, the uri used
	* and the timestamp of the message. Be aware of the 15 minutes timeframe
	* when setting the time manually.
	*
	* @param string $service service name or restful action
	* @param string $method method or uri
	* @param string $nonce nonce of request
	*
	* @access public
	* @final
	*
	* @return string encoded string
	*/
	final public function getSignature( $service, $method, $nonce, $timestamp )
	 {
	   $sign = $service . strtolower($method) . $timestamp;
	   if ( !empty($nonce) )
	    {
	     $sign .= $nonce;
	    }
	   $hmac = $this->hmac($sign);
	   if ( $hmac )
	    {
	    return $hmac;
	    }
	   return false;
	}

        /**
        * Creates secured HMAC signature of the message parameters.
        *
	* Uses the hash_hmac function if available (PHP needs to be >= 5.1.2).
	* Otherwise it uses the PEAR/CRYP_HMAC library to sign and crypt the
	* message. Make sure you have at least one of the options working on your
	* system.
	*
	* @param string $message message to sign
	*
	* @access private
	*
	* @return string signed sha1 message hash
	*/
	private function hmac( $mesgparams )
	 {
	  if ( function_exists('hash_hmac') )
 	   {
            $hmac = hash_hmac('sha1', utf8_encode($mesgparams), $this->secretKey);
            $hmac = $this->encodeBase64($hmac);
           }
	  else
           {
	    require_once 'Crypt/HMAC.php';
            $hashobj = new Crypt_HMAC($this->secretKey, "sha1");
            $hmac = $this->encodeBase64($hashobj->hash(utf8_encode($mesgparams)));
           }
          return $hmac;
        }


 	/**
	* Encodes the given message parameters with Base64.
	*
	* @param string $str string to encode
	*
	* @access private
	*
	* @return encoded string
	*/
	private function encodeBase64( $str )
	 {
	  $encode = '';
	  for ($i=0; $i < strlen($str); $i+=2){
	    $encode .= chr(hexdec(substr($str, $i, 2)));
	  }
	  return base64_encode($encode);
	 }



        }//end class

?>
