<?php
    require 'AuthenticationUtil.php';

    //php Run.php 1803 676F47B42F2819102E7A a9A63Af80b9D47+8ae84a3929776ba/9479fCE46 adspace
    define('APP_URL', 'https://advertiser.api.zanox.com/advertiser-api/2015-03-01');
    define('BASE_REST_APP', '/report/program/');
    define('ARGUMENTS_NUMBER', '5');

    if (sizeof($argv) != ARGUMENTS_NUMBER)
    {
        echo 'Wrong number of arguments. Correct usage: Run.php PROGRAM_ID CONNECT_ID SECRET_KEY GROUP_BY';
        exit;
    }

    $programId = $argv[1];
    $connectId = $argv[2];
    $secretKey = $argv[3];
    $groupBy = $argv[4];

    if (!isGroupByValid($groupBy))
    {
        $groupBy = 'day';
        echo "Group by parameter invalid, default group by: day will be used. \n";
    }

    $c = new AuthenticationUtil();
    $c->setConnectId($connectId);
    $c->setSecretKey($secretKey);
    $nonce = $c->getNonce();
    $timestamp = gmdate('D, d M Y H:i:s T');
    $sign = $c->getSignature('GET', '/report/program/' . $programId, $nonce, $timestamp);
    date_default_timezone_set('UTC');
    $d = strtotime("-31 days");
    $fromdate = date("Y-m-d", $d);
    $d = strtotime("yesterday");
    $todate = date("Y-m-d", $d);

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
    curl_setopt($ch, CURLOPT_URL, $url);

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    echo $output;

    // close curl resource to free up system resources
    curl_close($ch);

    function isGroupByValid($groupBy)
    {
        $validGroupByArray = array('day', 'month', 'adspace', 'admedium', 'adspace,admedium', 'admedium,adspace');
        if (in_array($groupBy, $validGroupByArray))
        {
            return true;
        }
        return false;
    }

?>
