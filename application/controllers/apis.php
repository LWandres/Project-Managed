<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class apis extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('users');
			$this->load->model('displays');
			$this->load->model('meetings');
		}

	public function sendemail($meetid){
		$agenda= $this->meetings->get_agenda($meetid);
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
			$from = 'projectmanaged@gmrail.com';
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Create email headers
			$headers .= 'From: '.$from."\r\n".
			    'Reply-To: '.$from."\r\n" .
			    'X-Mailer: PHP/' . phpversion();
			// Compose a simple HTML email message
			$message = '<html><body>';
			$message .= '<h1 style="color:#0033cc;">Hi from Project Managed!</h1>';
			$message .= '<p style="color:black;font-size:18px;">In preparation for your upcoming meeting, you can access our email notes at the following URL:</p>';
			$message .= '<a href="www.projectmanaged.com">Access Meeting Agenda</a></body></html>';
			// Sending email
			mail($to, $subject, $message, $headers);
					redirect('/display/loaddashboard');
	}

		public function createpdf($meetid){
		  // Set parameters
		  // Set parameters
		  // Set parameters
		$apikey = '79e27278-d41d-4ff4-8481-a6e802ea1730';
		$value = '<title>Meeting Notes</title>
		        <style>
		        #background{
		          background:CADETBLUE;
		          height:1500px;
		          color:white;
		        }

		        #container{
		          width:900px;
		          margin:0px auto;
		          font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		        }

		        #header{
		          margin-top:40px;
		          margin-left: 20px;
		          width:800px;
		          background:CADETBLUE;
		          height: 2%;
		          display:inline-block;
		          background:white;
		          padding-bottom:10px;
		          border-bottom:solid 1px black;
		          border-radius:10px;
		          color:black;
		        }

		        #header > h1{
		          margin-left:60px;
		          display: inline-block;
		        text-shadow: 0px 4px 3px rgba(0,0,0,0.4),
		                     0px 8px 13px rgba(0,0,0,0.1),
		                     0px 18px 23px rgba(0,0,0,0.1);
		          border-bottom: solid 2 px navy;
		        }

		        #header ul,li{
		          margin-top:15px;
		          display: inline-block;
		          padding:0% 2%;
		          float:right;
		        }
		        #header ul{
		          width: 40%;
		        }

		        #header a{
		          text-decoration: none;
		          color:#009999;
		        }

		        #header a:hover {
		          color: hotpink;
		        }

		        #header li{
		          border-right:solid 1px;
		        }

		        #main{
		          margin-top: 50px;
		          height: 700px;
		          background:white;
		          margin:20px;
		          border:solid 1px silver;
		        }

		        h2{
		          padding: 20px;
		          text-shadow: 2px 4px 3px rgba(0,0,0,0.3);
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
		          margin-left: 20px;
		        }

		        #tabs ul li{
		          float:none;
		        }

		        input{
		          display:inline-block;
		        }

		        textarea{
		          vertical-align: top;
		          font-family: Tahoma;
		          font-size:.80em;
		        }

		        table{
		          padding: 10px;
		        }

		        .table{
		            display:table;
		        }

		        input{
		          padding:0px;
		          margin:0px;
		        }

		        #agenda ul li{
		          float:none;
		          margin-left: 350px;
		          display:inline;
		        }
		        input[type=date]{
		          height:25px;
		          width:140px;
		        }

		        .objectives{
		          margin-bottom:40px;
		          padding: 10px;
		        }

		        .goals{
		          margin-bottom:40px;
		          padding: 10px;
		        }

		        .attendees{
		          margin-bottom:40px;
		          padding: 10px;
		        }

		        .attendees input[type=submit]{
		          margin-left:450px;
		          margin-top:5px;
		          margin-bottom:5px;
		        }

		        .Agenda{
		          margin-bottom:40px;
		          padding: 10px;
		        }

		        .FollowUps{
		          margin-top:0px;
		          padding-top:0px;
		        }

		        .FollowUps table{
		            vertical-align: top;
		          }

		        .FollowUps input[name=updatefollowup]{
		          background:green;
		          color:white;
		          padding: 5px;
		        }
		        .FollowUps input[id=follow]{
		          width:300px;
		        }

		        .FollowUps{
		            width:750px;
		            margin-top:10px;
		          }
		        .FollowUps th{
		          width:190px;
		          text-align:center;
		        }
		        .FollowUps input[type=text]{
		          width:300px;
		        }
		        #agenda > div.FollowUps > form > div > table > thead > tr:nth-child(1) > th:nth-child(1){
		          text-align:left;
		        }
		        #agenda > div.FollowUps > form > div > table > thead > tr:nth-child(1) > th:nth-child(4){
		          text-align:left;
		        }

		        #agenda > div.FollowUps > form > div > table > thead > tr:nth-child(1) > th:nth-child(4){
		          width:200px;
		          margin-left:50px;
		        }

		        #agenda > div.attendees > form{
		          border:0px;
		        }


		        #container > div.email > form > input[type=button]{
		          display:inline-block;
		          border-radius: 5px;
		        }

		        #agenda a[name=back]{
		          margin-left: 500px;
		        }

		        input[name=email]{
		          margin-top: 20px;
		          margin-left:600px;
		          padding:5px;
		          color:black;
		          border:solid 1px black;
		          border-radius: 5px;
		        }

		        .add_field_button{
		          margin-top: 20px;
		          padding:5px;
		          background:green;
		          color:white;
		          border-radius: 5px;
		          margin-bottom:20px;
		        }

		        #agenda a[name=back]{
		          margin-left: 500px;
		        }

		        </style>
		        </head>
		        <body>
		        <div id=background>
		          <div id="container">
		              <div id="agenda">
		              <h2>Agenda Name Here</h2>
		                <ul>
		                  <li>Date</li>
		                  <li>Time</li>
		                </ul>
		                <h4>Objectives</h4>
		                <div class="objectives">
		                  Objectives go here
		                </div>

		                <h4>Goals</h4>
		                <div class="goals">
		                    Goals go here
		                </div>

		                <h4>Attendees</h4>
		                <div class="attendees">

		                  <form class="attendees">
		                        <input type="checkbox" name="attendee[]" value="Attendees"</input><br>
		                  </form>
		                </div>

		                <h4>Agenda</h4>
		                <div class="Agenda">
		                  Agenda Goes here
		                </div>

		                <h4>Meeting Follow Ups</h4>

		                <div class="FollowUps">
		                      <form class="followtable">
		                              <table>
		                                <thead>
		                                  <tr>
		                                    <th>Owner</th>
		                                    <th>Follow Up</th>
		                                    <th>Due</th>
		                                    <th>Done?</th>
		                                  </tr>
		                                </thead>
		                                <tbody>
		                                  <?php if(!empty($followups1)){ echo "This meeting doesn"t have any follow ups";
		                                  } ?>
		                                  <?php if(!empty($followups)){
		                                          foreach($followups as $follow){ ?>
		                                               <tr>
		                                                  <td><?=$follow["owner"]?></td>
		                                                  <td><?=$follow["followup"]?></td>
		                                                  <td><?=$follow["duedate"]?></td>
		                                                  <td><?=$follow["status"]?></td>
		                                                </tr>
		                                              <?php }
		                                        }?>
		                                </div>
		                              </div>
		                         </table>
		                      </form>
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

		// Make the file a downloadable attachment - comment this out to show it directly inside the
		// web browser.  Note that you can give the file any name you want, e.g. alias-name.pdf below:
		header('Content-Disposition: attachment; filename=' . 'Agenda.pdf' );
		echo $result;
		redirect('/display/loaddashboard');
		}
		}
?>
