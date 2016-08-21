<?php
require (BASEPATH.'libraries/PHPMailerAutoload.php');
require(BASEPATH.'libraries/class.phpmailer.php');
//Main controller for external integrations
class apis extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('users');
			$this->load->model('displays');
			$this->load->model('meetings');
		}
	//email follow up button from the dashboard.
	public function emailfollowup($meetid){
		$followups = $this->meetings->get_emailfollowups($meetid);
		foreach ($followups as $task){
			$email_to = $task['email'];
			$followup = $task['followup'];
			$meeting = $task['name'];
			$duedate = date('F d Y', strtotime($task['duedate']));
			//PHP MAILER WITH SMTP
			$mail = new PHPMailer;
			$mail->isSMTP();
			// $mail->SMTPDebug = 4;                           	// Enable verbose debug output
			$mail->Host = 'smtp.gmail.com';  					// Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                             // Enable SMTP authentication
			$mail->Username = 'pmmanaged@gmail.com';            // SMTP username
			$mail->Password = 'pmpcert2016';                    // SMTP password
			$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;
			$mail->setFrom('pmmanaged@gmail.com', 'Project Managed');
			$mail->addAddress(''.$email_to.'');
			$mail->addReplyTo('pmmanaged@gmail.com', 'Information');
			$mail->addBCC('lew4f08@gmail.com');
			$mail->isHTML(true);
			//HTML and message content
			$mail->Subject =  'Action Required: Follow Up Due on '. $duedate .'';
			$mail->Body    = '<html><body><div style="background:black;width:800px;margin:0px auto;margin-top:10px;margin-bottom:40px;padding:40px;font-family:tahoma;color:white;font-size:14px"><h1 style="color:white;text-align:center;margin-top:10px">Hi from Project Managed!</h1><br><p style="text-align:center;color:white;font-size:16px">This is a friendly reminder that you have followups from the meeting '.$meeting.' <br>Your follow up is: '.$task['followup'].'<br> You can access meeting notes by using the button below.</p><br><br><br><br><a style="text-decoration:none;margin-left:38%;background:rgb(25, 176, 153);padding:20px;width:200px;border:none;color:white;font-style:bold;font-size:20px" href="http://52.40.19.212/admin/viewnotesemail/'.$meetid.'">Access Meeting Agenda</a></div></body></html>';
			$mail->AltBody = 'Hi from Project Managed! This is a friendly reminder that you have followups from the meeting: '.$meeting.'<br>
							  Your follow up is: ' .$task['followup']. ' <br>
							  You can access meeting notes by using this url: http://52.40.19.212/admin/viewnotesemail/'.$meetid.'';
			$mail->send();
		}
		redirect('/display/loaddashboard');
	}
	//send email button directly on meeting agenda pages
	public function sendemail($meetid){
		$agenda= $this->meetings->get_agenda($meetid);
		$attendees= $this->meetings->get_participants($meetid);
		$recipients=$this->users->sendemails($meetid);
		$mail = new PHPMailer;
		foreach ($recipients as $recipient => $value){
			if($value['email'] != null){
				$mail->addAddress(''.$value['email'].'');
				$mail->isSMTP();
				// $mail->SMTPDebug = 4;                               // Enable verbose debug output
				$mail->Host = 'smtp.gmail.com';  					// Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                             // Enable SMTP authentication
				$mail->Username = 'pmmanaged@gmail.com';            // SMTP username
				$mail->Password = 'pmpcert2016';                    // SMTP password
				$mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;
				$mail->setFrom('pmmanaged@gmail.com', 'Project Managed');
				$mail->addReplyTo('pmmanaged@gmail.com', 'Information');
				$mail->isHTML(true);
				$mail->Subject = 'Agenda Notes for '.$agenda['name'].'';
				$mail->Body    = '<html><body><div style="background:black;width:800px;margin:0px auto;margin-top:10px;margin-bottom:40px;padding:40px;font-family:tahoma;color:white;font-size:1em"><h1 style="color:white;text-align:center;margin-top:10px">Hi from Project Managed!</h1><p style="text-align:center;color:white;font-size:14px">In preparation for your upcoming meeting, <b>'.$agenda['name'].'</b> you can access the email agenda by clicking the button below.<br><br><br><br><a style="text-decoration:none;background:rgb(25, 176, 153);padding:20px;width:200px;border:none;color:white;font-style:bold;font-size:20px;margin-top:50px;margin-left:10%" href="http://52.40.19.212/admin/viewnotesemail/'.$meetid.'">Access Meeting Agenda</a></div></body></html>';
				$mail->AltBody = 'Hi from Project Managed! In preparation for your upcoming meeting '.$agenda['name'].' , you can access the meeting agenda via the URL below.
								 http://52.40.19.212/admin/viewnotesemail/'.$meetid.'';
				  if(!$mail->send()) {
					  echo 'Message could not be sent.';
					  echo 'Mailer Error: ' . $mail->ErrorInfo;
				  }
	   		}
   		}
		redirect('/display/loaddashboard');
	}
	//generates a meeting agenda PDF for download
	public function createpdf($meetid){
		$agenda= $this->meetings->get_agenda($meetid);
		$attendees= $this->meetings->get_participants($meetid);
		$formatted_attendees = '';
		foreach($attendees as $attendee){
		    $name = $attendee['first']. " ". $attendee['last']. " - ". $attendee['email'];
		    $formatted_attendees = $formatted_attendees .$name. " , ";
		}
		$followups = $this->meetings->get_followups($meetid);
		  // Set parameters
		$apikey = '79e27278-d41d-4ff4-8481-a6e802ea1730';
		$value = '<title>Meeting Notes</title>
		        <style>

		        #container{
					width:900px;
					margin:0px auto;
					margin-top:50px;
					font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		        }
		        #main{
					margin-top: 50px;
					background:white;
					margin:20px;
		        }
				#meetingname {
					padding: 0px;
					margin: 0px;
					font-size: 2.4em;
					text-shadow: none;
				}
		        #agenda{
					margin-top: 30px;
					width: 800px;
					background:white;
					margin:20px;
					padding:50px;
					color:black;
		        }
		        h2{
					margin-left: 5px;
		        }
		        input{
					display:inline-block;
		        }
		        table{
					padding: 10px;
		        }
		        .table{
					display:table;
		        }
		        #agenda li{
					float:none;
					display:inline;
					margin-left:0px !important;
					padding-left:0px !important;
		        }
				#agenda ul{
					float:none;
					display:inline;
					margin-left:0px !important;
					padding-left:0px !important;
		        }
				#date{
					margin-top: 10px;
					margin-bottom:60px;
				}
		        .objectives{
					margin-bottom:40px;
					padding: 10px;
		        }
		        .goals{
					margin-bottom:40px;
					padding: 10px;
		        }
		        .Agenda{
					margin-bottom:40px;
					padding: 10px;
		        }
		        </style>
		        </head>
		        <body>
		        <div id=background>
		          <div id="container">
		              <div id="agenda">
					  <h2 id="meetingname">'.$agenda['name'].'</h2>
	  			        <ul id="date">
	  			          <li>'. date('l F d Y',strtotime($agenda['date'])).'</li><br>
	  			          <li>'. date("g:i a", strtotime($agenda['start'])). " - ". date("g:i a", strtotime($agenda['end'])).'</li>
	  			        </ul>
		                <h4>Objectives</h4>
		                <div class="objectives">
		                  '.$agenda['objective'].'
		                </div>
		                <h4>Goals</h4>
		                <div class="goals">
		                    '.$agenda['goals'].'
		                </div>
						<h4>Attendees</h4>
						<div class="goals">
							'.$formatted_attendees.'
						</div>
		                <h4>Agenda</h4>
		                <div class="Agenda">
		                  '.$agenda['agenda'].'
		                </div>
		            </div>
		          </div>
				  </div>
		        </body>'; // can aso be a url, starting with http..
		// Convert the HTML string to a PDF using those parameters.  Note if you have a very long HTML string use POST rather than get.  See example #5
		$result = file_get_contents('http://api.html2pdfrocket.com/pdf?apikey=' . urlencode($apikey) . '&value=' . urlencode($value));
		// Output headers so that the file is downloaded rather than displayed
		// Remember that header() must be called before any actual output is sent
		header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . strlen($result));
		// Make the file a downloadable attachment
		header('Content-Disposition: attachment; filename=' . 'MeetingAgenda.pdf' );
		echo $result;
		redirect('/display/loaddashboard');
		}
	}
?>
