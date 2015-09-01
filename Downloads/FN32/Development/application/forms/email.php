<?php
   //change this to your email.
   //$href = "http://bit.ly/UGAFuN";
   $subject = "Hello! This is HTML email";

   $message = "<strong>Click here for platform manual and overview refer user manual guide
		        For video/audio support log into the platform and go to Training tab (or have the video links here)
		        To access your account use the following information provided.
		        CLICK HERE AND VISIT OUT WEBSITE http://bit.ly/UGAFuN";
   
        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=iso-8859-1";
        $headers[] = "From: Sender Name <info@textmuncation.com>";
        $headers[] = "Bcc: Farad NNN <atbulag@yahoo.com>";
        //$headers[] = "Reply-To: Recipient Name <farad@textmuncation.com>";
        //$headers[] = "Subject: {$subject}";
        $headers[] = "X-Mailer: PHP/".phpversion();
        $to = 'farad@textmunication.com';
mail($to, $subject, $message, implode("\r\n", $headers));

   $tmst = time(); 
   echo "<span style='color:green; font-style:italic;'>Message has been sent: :$tmst</span>";
?>