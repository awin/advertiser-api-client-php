<?php
        require 'RestClient.php';
     
	//java -jar target/advertiser-api-client-1.0-SNAPSHOT.jar --url 1803 676F47B42F2819102E7A a9A63Af80b9D47+8ae84a3929776ba/9479fCE46 adspace

      $app_url = 'https://advertiser.api.zanox.com/advertiser-api/2015-03-01';

	//generate authorization:
       $connectId = '676F47B42F2819102E7A';
       $secretKey = 'a9A63Af80b9D47+8ae84a3929776ba/9479fCE46';
       $programId = '1803';

       $c = new RestClient();

       $c->setConnectId($connectId);
       $c->setSecretKey($secretKey);

       $nonce = $c->getNonce();
       $timestamp = gmdate('D, d M Y H:i:s T');
       $sign = $c->getSignature('GET', '/report/program/', $nonce, $timestamp);
	
         $parameter = array(
              'groupby'=>'adspace',
	      'fromdate'=>'2015-03-01',
	      'todate'=>'2015-03-15',
	      'connectid'=>$connectId,
              'date'=>$timestamp,
              'nonce'=>$nonce,
              'signature'=>$sign);


 	if ( is_array($parameter) ) {
          $parameter_query = http_build_query($parameter); 
	}


       //echo $nonce;
       //echo $timestamp;
       //echo $sign;

      // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $app_url . '/report/program/' . $programId . '?'. $parameter_query);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

	 curl_close($ch);  
	echo $output;


?>
