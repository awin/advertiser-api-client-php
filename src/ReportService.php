<?php

class ReportService
{
    private $BASE_REST_APP = '/report/program/';
    private $ARGUMENTS_NUMBER = 4;
    private $arguments;


    function __construct($args)
    {
        $this->arguments = $args;
    }

    public function getReportData()
    {

        if (sizeof($this->arguments) != $this->ARGUMENTS_NUMBER) {
            error_log('Wrong number of arguments. Correct usage: ./src/AdvertiserApiClient.php SERVICE_TYPE PROGRAM_ID CONNECT_ID SECRET_KEY GROUP_BY');
            exit(1);
        }

        $programId = $this->arguments[0];
        $connectId = $this->arguments[1];
        $secretKey = $this->arguments[2];
        $groupBy = $this->arguments[3];

        if (!$this->isGroupByValid($groupBy)) {
            $groupBy = 'day';
            echo "Group by parameter invalid, default group by: day will be used. \n";
        }

        $c = new Zanox\AdvertiserApi\AuthenticationUtil();
        $c->setConnectId($connectId);
        $c->setSecretKey($secretKey);
        $nonce = $c->getNonce();
        $timestamp = gmdate('D, d M Y H:i:s T');
        $sign = $c->getSignature('GET', $this->BASE_REST_APP . $programId, $nonce, $timestamp);
        date_default_timezone_set('UTC');
        $d = strtotime("-31 days");
        $fromdate = date("Y-m-d", $d);
        $d = strtotime("yesterday");
        $todate = date("Y-m-d", $d);

        $parameter = array(
            'groupby' => $groupBy,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'connectid' => $connectId,
            'date' => $timestamp,
            'nonce' => $nonce,
            'signature' => $sign);

        $parameterQuery = http_build_query($parameter);

        $url = APP_URL . $this->BASE_REST_APP . $programId . '?' . $parameterQuery;
        $output = file_get_contents($url);

        return $output;
    }

    private function isGroupByValid($groupBy)
    {
        $validGroupByArray = array('day', 'month', 'adspace', 'admedium', 'adspace,admedium', 'admedium,adspace');
        if (in_array($groupBy, $validGroupByArray)) {
            return true;
        }
        return false;
    }
}
