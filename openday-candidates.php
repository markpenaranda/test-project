<html>
	<head>
			<data-title>Openday Candidates</data-title>
     <?php include 'include/css.php'; ?>
    </head>


	<body class="inside">

    <!-- MUST DECLARE HERE THE FF -->
      <!-- Openday ID -->
      <input type="hidden" id="opendayId" value="<?php echo $_GET['openday_id'] ?>">
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
							<h1><i class="fa fa-users"></i>Candidate Per Open Day</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<ul class="list-unstyled">
							<li>
								<div class="form-group">
									<label>Select Openday Event</label>
									<select id="opendayList" class="form-control">
										<option value="0">-- SELECT OPENDAY EVENT --</option>
									</select>
								</div>
							</li>
				</ul>
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div id="opendayDetails" class="row">
						
					</div>
				</div>
				<div class="row">
					<div id="candidatesResultList" class="col-lg-4 col-sm-4 col-xs-12">
						<ul class="interview_list_right_div">
							<li>
								<div class="reg-select-container apply-select-container">
									<select id="candidateSchedule">
										<option value="1">Candidates with schedule</option>
										<option value="0">Candidates without schedule</option>
									</select>
									<span class="arrow" aria-hidden="true"></span>
								</div>
							</li>
							<div class="no-results" style="min-height:200px;">
									<p class="centered-info">
										No Results
									</p>
							</div>
						</ul>
						
						<ul id="candidateList" class="interview_list_right_ul">
							
							
						</ul>
					</div>
					<div class="col-lg-8 col-sm-8 col-xs-12">
						<button id="back" style="width:10%;" class="xs-hide-me visible-xs btn jg-btn btn-success"><i class="fa fa-angle-left"></i></button>
						<div id="opendayProfile" class="xs-hide-me  openday_profile_view_wrapper">
							<p class="click-to-view-info centered-info" ><i class="fa fa-search fa-fw"></i>Click 'View CV' to review candidates.</p>
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
