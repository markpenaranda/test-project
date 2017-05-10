<html>
	<head>
		<data-title>Discover Jobs</data-title>
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
							<h1><i class="fa fa-search fa-fw"></i>Discover Open Day Interiew Post</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div class="row">
						<div class="jg-opendayonline-advancedsearch-container">
							<div class="row">
								<div id="accountInfo" class="col-lg-3 col-sm-12 jg-btm-indent">
									
								</div>
								<div class="col-lg-9 col-sm-12">
									<div class="jg-advancedsearch-form">
										<div class="row">
											<div class="col-sm-12 jg-mar-btm">
												<label for="">Search Open Day Event</label>
												<input type="text" class="form-control" id="search" name="" value="">
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
						<p style="display:none;" class="loading-results jg-load-on-scroll jg-top-indent">
							<span style="color:inherit;" class="fa fa-fw fa-rotate-right fa-spin"></span>
							Loading Results on Event Calls
						</p>
						<div id="resultsContainer" style="display:none;" class="jg-opendayonline-eventcallresult-container">
							<div class="row">
								<div  class="col-lg-3 col-sm-3 col-xs-12 col-md-3">
									<!--<h4 class="jg-resultdate jg-center">January 1, 2017</h4>-->
									<h4><span id="numberOfResults"></span> Results <span id="resultsSize"></span> out of <span id="totalResults"></span></h4>
									<ul style="overflow: scroll; max-height: 500px;" id="resultsUl" class="jg-opendayonline-eventcallresult-list">


									</ul>
									<p id="resultsUlLoader" class="jg-load-on-scroll jg-top-indent loading-results">
										<span class="fa fa-rotate-right fa-spin" style="color:#555"></span>
									</p>
								</div>
								<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
								<button id="back" style="width:10%;" class="hide_me visible-xs btn jg-btn btn-success"><i class="fa fa-angle-left"></i></button>
										<div id="resultDetails" class="hide_me jg-opendayonline-resultdetails-info">
												<p class="click-to-view-info centered-info" ><i class="fa fa-search fa-fw"></i>Click 'View Details' to check the event.</p>
										</div>
								</div>
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
			
			<script type="text/javascript" src="js/app/discover-jobs.js"></script>

	</body>
</html>
