<html>
	<head>
		<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
		<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" type="text/css" />
		<link rel="stylesheet" href="css/hover-min.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
	</head>

	<body class="inside">
    <?php
      include 'include/business-navigation.php';
    ?>


		<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>Create Open Day Interview Post</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div id="mainCreateRoomRow" class="row">

						<ul id="interviewPostFormContainer" class="interview_post_form">
							<li>
								<ul class="single_select_box_wrapper">
									<li class="col-lg-2">
										<div class="row">
											<p>Create from Previous Post</p>
										</div>
									</li>
									<li class="col-lg-3">
										<div class="row">
											<select>
												<option>India</option>
											</select>
										</div>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="apply_page_btnset_ul">
							<li>
								<button id="cancel" class="hvr-underline-reveal">cancel</button>
							</li>
							<li>
								<button id="preview" class="hvr-underline-reveal">preview</button>
								<button id="continue" style="display:none;" class="hvr-underline-reveal">Continue</button>
								<button id="submit" style="display:none;" class="hvr-underline-reveal">Checkout and Publish</button>
							</li>
						</ul>
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
  	<script type="text/javascript" src="js/app/create-room.js"></script>

	</body>
</html>
