<?php

error_reporting(0);
use Twlve\Bookingcom\Booking;
include 'config.php';

function bocom($us,$password,$n){
    require 'vendor/autoload.php';
    include 'config.php';
    $rand = rand(0,9999999);
    $booking = new Booking();
    $hotels  = ['3326463', '4984319'];
    echo "\n$okegreen**$white Creating user ke $n\n";
    $us = $us.$rand;
    $email = $us."@ddlre.com";
    //$password = $password.$rand;
    $register = $booking->register($email, $password);
    if (!$register->success) {
        checkConnection($register);
        echo "$red!!$white ERROR : " . $register->error_message . "\n";
        die();
    }
    echo "$okegreen**$white Register Success\n";
    echo "$okegreen**$white Claim Reward\n";
    sleep(20);
    $booking->setAuthToken($register->data->auth_token);
    $createWishList = $booking->createWishList();
    if (!$createWishList->success) {
        checkConnection($createWishList);
        echo "$red!!$white ERROR : " . $createWishList->error_message . "\n";
        die();
    }
    foreach ($hotels as $hotel) {
        $saveWishList = $booking->saveWishList($createWishList->data->id, $hotel);
        if (!$saveWishList->success) {
            checkConnection($saveWishList);
            echo "!$red!!$white ERROR : " . $saveWishList->error_message . "\n";
            die();
        }
        if ($saveWishList->data->gta_add_three_items_campaign_status->status == 'reward_given_wallet') {
            echo "$okegreen**$white " . $saveWishList->data->gta_add_three_items_campaign_status->modal_body_text . "\n";
            echo "$okegreen**$white " . $saveWishList->data->gta_add_three_items_campaign_status->modal_header_text . "\n";
            echo "$okegreen**$white Verifying Email\n";
            sleep(4);
            $em = file_get_contents("https://www.1secmail.com/api/v1/?action=getMessages&login=".$us."&domain=1secmail.com");
            preg_match("/:(.*?),\"from\":\"noreply@mailer.booking.com\"/", $em, $idl);
            $idll = $idl[1];
            $isi = file_get_contents("https://www.1secmail.com/api/v1/?action=readMessage&login=".$us."&domain=1secmail.com&id=".$idll);
            preg_match("/enc_user=(.*?)confirmation_type=promotional/",$isi, $link);
            $link = "https://secure.booking.com/app_link/login.id.html?enc_user=".$link[1]."confirmation_type=promotional";
            $links = str_replace('&amp;', '&', $link);
            $chh = curl_init(); 
            curl_setopt($chh, CURLOPT_URL, $links);
            curl_setopt ($chh, CURLOPT_COOKIEJAR, dirname(__FILE__)."/cookiemolas.txt");
            curl_setopt($chh, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($chh, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt( $chh, CURLOPT_HTTPHEADER, array('User-Agent: sMozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'));
            $outputeh = curl_exec($chh); 
            curl_close($chh);   
            preg_match("/\"op_token\":\"(.*?)\"/",$outputeh, $getok);
            $token = $getok[1];
            $che = curl_init();
            curl_setopt($che, CURLOPT_URL, "https://account.booking.com/account/sign-in/password");
            curl_setopt ($che, CURLOPT_COOKIEFILE, dirname(__FILE__)."/cookiemolas.txt");
            curl_setopt ($che, CURLOPT_COOKIEJAR, dirname(__FILE__)."/cookiemolas.txt");
            $payloade = '{"login_name":"'.$email.'","password":"'.$password.'","state":"","scope":"","code_challenge":"","code_challenge_method":"","op_token":"'.$token.'"}';
            curl_setopt( $che, CURLOPT_POSTFIELDS, $payloade );
            curl_setopt( $che, CURLOPT_HTTPHEADER, array('X-Requested-With: XMLHttpRequest' , 'User-Agent: sMozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36','Content-Type: application/json','Accept: */*'));
            curl_setopt( $che, CURLOPT_RETURNTRANSFER, true );
            $resule = curl_exec($che);
            $resulte = json_decode($resule, TRUE);
            if($resule !== NULL){
                $newfile = fopen("akun.txt", "a");
                fwrite($newfile, $email.";".$password."\n");
                fclose($newfile);
                echo "$okegreen**$white Sukses\n";
                echo "$okegreen**$white Email : $email\n";
                echo "$okegreen**$white Password : $password\n";
            }
            else{
                echo curl_error($che);
            }
        }
    }
}

echo "$yellow??$white Base email : ";
$us = trim(fgets(STDIN));
echo "$yellow??$white Password : ";
$password = trim(fgets(STDIN));
echo "$yellow??$white Jumlah : ";
$jumlah = trim(fgets(STDIN));
for($n=0;$n<$jumlah;$n++){
    bocom($us,$password,$n+1);
}

function checkConnection($data)
{
    if (strtolower($data->error_message) == 'no connection') {
        echo "\n\n";
        echo "!! ERROR : " . $data->error_message . "\n";
        echo " ______     ________ ______     ________ _ _ _ \n";
        echo "|  _ \ \   / /  ____|  _ \ \   / /  ____| | | |\n";
        echo "| |_) \ \_/ /| |__  | |_) \ \_/ /| |__  | | | |\n";
        echo "|  _ < \   / |  __| |  _ < \   / |  __| | | | |\n";
        echo "| |_) | | |  | |____| |_) | | |  | |____|_|_|_|\n";
        echo "|____/  |_|  |______|____/  |_|  |______(_|_|_)\n";
        echo "  _____ _    _ _    _ _______ _____   ______          ___   _ _ _ _ \n";
        echo " / ____| |  | | |  | |__   __|  __ \ / __ \ \        / / \ | | | | |\n";
        echo "| (___ | |__| | |  | |  | |  | |  | | |  | \ \  /\  / /|  \| | | | |\n";
        echo " \___ \|  __  | |  | |  | |  | |  | | |  | |\ \/  \/ / | . ` | | | |\n";
        echo " ____) | |  | | |__| |  | |  | |__| | |__| | \  /\  /  | |\  |_|_|_|\n";
        echo "|_____/|_|  |_|\____/   |_|  |_____/ \____/   \/  \/   |_| \_(_|_|_)\n";
        sleep(2);
        die();
    }
}
