<html>
	<head>
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
				<div style="display: none;" id="candidate-interview" class="row">
					<div class="col-lg-6 col-sm-4 col-xs-12 col-lg-offset-3">
						<div class="openday_interview_green_wrapper">
							<div class="row">
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<h1 class="opnday_candidate_name">Candidate 001 : John Doe</h1>
								</div>
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<div  style="margin-top: 5px; width: 100%; background-color: black; position:relative; height: 350px;">
							            <video id="remoteVideo" autoplay style="width: : 100%; position:absolute; left: 0; top:0"></video>
							            <video id="localVideo" autoplay muted style="height: 100px; position:absolute; right: 0; bottom:0"></video>
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
												<h6>Sent Nov-2-2016 5:36 PM</h6>
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
												<h6>Sent Nov-2-2016 5:36 PM</h6>
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

	<script type="text/javascript" src="js/app/candidate.js"></script>
	</body>
</html>

