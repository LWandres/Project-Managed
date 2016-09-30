<!DOCTYPE html>
<html lang="en">
<head>
	<title>Project Managed</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Project Managed Website">
	<meta name="keywords" content="PMP, Project Management">
	<meta name="author" content="Laura Wandres">
	<link rel="icon" href="/assets/images/pmicon.png" type="image/x-icon">

  	<!-- stylesheets -->
	<link rel="stylesheet" type="text/css" href="/assets/css/welcome.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/css/jquery-ui.css">

	<!-- fonts -->
	<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'> -->
	<link href='https://fonts.googleapis.com/css?family=Raleway:300,400' rel="stylesheet">

</head>
<body>
	<div id="container" class="col-md-11">
		<div id="header">
			<div class="fixed">
			<h1 class="left-header">PROJECT <span class="managed"> MANAGED</span>.</h1>
				<ul class="rightheader">
					<li><a href="/login">REGISTER </a></li>
					<li><a href="/login">LOGIN </a></li>
					<li><a href="/"><img src="/assets/images/home.png" alt="home.png"></a></li>
				</ul>
			</div>

			<!-- responsive menu button -->
			<div id="mobile-nav">
				 <button id="menu-btn">
					 <div class="menu-btn fixed">
						 <div></div>
						 <span></span>
						 <span></span>
						 <span></span>
				 		</div>
			 	</button>

				 <div id="resp-menu" class="responsive-menu">
						<ul>
							<li><a href="/">HOME</a></li>
							<li><a href="/login">LOGIN</a></li>
							<li><a href="#">CONTACT</a></li>
						</ul>
				 </div>
			</div>
			<!-- end of responsive menu -->
    </div>

	<!-- JS files-->
	<script async src="/assets/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery-3.1.0.min.js"></script>

	<script>
		$(document).ready(function(){
			$(".responsive-menu").hide();
			$("#menu-btn").click(function(){
				$(".responsive-menu").toggle();
			});
		});
	</script>
