<?php

require("phpmailer/class.phpmailer.php");
class send_mail{
    public function send_email($to,$subject,$message,$headers){
        $mail = new PHPMailer();
 $mail->Host = "smtp.gmail.com";
  $mail->Port = 587;
        $mail->IsSMTP(); // send via SMTP
        $mail->SMTPAuth = true; // turn on SMTP authentication
//$mail->Host = 'ssl://smtp.gmail.com:465';
        $mail->Username = "test.account.rac@gmail.com"; // SMTP username
        $mail->Password = "racpakistan22"; // SMTP password

 


        $webmaster_email = "test.account.rac@gmail.com"; //Reply to this email ID
        $email=$to;
        //$email="humza.shahid@ilsainteractive.com"; // Recipients email ID
        $name="RAC"; // Recipient's name

        $mail->From = $headers;
        $mail->FromName = "Brithdaycomp";

        $mail->AddAddress($email,$name);
        $mail->AddReplyTo($webmaster_email,"Brithdaycomp");
        $mail->WordWrap = 50; // set word wrap
        //$mail->AddAttachment("public/new/".$filename", $filename); // attachment
       // $mail->AddAttachment(site_url()."public/new/".$filename); // attachment
        $mail->IsHTML(true); // send as HTML
         $mail->Subject = $subject;
        //$mail->Subject = "This is the subject";
         $mail->Body = $message; //HTML Body
        //$mail->Body = "Hi, This is the HTML BODY "; //HTML Body
        $mail->AltBody = "This is the body when user views in plain text format"; //Text Body

           $headers  = 'MIME-Version: 1.0' . "\r\n";
           $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To:'.$to . "\r\n";
$headers .= 'From: Birthdaycomp' . "\r\n";


            if(!mail($to, $subject, $message, $headers)){
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        else
        {
            session_start();
            $_SESSION['message']="Your account has been activate successfully please Login from your account";
                //echo "Message has been sent";
        }

            }


    public function send_email_attachment($to,$subject,$message,$headers,$type,$filename){
        $mail = new PHPMailer();
 $mail->Host = "smtp.gmail.com";
  $mail->Port = 587;
        $mail->IsSMTP(); // send via SMTP
        $mail->SMTPAuth = true; // turn on SMTP authentication
//$mail->Host = 'ssl://smtp.gmail.com:465';
        $mail->Username = "test.account.rac@gmail.com"; // SMTP username
        $mail->Password = "racpakistan22"; // SMTP password



         
        $webmaster_email = "test.account.rac@gmail.com"; //Reply to this email ID
        $email=$to;
        //$email="humza.shahid@ilsainteractive.com"; // Recipients email ID
        $name="RAC"; // Recipient's name

        $mail->From = $headers;
        $mail->FromName = "Brithdaycomp";

        $mail->AddAddress($email,$name);
        $mail->AddReplyTo($webmaster_email,"Brithdaycomp");
        $mail->WordWrap = 50; // set word wrap
        //$mail->AddAttachment($target_path/$filename);
       // $mail->AddAttachment(site_url()."public/new/".$filename); // attachment
        $mail->IsHTML(true); // send as HTML
         $mail->Subject = $subject;
        //$mail->Subject = "This is the subject";
         $mail->Body = $message; //HTML Body
        //$mail->Body = "Hi, This is the HTML BODY "; //HTML Body
        $mail->AltBody = "This is the body when user views in plain text format"; //Text Body

    $replyto = "admin@birthdaycomp.com";

    if(!empty($filename)){
    $target_path = "public/new";
    $file = site_url()."public/new/".$filename;
    $file_size = filesize("$target_path/$filename");
    $handle = fopen($file, "rb");
    $content = fread($handle, $file_size);

    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $from_name = "Brithdaycomp" ;
    $headers = "From: $from_name <$replyto>\r\n";
    $headers .= "Reply-To: $replyto\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n\r\n";
    $headers .= "This is a multi-part message in MIME format.\r\n";
    $headers .= "--$uid\r\n";
    $headers .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $headers .= $message."\r\n\r\n";
    $headers .= "--$uid\r\n";
    $headers .= "Content-Type: $type ; name=\"$filename\"\r\n"; // use different content types here
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $headers .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
    $headers .= $content."\r\n\r\n";
    $headers .= "--$uid--";
    }
// Additional headers
//$headers .= 'To:'.$to . "\r\n";
$headers .= 'From: Birthdaycomp' . "\r\n";


            if(!mail($to, $subject, $message, $headers)){
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        else
        {
            session_start();
            $_SESSION['message']="Your account has been activate successfully please Login from your account";
                //echo "Message has been sent";
        }

            }





        }

?>
