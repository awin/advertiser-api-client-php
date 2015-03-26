<?php
        require 'RestClient.php';


        define('APP_URL', 'https://advertiser.api.zanox.com/advertiser-api/2015-03-01');
        define('BASE_REST_APP', '/report/program/');
        define('ARGUMENTS_NUMBER', '6');

        if (sizeof($argv) != ARGUMENTS_NUMBER) {
            echo 'Wrong number of arguments. Correct usage: Main.php --[header|url] PROGRAM_ID CONNECT_ID SECRET_KEY GROUP_BY"';
            exit;
        }

        $auth_type = $argv[1];
        $programId = $argv[2];
        $connectId = $argv[3];
        $secretKey = $argv[4];
        $groupBy = $argv[5];

        if (!isGroupByValid($groupBy)) {
            $groupBy = 'day';
            echo 'Group by parameter invalid, default group by: day will be used.';
        }

        $c = new RestClient();
        $c->setConnectId($connectId);
        $c->setSecretKey($secretKey);
        $nonce = $c->getNonce();
        $timestamp = gmdate('D, d M Y H:i:s T');
        $sign = $c->getSignature('GET', '/report/program/' . $programId, $nonce, $timestamp);
        $d = strtotime("-31 days");
        $fromdate = date("Y-m-d", $d);
        $d = strtotime("yesterday");
        $todate = date("Y-m-d", $d);

       $c = new RestClient();

       $c->setConnectId($connectId);
       $c->setSecretKey($secretKey);

       $nonce = $c->getNonce();
       $timestamp = gmdate('D, d M Y H:i:s T');
       $sign = $c->getSignature('GET', '/report/program/' . $programId, $nonce, $timestamp);
	
      // create curl resource
      $ch = curl_init();

      $parameter = array(
           'groupby'=>$groupBy,
           'fromdate'=>$fromdate,
           'todate'=>$todate,
           'connectid'=>$connectId,
           'date'=>$timestamp,
           'nonce'=>$nonce,
           'signature'=>$sign);

      $parameterQuery = http_build_query($parameter);

      // set url
      $url = APP_URL . BASE_REST_APP . $programId . '?' . $parameterQuery;
      echo $url;
      curl_setopt($ch, CURLOPT_URL, $url);


       //return the transfer as a string
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       // $output contains the output string
       $output = curl_exec($ch);

       echo $output;

       // close curl resource to free up system resources
       curl_close($ch);

       function isGroupByValid($groupBy) {
            $validGroupByArray = array('day', 'month', 'adspace', 'admedium', 'adspace,admedium', 'admedium,adspace');
            if (in_array($groupBy, $validGroupByArray)) {
                return true;
            }
            return false;
       }

?>
