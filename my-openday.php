<html>
	<head>
    <?php include 'include/css.php'; ?>
    </head>


	<body class="rymd_bg">


  <!-- MUST DECLARE HERE THE FF -->
      <!-- User ID -->
      <input type="hidden" id="userId" value="1d5828aaf4687d05d1ae">


  <!-- END MUST DECLARE HERE THE FF -->


		<?php include 'include/job-seeker-navigation.php'; ?>

	<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>My Open Day Online Video Interviews</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12" id="">
						<ul class="nav nav-tabs job_tab_ul">
							<li class="active"><a id="activeSelect" data-toggle="tab" href="#active_openday">Active</a></li>
						  	<li><a  id="endSelect" data-toggle="tab" href="#end_openday">End</a></li>
						</ul>
						<div class="tab-content">
							<div id="active_openday" class="tab-pane fade in active">
								<ul class="my_jobs_listing_ul" id="">
								</ul>
							</div>
									
										
							<div id="end_openday" class="tab-pane fade">
								<ul class="my_jobs_listing_ul" id="">
									
								</ul>
							</div>
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
			
			<script type="text/javascript" src="js/app/my-openday.js"></script>

	</body>
</html>
