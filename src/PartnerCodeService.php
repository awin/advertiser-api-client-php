<?php

class PartnerCodeService
{
    private $BASE_REST_APP = '/member/partnership';
    private $ARGUMENTS_NUMBER = 3;
    private $arguments;


    function __construct($args)
    {
        $this->arguments = $args;
    }

    public function getPartnerCodeData()
    {

        if (sizeof($this->arguments) != $this->ARGUMENTS_NUMBER) {
            error_log('Wrong number of arguments. Correct usage: ./src/AdvertiserApiClient.php SERVICE_TYPE CONNECT_ID SECRET_KEY PARTNER_CODES');
            exit(1);
        }

        $connectId = $this->arguments[0];
        $secretKey = $this->arguments[1];
        $partnerCodes = $this->arguments[2];

        $c = new Zanox\AdvertiserApi\AuthenticationUtil();
        $c->setConnectId($connectId);
        $c->setSecretKey($secretKey);
        $nonce = $c->getNonce();
        $timestamp = gmdate('D, d M Y H:i:s T');
        $sign = $c->getSignature('GET', $this->BASE_REST_APP, $nonce, $timestamp);

        $parameter = array(
            'partnerCode' => $partnerCodes,
            'connectid' => $connectId,
            'date' => $timestamp,
            'nonce' => $nonce,
            'signature' => $sign);

        $parameterQuery = http_build_query($parameter);

        $url = APP_URL . $this->BASE_REST_APP . '?' . $parameterQuery;
        $output = file_get_contents($url);

        return $output;
    }
}
