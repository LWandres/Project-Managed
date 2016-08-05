<!-- <!doctype html>

KEEPING THIS HERE FOR FUTURE IMPLEMENTATION
<html>
<head>

  <title>Meeting Portal</title>
  <link rel="stylesheet" type="text/css" href="/assets/css/portal.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<style>
</style>
</head>
<div id="background">
  <body>
    <div id="container">
      <div id="header">
  			<h1>Project Managed.</h1>
  				<ul>
  					<li><a href="/access/profile">Contact Us </a></li>
  					<li><a href="#">Resources </a></li>
  					<li><a href="/">Home </a></li>
  				</ul>
  		</div>

      <div id="main">
      <h1>TIME </h1>
      <h1>TO </h1>
      <h1>MEET </h1>

      <div class="row forms">
        <div class='col-md-6'>
          <form action="/access/meetingportal" method="post" class="form-horizontal" role="form">
            <h2>Log In</h2>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">Email:</label>
              <div class="col-sm-10">
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="name">MeetID:</label>
              <div class="col-sm-10">
                <input type="text" name="meetingid" class="form-control" id="meetingid" placeholder="<?=$meetinginfo?>">
              </div>
            </div>

            <div class="errors">
            <?php

              if($this->session->flashdata('log_errors')){
                echo($this->session->flashdata('log_errors'));
              }
            ?>
            </div>
            <div class="form-group">
              <button type="submit" value="add user!" class="btn btn-primary add button">Login</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</div>

</html> -->
