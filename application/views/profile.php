<html>

<head>

    <title>Edit Your Profile</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/login.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <!-- JS -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

</head>
<!-- including header partial -->
<?php include_once("header3.php"); ?>
<!-- end header partial -->

<body>
    </div>
    <div id="background">

        <?php
    if($this->session->flashdata('reg_errors')){
      echo($this->session->flashdata('reg_errors')[0]);
    }
    $session_id = $this->session->userdata('id');
    ?>
            <form id="updateprofile" action="/access/updateprofile/<?=$session_id?>" method="post" class="form-horizontal" role="form">
                <h2>Your Profile</h2>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">First Name:</label>
                    <div class="col-sm-10">
                        <input type="text" name="first" class="form-control" id="first" value="<?= $userinfo['first']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Last Name:</label>
                    <div class="col-sm-10">
                        <input type="text" name="last" class="form-control" id="last" value="<?= $userinfo['last']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="lastname">Organization:</label>
                    <div class="col-sm-10">
                        <input type="text" name="orgname" class="form-control" id="orgname" value="<?= $userinfo['orgname']?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="name">Email:</label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" id="email" value="<?= $userinfo['email']?>">
                    </div>
                </div>

                <!-- Password reset for later implementation -->
                <!-- <div class="form-group">
          <label class="control-label col-sm-2" for="password">Password:</label>
          <div class="col-sm-10">
            <input  type="password" name="password" class="form-control" id="password" placeholder="password">
          </div>
        </div> -->

                <!-- <div class="form-group">
          <label class="control-label col-sm-2" for="confirmpassword">Confirm Password:</label>
          <div class="col-sm-10">
            <input  type="password" name="confirmpassword" class="form-control" id="confirmpassword" placeholder="Confirm Password">
          </div>
      </div> -->

                <div class="form-group">
                    <div class=" col-sm-10 ">
                        <button type="submit" value="updateprofile" class="btn btn-primary add button">Update Profile</button>
                    </div>
                </div>
            </form>

    </div>
    </div>
    </div>
</body>
</div>


</html>
