<html>
	<head>
	<data-title>Openday Interview</data-title>
	<?php include 'include/css.php'; ?>
	</head>

	<body class="inside">


  <!-- MUST DECLARE HERE THE FF -->
      <!-- User ID -->

      <!--  ROOM ID -->
      <input type="hidden" id="roomId" value="<?php echo $_GET['openday']; ?>">
      <!-- User ID -->
      <input type="hidden" id="userId" value="1d5828aaf4687d05d1ae">
      <!-- Applicant's Initial Hour(s) -->
      <input type="hidden" id="initialTime" value="0">

  <!-- END MUST DECLARE HERE THE FF -->


  <?php include 'include/job-seeker-navigation.php'; ?>

		<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>Open Day Live Interview</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div id="candidate-interview" class="row">
					<div class="col-lg-6 col-sm-4 col-xs-12 col-lg-offset-3">
						<div class="openday_interview_green_wrapper">
							<div class="row">
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<h1 class="companyName opnday_candidate_name"></h1>
								</div>
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<div  style="margin-top: 5px; width: 100%; background-color: black; position:relative; height: 350px;">
													<i class="fa fa-expand fullscreen-toggle"  aria-hidden="true"></i>
													<i class="fa fa-compress not-fullscreen-toggle hide" aria-hidden="true"></i>

													<video id="remoteVideo" autoplay class="remote-vc-not-fullscreen"></video>
							            <video id="localVideo" autoplay muted class="local-vc-not-fullscreen"></video>
							        </div>
								</div>
								<div class="chat col-lg-12 col-sm-12 col-xs-12">
									<ul class="messages openday_chatbox_ul">

									</ul>
									<div class="chat_text_fied_wrapper">
										<div class="col-lg-9">
											<div class="row">
												<input id="message" type="text" placeholder="type your message here">
											</div>
										</div>
										<div class="col-lg-3">
											<div class="row">
												<button class="send chat_send_btn hvr-shutter-in-horizontal">SEND</button>
											</div>
											<div class="message_template">
									<li>
										<div class="col-lg-10 col-sm-10 col-xs-12">
											<div class="row">
												<p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
												<h6 class="message_date_time">Sent Nov-2-2016 5:36 PM</h6>
											</div>
										</div>
										<div class="col-lg-2 col-sm-2 col-xs-12">
											<div class="row">
												<div class="chat_icon_div">
													<img src="http://fs1.jobsglobal.us/tree/e47/02fc3ee3c7e4584421f2bc43a.png">
												</div>
											</div>
										</div>
									</li>
								</div>
								<div id="reciever_template" class="message_template">
									<li>
										<div class="col-lg-2 col-sm-2 col-xs-12">
											<div class="row">
												<div class="chat_icon_div">
													<img src="http://fs1.jobsglobal.us/tree/e47/02fc3ee3c7e4584421f2bc43a.png">
												</div>
											</div>
										</div>
										<div class="col-lg-10 col-sm-10 col-xs-12">
											<div class="row">
												<p class="text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
												<h6 class="message_date_time">Sent Nov-2-2016 5:36 PM</h6>
											</div>
										</div>

									</li>
								</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div id="opendayDetails" class="row">

					</div>
					<div id="waitingDiv" class="row">

					</div>
				</div>
			</div>
		</section>


		<!-- Modal -->
		<div id="leavePrompt" tabindex="-1" role="dialog" data-backdrop="static"  class="modal fade">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Leave this interview?</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to leave this page? You will be disconnected to the interview.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" id="modalApprove" data-link="" data-dismiss="modal" class="approve btn btn-primary">Yes</button>
					</div>
					</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
		</div>

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
	<script type="text/javascript" src="js/components/videocall.js"></script>

	<script type="text/javascript" src="js/app/candidate.js"></script>
	</body>
</html>
