<?php 
if($_REQUEST['xAction'] == "subscribe") {
    //Mailchimp API URL
    $listID = "LIST ID";
    $email = "YOUR EMAIL";
    $fname = "YOUR NAME";
    $apiKey = "API KEY";

    $memberID = md5(strtolower($email));
    $dataCenter = substr($apiKey, strpos($apiKey, '-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

    //member information
    $json = json_encode([
        'email_address' => $email,
        'status'        => 'subscribed',
        'merge_fields'  => [
            'FNAME'     => $fname,
        ]
    ]);

    //send a HTTP POST request with curl
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo '<pre>'; print_r(json_decode($result, true)); print_r($httpCode);
    curl_close($ch);
    if($httpCode == 200) {
        $msg = 'OK';
    } else {
        switch($httpCode) {
            case 214:
                $msg = 'You are already subscribed.';
            break;
            default:
                $msg = 'Some problem occurred, please try again.';
            break;
        }
    }
}else{
    $msg = 'Some problem occurred, please try again.';
}
echo $msg;
?>