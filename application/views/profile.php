<!doctype html>
<html>
<head>

  <title>Edit Your Profile</title>
  <link rel="stylesheet" type="text/css" href="/assets/login.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<style>
</style>
</head>
  <body>
    <div id="container">
      <div id="header">
  			<h1>Project Managed.</h1>
  				<ul>
  					<li><a href="/access/logout">Logout</a></li>
  					<li><a href="">Resources </a></li>
            <li><a href="/display/loaddashboard">Dashboard</a></li>
  					<li><a href="/">Home </a></li>
  				</ul>
  		</div>

      <div id="main">

  <div class='col-md-5 col-md-offset-1 '>
    <?php
    if($this->session->flashdata('reg_errors')){
      echo($this->session->flashdata('reg_errors')[0]);
    }
    $session_id = $this->session->userdata('id');
    ?>
    <div id="registration">
      <form action="/access/updateprofile/<?=$session_id?>" method="post" class="form-horizontal" role="form">
        <h2>Your Profile</h2>
        <div class="form-group">
          <label class="control-label col-sm-2" for="name">First Name:</label>
          <div class="col-sm-10">
            <input  type="name" name="name" class="form-control" id="name" placeholder="Enter Name">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Last Name:</label>
          <div class="col-sm-10">
            <input  type="name" name="name" class="form-control" id="name" placeholder="Enter Name">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="lastname">Organization:</label>
          <div class="col-sm-10">
            <input  type="name" name="username" class="form-control" id="username" placeholder="Enter Username">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="name">Email:</label>
          <div class="col-sm-10">
            <input  type="name" name="name" class="form-control" id="name" placeholder="Enter Name">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="password">Password:</label>
          <div class="col-sm-10">
            <input  type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="confirmpassword">Confirm Password:</label>
          <div class="col-sm-10">
            <input  type="password" name="confirmpassword" class="form-control" id="confirmpassword" placeholder="Confirm Password">
          </div>
        </div>

        <div class="form-group">
          <div class=" col-sm-10 ">
            <button type="submit" value="updateprofile" class="btn btn-primary add button">Update Profile</button>
          </div>
        </div>
      </form>
    </div>

  </div>
  </div>
</div>
</body>
</div>


</html>
