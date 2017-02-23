<?php namespace App\Helpers;

use PHPMailer;
use Carbon\Carbon;

class EmailHelper {

    public function __construct(PHPMailer $phpmailer) {
        $this->phpmailer = $phpmailer;
    }

    public function testEmail($email) {

            $this->phpmailer->addAddress($email, 'Sample Recepient');
            $this->phpmailer->isHTML(true);


            $theBody = "
                Hi,<br><br>

                You have been contacted via Report Abuse Form.<br><br>

            ";

            $this->phpmailer->Subject = 'Sample Email';
            $this->phpmailer->Body    = $theBody;

            if(!$this->phpmailer->send()) {
                return $this->phpmailer->ErrorInfo;
            }

            return true;
    }

    public function sendOpendayDetailsMail($openday, $schedule, $user, $userTimezone) {
        
         $this->phpmailer->addAddress($user['primary_email'], 'Sample Recepient');
            $this->phpmailer->isHTML(true);

            $startTimeUTC  = Carbon::createFromFormat("H:i:s", $schedule['schedule_time_start'], 'UTC');
            $startTime = $startTimeUTC->tz($userTimezone['timeZoneId'])->format("h:iA");

            $endTimeUTC  = Carbon::createFromFormat("H:i:s", $schedule['schedule_time_end'], 'UTC');
            $endTime = $endTimeUTC->tz($userTimezone['timeZoneId'])->format("h:iA");
            
            
            if($schedule['is_scheduled']) {
                   $theBody = "
               Dear " . $user['name'] . ", <br><br>

                You have successfully booked an Open Day Online Interview on ".  $schedule['event_date']  . " at ".  $startTime . "-" .  $endTime  . " for  " . $schedule['event_name'] . " of " . $schedule['page_name'] . ".
                <br><br>
                Please be online 15 minutes before your scheduled online interview, being late will be placed on the waiting list and will have less chance to be interviewed.
                <br><br>
                Open Day Link: <a href='https://openday.jobsglobal.com/interview.php?openday=". $schedule['openday_id'] ."'>https://openday.jobsglobal.com/interview.php?openday=". $schedule['openday_id'] ."</a><br>
                Your Candidate No: " . $schedule['candidate_number'] . "<br>
                <br><br>
                Best regards,<br>" .
                $schedule["page_name"];
            }

            else {
             $theBody = "
                    Dear " . $user['name'] . ",
                    <br><br>
                    You have successfully booked an Open Day Online Interview on ".  $schedule['event_date']  . " as waiting list for " . $schedule['event_name'] . " of " . $schedule['page_name'] . ".
                    <br>
                    Please be online and take note as waiting list, you will have less chance to be interviewed.
                    <br><br>
                    Open Day Link:<a href='https://openday.jobsglobal.com/interview.php?openday=". $schedule['openday_id'] ."'>https://openday.jobsglobal.com/interview.php?openday=". $schedule['openday_id'] ."</a><br>
                    Your Candidate No: " . $schedule['candidate_number'] . "<br>
                    <br><br>
                    Best regards,<br>" .
                        $schedule["page_name"]; 
            }

         



           

            $this->phpmailer->Subject = 'Openday Join Event';
            $this->phpmailer->Body    = $theBody;

            if(!$this->phpmailer->send()) {
                return $this->phpmailer->ErrorInfo;
            }

            return true;
    }
}