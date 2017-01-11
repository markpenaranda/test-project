<html>
	<head>
     <?php include 'include/css.php'; ?>
    </head>

	<body class="inside">

	
  <!-- MUST DECLARE HERE THE FF -->
      <!-- User ID -->
      <input type="hidden" id="userId" value="05582c0b47a4aab16bcd">
      <input type="hidden" id="opendayId" value="<?php echo $_GET['openday']; ?>">


  <!-- END MUST DECLARE HERE THE FF -->

    <?php
      include 'include/business-navigation.php';
    ?>


		<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>Openday Successfully Created</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div id="mainCreateRoomRow" class="row">

						<h3><span id="opendayTitle">Marriott Hotel</span> <button class="pull-right btn btn-primary jg-btn">Promote Post</button></h3>
                        <ul  class="interview_post_form">
							<li>
								<ul class="single_select_box_wrapper">
									
										<div class="alert alert-success">
											<p>Successfully Posted the Job</p>
										</div>
									
                                <h5>Suggested Candidates</h5>
								</ul>
							</li>
                            <li>
                                <div class="row">
                                    <div id="suggestedCandidatesDiv" class="col-md-4">
                                        <ul id="resultsUl" class="jg-opendayonline-eventcallresult-list">

                                        </ul>
                                    </div>
                                    <div class="col-md-8">

                                    </div>
                                </div>
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
  	<script type="text/javascript" src="js/app/create-success.js"></script>

	</body>
</html>
