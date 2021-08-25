<?php
// phpinfo();
// exit;
require_once("./PaytmChecksum.php");
require_once("./encdec_paytm.php");


error_reporting(0);
ini_set('display_errors', 0);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
define("PAYTM_MERCHANT_KEY", "YOUR_MERCHANT_KEY"); // Need to Update
define("PAYTM_MID", "YOUR_PAYTM_MID"); // Need to Update


$order = "ORDERID_26";
$customer = "STRACC_CUSTOMER_26";
$paytmParams = array();
$paytmParams["body"] = array(
    "requestType" => "Payment",
    "mid" => PAYTM_MID,
    "websiteName" => "WEBSTAGING",
    "orderId" => $order,
    "callbackUrl" => "http://localhost/jscheckout/Paytm_PHP_Checksum-master/sample.php",
    "txnAmount" => array(
        "value" => "1.00",
        "currency" => "INR",
    ),
    "userInfo" => array(
        "custId" => $customer,
    )
);

if ($_POST) {
    $paytmChecksum = "";

    /* Create a Dictionary from the parameters received in POST */
    $paytmParams = array();
    foreach ($_POST as $key => $value) {
        if ($key == "CHECKSUMHASH") {
            $paytmChecksum = $value;
        } else {
            $paytmParams[$key] = $value;
        }
    }
    $isValidChecksum = verifychecksum_e($paytmParams, PAYTM_MERCHANT_KEY, $paytmChecksum);
    if ($isValidChecksum == 'TRUE') {
        // now save the response 
        echo "<br/>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    } else {
        echo "<br/>";
        echo "checksum error";
    }
    exit;
    // https://developer.paytm.com/assets/Transaction%20response%20codes%20and%20messages.pdf
    // https://developer.paytm.com/docs/js-checkout/#Pre-requisites

    //CARD PAYMENT WITH PENDING STATUTE
    // Array
    // (
    //     [BANKNAME] => Bank of Bahrain and Kuwait
    //     [BANKTXNID] => 
    //     [CHECKSUMHASH] => VSVwuX2v70jFIf6D5+1VS9KShE4ac5h2568BsDqkPcQI3i5Jo/ydh/XmfHXtjAYYfrWTpTegyx/GVzVoCEYewCgcn0IemwQcr6R3JWQ7XAw=
    //     [CURRENCY] => INR
    //     [MID] => LXxaBl08208509695143
    //     [ORDERID] => ORDERID_23
    //     [PAYMENTMODE] => DC
    //     [RESPCODE] => 402
    //     [RESPMSG] => Looks like the payment is not complete. Please wait while we confirm the status with your bank.
    //     [STATUS] => PENDING
    //     [TXNAMOUNT] => 1.00
    //     [TXNDATE] => 2021-08-25 14:48:27.0
    //     [TXNID] => 20210825111212800110168052602937319
    // )
    //CARD PAYMENT SUCCESS RESPONSE
    // Array
    // (
    //     [BANKNAME] => Bank of Bahrain and Kuwait
    //     [BANKTXNID] => 777001813044190
    //     [CHECKSUMHASH] => 4GCNU6VLBqBma+pb1q9q0UolevTGBV99hYBIidnoZdIlqCEu7zjHY1QKG1MOxOsp2fe/PkolmWMN4jDOr9YOftPy7WNwgLiWV/yLxFgqdZs=
    //     [CURRENCY] => INR
    //     [GATEWAYNAME] => HDFC
    //     [MID] => LXxaBl08208509695143
    //     [ORDERID] => ORDERID_63363
    //     [PAYMENTMODE] => DC
    //     [RESPCODE] => 01
    //     [RESPMSG] => Txn Success
    //     [STATUS] => TXN_SUCCESS
    //     [TXNAMOUNT] => 1.00
    //     [TXNDATE] => 2021-08-25 10:56:33.0
    //     [TXNID] => 20210825111212800110168476702916817
    // )

    //     CARD PAYMENT FAIL RESPONSE
    // Array
    // (
    //     [BANKNAME] => Bank of Bahrain and Kuwait
    //     [BANKTXNID] => 777001231131473
    //     [CHECKSUMHASH] => CUnucwvQwlWioBhhYegUo0/rJX0IyxOQCv7i2iuoGDXIYFqOLmZx2O0wuaLd1yO5AQGB5YXLWK4oYm/8IIiThpqkC+bhxj2bK608Y7XtCfs=
    //     [CURRENCY] => INR
    //     [GATEWAYNAME] => HDFC
    //     [MID] => LXxaBl08208509695143
    //     [ORDERID] => ORDERID_38564
    //     [PAYMENTMODE] => DC
    //     [RESPCODE] => 227
    //     [RESPMSG] => Your payment has been declined by your bank. Please try again or use a different method to complete the payment.
    //     [STATUS] => TXN_FAILURE
    //     [TXNAMOUNT] => 1.00
    //     [TXNDATE] => 2021-08-25 10:57:51.0
    //     [TXNID] => 20210825111212800110168621804333557
    // )

    // ONLINE BANK TRANFER SUCCESS 
    //     Array
    // (
    //     [BANKNAME] => Andhra Bank
    //     [BANKTXNID] => 11530450668
    //     [CHECKSUMHASH] => Ym9XIH9JW/ZFb7vsMXQP0awUFF1+98sFpBDzgrP+R6c73FPQBg18ea+xtZtNULJdfLDLtoNKbPjNU2iQbj8ADEhbYH5fa9VZHryWoXVh978=
    //     [CURRENCY] => INR
    //     [GATEWAYNAME] => ANDB
    //     [MID] => LXxaBl08208509695143
    //     [ORDERID] => ORDERID_48254
    //     [PAYMENTMODE] => NB
    //     [RESPCODE] => 01
    //     [RESPMSG] => Txn Success
    //     [STATUS] => TXN_SUCCESS
    //     [TXNAMOUNT] => 1.00
    //     [TXNDATE] => 2021-08-25 11:02:41.0
    //     [TXNID] => 20210825111212800110168694102910398
    // )

    // ONLINE BANK TRANFER FAIL
    // Array
    // (
    //     [BANKNAME] => Andhra Bank
    //     [BANKTXNID] => 15430306093
    //     [CHECKSUMHASH] => lxO30tBg0nqjD9UdvsZ7BAGgexNPGLx+WMGT+j0/3+r4+/Fgz94kDnCNpf9kVqhrBYGSa3HNhzGiQ3aQ5RaYj9uj1HAM+0trFuVY4HkdCLk=
    //     [CURRENCY] => INR
    //     [GATEWAYNAME] => ANDB
    //     [MID] => LXxaBl08208509695143
    //     [ORDERID] => ORDERID_82734
    //     [PAYMENTMODE] => NB
    //     [RESPCODE] => 227
    //     [RESPMSG] => Your payment has been declined by your bank. Please try again or use a different method to complete the payment.
    //     [STATUS] => TXN_FAILURE
    //     [TXNAMOUNT] => 1.00
    //     [TXNDATE] => 2021-08-25 11:03:54.0
    //     [TXNID] => 20210825111212800110168504402925767
    // )


}



/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_MERCHANT_KEY);

$paytmParams["head"] = array(
    "signature" => $checksum
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=" . PAYTM_MID . "&orderId=" . $order;

/* for Production */
// $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

$headers = array("Content-Type: application/json");
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo $error_msg;
    exit;
}
curl_close($ch);
$resp  = json_decode($response, true);
$txnToken = $resp['body']['txnToken'];

?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="application/javascript" crossorigin="anonymous" src="https://securegw-stage.paytm.in/merchantpgpui/checkoutjs/merchants/LXxaBl08208509695143.js"></script>
</head>

<body>

    <script type="text/javascript">
        $(document).ready(function() {
            onScriptLoad();
        });

        function onScriptLoad() {
            var config = {
                "root": "",
                "flow": "DEFAULT",
                "data": {
                    "orderId": "<?= $order; ?>" /* update order id */ ,
                    "token": '<?= $txnToken; ?>' /* update token value */ ,
                    "tokenType": "TXN_TOKEN",
                    "amount": "1.00" /* update amount */
                },
                "handler": {
                    "notifyMerchant": function(eventName, data) {
                        console.log("notifyMerchant handler function called");
                        console.log("eventName => ", eventName);
                        console.log("data => ", data);
                        if(eventName == "APP_CLOSED"){
                            alert(" User do not want to make payment. ");
                        }
                    }
                }
            };

            if (window.Paytm && window.Paytm.CheckoutJS) {
                window.Paytm.CheckoutJS.onLoad(function excecuteAfterCompleteLoad() {
                    // initialze configuration using init method 
                    window.Paytm.CheckoutJS.init(config).then(function onSuccess() {
                        // after successfully update configuration invoke checkoutjs
                        window.Paytm.CheckoutJS.invoke();
                    }).catch(function onError(error) {
                        console.log("error => ", error);
                    });
                });
            }
        }
    </script>
</body>

</html>