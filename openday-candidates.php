<html>
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" type="text/css" />
		<link rel="stylesheet" href="css/hover-min.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
	</head>

	<body class="inside">

    <!-- MUST DECLARE HERE THE FF -->
      <!-- Openday ID -->
      <input type="hidden" id="opendayId" value="<?php echo $_GET['openday_id'] ?>">


  	<!-- END MUST DECLARE HERE THE FF -->


    <?php
      include 'include/business-navigation.php';
    ?>


	<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>Candidate Per Open Day</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div id="opendayDetails" class="row">
						
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-sm-4 col-xs-12">
						<ul class="interview_list_right_div">
							<li>
								<div class="reg-select-container apply-select-container">
									<select>
										<option>Candidates with schedule</option>
										<option>Candidates without schedule</option>
									</select>
									<span class="arrow" aria-hidden="true"></span>
								</div>
							</li>
						
						</ul>
						<ul id="candidateList" class="interview_list_right_ul">
							
							
						</ul>
					</div>
					<div class="col-lg-8 col-sm-8 col-xs-12">
						<div id="opendayProfile" class="openday_profile_view_wrapper">
							
						</div>
					</div>
				</div>
			</div>
		</section>


		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12">
						<p class="copyright_p">Copyright 2016 All Rights Reserved by JobsGlobal.com</p>
						<ul class="footer_ul">
							<li>
								<a href="about_us.php">About Us</a>
							</li>
							<li>
								<a href="contact_us.php">Contact Us</a>
							</li>
							<li>
								<a href="terms_of_use.php">Terms of Use</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
	<?php
		include 'include/js.php';
	?>
  	<script type="text/javascript" src="js/app/openday-candidates.js"></script>

	</body>
</html>
