<?php
mb_internal_encoding("UTF-8");

$to = 'support@ejafari.ir';
$subject = 'پیام جدید از وب‌سایت';
$email = "";
$headers = "";
$body = "";

if( isset($_POST['name']) ){
    $name = $_POST['name'];

    $body .= "نام: ";
    $body .= $name;
    $body .= "\n\n";
}

if( isset($_POST['subject']) ){
    $subject = $_POST['subject'];
}

if( isset($_POST['email']) ){
    $email = $_POST['email'];

    $body .= "ایمیل: ";
    $body .= $email;
	$body .= "\n\n";
	
	$headers = 'From: ' .$email . "\r\n";
}

if( isset($_POST['phone']) ){
    $phone = $_POST['phone'];

    $body .= "تلفن: ";
    $body .= $phone;
    $body .= "\n\n";
}

if( isset($_POST['message']) ){
    $message = $_POST['message'];

    $body .= "پیام: ";
    $body .= $message;
    $body .= "\n\n";
}

if ( filter_var($email, FILTER_VALIDATE_EMAIL) && mb_send_mail($to, $subject, $body, $headers) ){
	echo '<div class="status-icon valid"><i class="fa fa-check"></i></div>';
}
else{
	echo '<div class="status-icon invalid"><i class="fa fa-times"></i></div>';
}
