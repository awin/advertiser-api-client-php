<?php
    require 'AuthenticationUtil.php';
    require 'ReportService.php';
    require 'PartnerCodeService.php';

    define('APP_URL', 'https://advertiser.api.zanox.com/advertiser-api/2015-03-01');

    if (sizeof($argv) < 2) {
        error_log('Wrong number of arguments.');
        exit(1);
    }

    $serviceType = $argv[1];
    switch ($serviceType) {
        case "reportservice":
            $reportService = new ReportService(array_splice($argv, 2, count($argv)));
            echo $reportService->getReportData();
            break;
        case "partnercodeservice":
            $partnerCodeService = new PartnerCodeService(array_splice($argv, 2, count($argv)));
            echo $partnerCodeService->getPartnerCodeData();
            break;
        default:
            echo "No service found for type:" . $serviceType . ", use [reportservice|partnercodeservice] only.";
    }
