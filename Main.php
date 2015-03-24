<?php
        require 'RestClient.php';
     
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://api.zanox.com/json/2011-03-01/products?connectid=43EEF0445509C7205827&items=5&q=nike");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        echo $output;
        // close curl resource to free up system resources
        curl_close($ch);     

       $c = new RestClient();
       $nonce = $c->getNonce();
       $date = gmdate('D, d M Y H:i:s T');
       //$sign = $c->getSignature('GET', $uri, $nonce);

       echo $nonce;
       echo $date;
       //echo $sign;

?>
