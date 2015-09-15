# advertiser-api-client-php
A PHP Client for Advertiser API

#Before using
To use the code for obtaining the data for your program you need to obtain the authentication information first:
* CONNECT_ID
* SECRET_KEY  
You can find your Connect ID and secret key in the zanox Marketplace

#Run
**Get report data:**
$php run.php SERVICE_TYPE PROGRAM_ID CONNECT_ID SECRET_KEY GROUP_BY
e.g.
php ./src/AdvertiserApiClient.php reportservice 1803 476F47B42F2819102E7A a9A63Af80b9D47+8ae84a3929776ba/9479fCE46 day


**Get partnercode details:**
$php run.php SERVICE_TYPE CONNECT_ID SECRET_KEY PARTNER_CODES
e.g.
php ./src/AdvertiserApiClient.php partnercodeservice 476F47B42F2819102E7A a9A63Af80b9D47+8ae84a3929776ba/9479fCE46 39976328C1486151649T,232632221C1397895T,287457C42819842T

(Note: the Connect ID and secret key given above are for for illustrative purposes only)
