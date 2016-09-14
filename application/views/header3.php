<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<title>Welcome to Project Managed</title>

	  	<!-- stylesheets -->
		<link rel="stylesheet" type="text/css" href="/assets/css/welcome.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">

		<!-- fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Raleway:300,400" rel="stylesheet">
		<script src="https://use.fontawesome.com/08a48debfa.js"></script>

		<script>
			$(document).ready(function(){
				$(".responsive-menu").hide();
				$("#menu-btn").click(function(){
					$(".responsive-menu").toggle();
				});
			});
		</script>

</head>
<body>
	<div id="container" class="col-md-10">
		<?php $session_id = $this->session->userdata('id');?>
		<div id="header">
			<div class="fixed">
				<h1 class="left-header">PROJECT <span class="managed"> MANAGED</span>.</h1>
					<ul class="rightheader">
						<li><a href="/access/logout">Logout</a></li>
						<li><a href="/display/loaddashboard">Dashboard</a></li>
						<li><a href="/"><i class="fa fa-home"></i></i></a></li>
					</ul>
			</div>

			<!-- responsive menu button -->
			<div id="mobile-nav">
				 <button id="menu-btn" height="100px">
					 <div class="menu-btn fixed">
						 <div></div>
						 <span></span>
						 <span></span>
						 <span></span>
				 		</div>
			 	</button>

				 <div id="resp-menu" class="responsive-menu">
						<ul>
							<li><a href="/access/logout">Logout</a></li>
							<li><a href="/display/loaddashboard">Dashboard</a></li>
							<li><a href="/"><i class="fa fa-home"></i></i></a></li>
						</ul>
				 </div>
			</div>
			<!-- end of responsive menu -->
    </div>
