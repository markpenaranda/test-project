<html>
	<head>
		<data-title>Create Openday Room</data-title>
		<?php include 'include/css.php'; ?>
	</head>

	<body class="inside">

	
  <!-- MUST DECLARE HERE THE FF -->
      <!-- User ID -->
      <input type="hidden" id="userId" value="05582c0b47a4aab16bcd">


  <!-- END MUST DECLARE HERE THE FF -->

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
									<li class="col-lg-3 col-sm-12 col-xs-12">
										<div class="row">
											<select class="col-sm-12 col-xs-12 col-md-12" id="createdList">
												<option>--SELECT FROM ALREADY CREATED EVENTS--</option>
											</select>
										</div>
									</li>
								</ul>
							</li>
						</ul>

						<ul style="width:100%" class="apply_page_btnset_ul">
							<li class="pull-left">
								<button id="addRoom" class="hvr-underline-reveal"><i class="fa fa-plus fa-fw"></i>Add Room</button>
							</li>
							<li class="pull-right">
								<button id="preview" class="hvr-underline-reveal">preview</button>
								<button id="continue" style="display:none;" class="hvr-underline-reveal">Continue</button>
								<button id="submit" style="display:none;" class="hvr-underline-reveal">Checkout and Publish</button>
							</li>
							<li class="pull-right">
								<button id="cancel" class="hvr-underline-reveal">cancel</button>
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
