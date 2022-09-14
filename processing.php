<?php

if($_POST['submit']=='submit'){

    pay();
}
else{
    echo "error";
}

function pay(){
$phonenumber = $_POST['number'];
$admno=$_POST['admno'];

$url='https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$ch = curl_init();
curl_setopt($ch , CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER,  ['Authorization: Basic ' . base64_encode('cXlVra0UqXuhKGtAZONqjVbOLhvtyIHn:3GEVyIhsgby98r1w')]);
//['Authorization: Bearer cFJZcjZ6anEwaThMMXp6d1FETUxwWkIzeVBDa2hNc2M6UmYyMkJmWm9nMHFRR2xWOQ==']
//['Authorization: Bearer cFJZcjZ6anEwaThMMXp6d1FETUxwWkIzeVBDa2hNc2M6UmYyMkJmWm9nMHFRR2xWOQ==']
// ['Authorization: Basic ' . base64_encode('cXlVra0UqXuhKGtAZONqjVbOLhvtyIHn:3GEVyIhsgby98r1w')]
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

if($e =curl_error($ch)){

    echo $e;
}
else
{
    $json = json_decode($response,1);
    print_r($json);
    echo $response."<br>";
    $access_token=$json['access_token'];

    



}

curl_close($ch);











date_default_timezone_set('Africa/Nairobi');
$timestamp = date('YmdHis');

$BusinessShortCode = 174379;
$passkey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$password = base64_encode($BusinessShortCode.$passkey.$timestamp);
$partyA=$phonenumber;
$amount=$_POST['amount'];

//$access_token_url;
//$initiate_url;


$url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
//$callback_url='http://mpesa-requestbin.herokuapp.com/1pnud0b1';
//$callback_url='https://webhook.site/ca8f670e-11e1-4b1a-9148-59f7e2518765';
$callback_url='https://eo1sjsvwvnaxgxz.m.pipedream.net';
$stkheaders =['Content-Type: application/json','Authorization: Bearer '.$access_token];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER,$stkheaders);


$data = array(
    "BusinessShortCode" => 174379,
    "Password" => $password, "Timestamp" => $timestamp, "TransactionType" => 'CustomerPayBillOnline', 'Amount' => $amount,
    'PartyA' => $partyA, 'PartyB' => 174379, 'PhoneNumber' =>$partyA, 'CallBackURL' => $callback_url,
    'AccountReference' => $admno, 'TransactionDesc' => 'Payment for fees for Student '.$admno.' in account number Ebenezar Academy'
);
$json = json_encode( $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
$response= curl_exec($ch);

if($e =curl_error($ch)){

    echo $e;
}
else
{
    $json = json_decode($response,1);
    echo $response."<br>";
    echo $json['ResponseDescription'];
   

    


}
curl_close($ch);



}


?>
