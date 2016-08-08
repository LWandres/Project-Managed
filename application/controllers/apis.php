<?php
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
			$duedate = date('F m Y', strtotime($task['duedate']));
			$to = $email_to;
			$subject = 'Action Required: Follow Up Due on'. $duedate .'<br>';
			$from = 'projectmanaged@gmail.com';
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Create email headers
			$headers .= 'From: '.$from."\r\n".
			    'Reply-To: '.$from."\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			// Compose a simple HTML email message
			$message = '<html><body>';
			$message .= '<div style="background:black;width:500px;margin:0px auto;margin-top:10px;margin-bottom:40px;padding:40px;font-style:tahoma"><h1 style="color:white;text-align:center;margin-top:10px>Hi from Project Managed!</h1>';
			$message .= '<p style="text-align:center;color:white;font-size:15px">This is a friendly reminder that you have followups from the meeting'.$meeting.'<br>';
			$message .= 'Your follow up is:'.$task['followup'].'<br>';
			$message .= 'You can access meeting notes by using the button below.</p>';
			$message .= '<a style="text-decoration:none;margin-left:36%;background:rgb(25, 176, 153);padding:20px;width:200px;border:none;color:white;font-style:bold;font-size:20px" href="localhost:8888/admin/viewnotes/'.$meetid.'">Access Meeting Agenda</a></div></body></html>';
			// Sending email
			mail($to, $subject, $message, $headers);
		}
		redirect('/display/loaddashboard');
	}


	//send email button directly on meeting agenda pages
	public function sendemail($meetid){
		$agenda = $this->meetings->get_agenda($meetid);
		$attendees= $this->meetings->get_participants($meetid);
		$recipients=$this->users->sendemails($meetid);
		 foreach ($recipients as $recipient){
			 foreach($recipient as $value){
			 	$email[]=$value;
			}
		 }
		 	$email_to = implode(',', $email); // your email address
			$to = $email_to;
			$subject = 'Agenda Notes';
			$from = 'projectmanaged@gmail.com';
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Create email headers
			$headers .= 'From: '.$from."\r\n".
			    'Reply-To: '.$from."\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			// Compose a simple HTML email message
			$message = '<html><body>';
			$message .= '<div style="background:black;width:500px;margin:0px auto;margin-top:0px;margin-bottom:40px;padding:40px;font-style:tahoma"><h1 style="color:white;text-align:center;margin-top:10px>Hi from Project Managed!</h1>';
			$message .= '<p style="text-align:center;color:white;font-size:15px">In preparation for your upcoming meeting, you can access the meeting notes by using the button below.</p>';
			$message .= '<a style="text-decoration:none;margin-left:36%;background:rgb(25, 176, 153);padding:20px;width:200px;border:none;color:white;font-style:bold;font-size:20px" href="localhost:8888/admin/viewnotes/'.$meetid.'">Access Meeting Agenda</a></div></body></html>';
			// Sending email
			$result = mail($to, $subject, $message, $headers);
			var_dump($result);
			// redirect('/display/loaddashboard');
	}

	//generates a meeting agenda PDF for download
	public function createpdf($meetid){
		$agenda= $this->meetings->get_agenda($meetid);
		$attendees= $this->meetings->get_participants($meetid);
		$followups = $this->meetings->get_followups($meetid);
		  // Set parameters
		$apikey = '79e27278-d41d-4ff4-8481-a6e802ea1730';
		$value = '<title>Meeting Notes</title>
		        <style>
		        #background{
		          background:MEDIUMTURQUOISE;
		          height:1500px;
		          color:white;
		        }

		        #container{
		          width:900px;
		          margin:0px auto;
				  margin-top:50px;
		          font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		        }

		        #main{
		          margin-top: 50px;
		          height: 700px;
		          background:white;
		          margin:20px;
		          border:solid 1px silver;
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
		          border-radius: 10px;
		          border:solid 1px silver;
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
		        #agenda ul li{
		          float:none;
		          margin-left: 350px;
		          display:inline;
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
	  			          <li>'. date('l F m Y',strtotime($agenda['date'])).'</li>
	  			          <li>'. date('h:m A',strtotime($agenda['start'])).'</li> -'. date('h:m A',strtotime($agenda['end'])).'</li>
	  			        </ul>
		                <h4>Objectives</h4>
		                <div class="objectives">
		                  '.$agenda['objective'].'
		                </div>

		                <h4>Goals</h4>
		                <div class="goals">
		                    '.$agenda['goals'].'
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
