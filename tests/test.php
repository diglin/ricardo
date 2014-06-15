<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

// Ricardo Username DE: 0F43A456-187E-4494-B4CC-3AC03AA70C90
// Ricardo Pass: PassSenghor!!!

$username = '0F43A456-187E-4494-B4CC-3AC03AA70C90';
$password = 'PassSenghor!!!';

// Anonymous
$headers = array(
    "Ricardo-Username: $username",
    "Ricardo-Password: $password",
    "Content-Type: application/json",
    "Host: ws.betaqxl.com"
);

$service = 'SecurityService';
$method = 'GetAnonymousTokenCredential';
$content = array(
    "getAnonymousTokenCredentialParameter" => array(),
);

ricardoConnect($service, $method, $headers, $content);

// Step 1
$service = 'SecurityService';
$method = 'CreateTemporaryCredential';
$content = array(
    "createTemporaryCredentialParameter" => array(),
);

//ricardoConnect($service, $method, $headers, $content);

// Step 2
$service = 'SecurityService';
$method = 'CreateTokenCredential';
$content = array(
    "createTokenCredentialParameter" => array('TemporaryCredentialKey' => '4346fb50-4b79-4ad5-8407-bf908e08fea7'),
);

//ricardoConnect($service, $method, $headers, $content);

$tokenCredentialKey = 'ee3940a7-e1db-449f-bd65-738210abdff2e';

// Step 3
$headers = array(
    "Ricardo-Username: $tokenCredentialKey",
    "Content-Type: application/json",
    "Host: ws.betaqxl.com"
);
$service = 'ArticleService';
$method = 'GetDisplayArticle';
$content = array(
    "getDisplayArticleParameter" => array(
        'ArticleId' => '728955788',
        'ArticleInformation' => true,
        'BidHistoryMaxSize' => 10,
        "BidInformation" => true,
        "Deliveries" => true,
        "Descriptions" => true,
        "InternalReferences" => true,
        "Pictures" => false,
        "QuestionsAnswers" => true,
        "RelistInformation" => true,
        "SellerInformation" => true
    ),
);

//$method = 'GetOpenArticles';
//$content = array(
//    "getOpenArticlesParameter" => array(
//        'ArticleTitleFilter' => '*',
//        'ArticleTypeFilter' => '*',
//        'InternalReferenceFilter' => '',
//        'LastnameFilter' => '',
//        'NicknameFilter' => ''
//    ),
//);

//ricardoConnect($service, $method, $headers, $content);


// RefreshToken
$headers = array(
    "Ricardo-Username: $username",
    "Ricardo-Password: $password",
    "Content-Type: application/json",
    "Host: ws.betaqxl.com"
);
$service = 'SecurityService';
$method = 'RefreshTokenCredential';
$content = array(
    "refreshTokenCredentialParameter" => array('TokenCredentialKey' => $tokenCredentialKey),
);

//ricardoConnect($service, $method, $headers, $content);

function ricardoConnect($service, $method, $headers, $content)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ws.betaqxl.com/ricardoapi/' . $service . '.Json.svc/' . $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $debug = curl_exec($ch);
    $result = json_decode($debug);

    print_r($result);

    curl_close($ch);
}



/*
 * stdClass Object
(
    [CreateTemporaryCredentialResult] => stdClass Object
        (
            [TemporaryCredential] => stdClass Object
                (
                    [ExpirationDate] => /Date(1399587900000+0200)/
                    [TemporaryCredentialKey] => 29f948f4-0344-4dc6-b8bd-272b36d2e31d
                    [ValidationUrl] => http://www.ch.betaqxl.com/ApiConnect/Login/Index?token=29F948F4-0344-4DC6-B8BD-272B36D2E31D&countryId=2&partnershipId=1059&partnerurl=
                )

        )

)


Returned http://www.diglin.com/?success=1&temporarytoken=29f948f4-0344-4dc6-b8bd-272b36d2e31d


stdClass Object
(
    [CreateTokenCredentialResult] => stdClass Object
        (
            [TokenCredential] => stdClass Object
                (
                    [SessionDuration] => 30
                    [TokenCredentialKey] => 43759866-d610-4ff4-be05-83e1772c86c8
                    [TokenExpirationDate] => /Date(1400795100000+0200)/
                )

        )

)
 */


/*
 * When using the Consumer Token, if you retrieve a MessageSecurityException with a FaultException message “Session expired” it means that the Session for this Token has expired. You can refresh it using the method:

RefreshTokenCredential
And give the Consumer Token key.

If the Consumer Token ExpirationDate has been reached, you will get a BusinessFault message “Invalid TokenCredential”. In this case, you will need to ask for a new temp credential and do the whole Consumer Token process.
 */