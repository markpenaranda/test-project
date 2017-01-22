<html>
	<head>
		<?php include 'include/css.php'; ?>
	</head>
	<body class="inside">
		<!-- MUST DECLARE HERE THE FF -->
		<!--  ROOM ID -->
		<input type="hidden" id="roomId" value="<?php echo isset($_GET['openday']) ? $_GET['openday'] : ''; ?>">
		<!-- Current User/Admin Id -->
		<input type="hidden" id="userId" value="05582c0b47a4aab16bcd">
		<!-- Applicant Id -->
		<input type="hidden" id="applicantId" value="2">
		<!-- Applicant's Initial Hour(s) -->
		<input type="hidden" id="initialTime" value="0">
		<!-- END MUST DECLATE HERE THE FF -->
		<!-- MUST DECLARE HERE THE FF -->
		
		<?php
		include 'include/business-navigation.php';
		?>
		<section>
			<div class="orange_header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<h1><i class="fa fa-users"></i>OPEN DAY ONLINE VIDEO INTERVIEW</h1>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-4 col-sm-4 col-xs-12">
						<div class="openday_interview_green_wrapper">
							<div class="row">
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<h1 class="opnday_candidate_name"><span id="spanCandidateNumber"></span>  <span id="spanCandidateName"></span></h1>
								</div>
								<div class="col-lg-12 col-sm-12 col-xs-12">
									<div style="margin-top: 5px; width: 100%; height: 280px; position: relative; background-color: black;">
									<video id="remoteVideo" autoplay style="width: : 100% !important; position:absolute; left: 0; top:0"></video>
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
			<div class="col-lg-5 col-sm-5 col-xs-12">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div class="row">
						<ul class="todays_events_ul">
							<li>
								<h6>Live Event</h6>
								<select id="liveOpendaySelect">
								</select>
							</li>
							<li>
								<div class="row">
									<div class="col-lg-4">
										<h6>Total Check-In with Schedule</h6>
										<p id="checkInWithSchedule">0</p>
									</div>
									<div class="col-lg-5">
										<div class="row">
											<h6>Total Not yet Check-In with Schedule</h6>
											<p id="notCheckInWithSchedule">0</p>
										</div>
									</div>
									<div class="col-lg-3">
										<h6>Total Waiting List</h6>
										<p id="waitingListCount">0</p>
									</div>
								</div>
							</li>
							<li>
								<button class="stop_queue_btn">Stop adding Queue</button>
							</li>
							<li>
								<div class="row">
									<div class="col-lg-4 col-sm-4 col-xs-12">
										<h6>Currecnt Interview Timer</h6>
										<p id="lapseTime">00:00:00</p>
									</div>
									<div class="col-lg-5 col-sm-5 col-xs-12">
										<h6>Total Used Time</h6>
										<p><text id="totalUsedTime">00 H: 00 M</text> <span>out of</span> <text id="totalDuration">0 H</text></p>
									</div>
									<div class="col-lg-3 col-sm-3 col-xs-12">
										<button class="extend_more_btn hvr-shutter-in-horizontal">Extend More</button>
									</div>
								</div>
							</li>

							<ul id="extendMore" style="display:none;" class="interview_post_form nterview_post_form_list">
										<li>
											<div class="row">
												<div class="col-lg-12">
													<ul class="checkout_ul">
														<li>
															<div class="col-lg-4">
																<h3>
																	$100 / hour
																	<span class="pull-right">x</span>
																</h3>
															</div>
															<div class="col-lg-2">
																<input type="number" name="" id="numberOfHours" class="form-control">
															</div>
															<div class="col-lg-4">
																<h3>no.of hours</h3>
															</div>
															<div class="col-lg-3">
																<p class="total-amount">$600.00</p>
															</div>
														</li>
														<li>
															<div class="col-lg-4"> </div>
															<div class="col-lg-1"></div>
															<div class="col-lg-4">
																<h3>Total</h3>
															</div>
															<div class="col-lg-3">
																<p class="checkout_total_p total-amount">$600.00</p>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</li>
							<div class="row">
										<div class="col-lg-3">
											<div class="paypal_icon_wrapper">
												<p>Payment Method</p>
												<img src="images/paypal_logo.png" alt="">
											</div>
										</div>
										<div class="col-lg-9">
											<ul class="apply_page_btnset_ul">
												<li>
													<button class="close_extend_more_btn  hvr-underline-reveal">back</button>
												</li>
												<li>
													<button class="close_extend_more_btn  hvr-underline-reveal">checkout and publish</button>
												</li>
											</ul>
										</div>
							</div>
								</ul>
						</ul>

					</div>
				</div>
				<div id="profileView" style="min-height:500px;" class="openday_profile_view_wrapper">
					<p class="click-to-view-info centered-info" ><i class="fa fa-search fa-fw"></i>Click 'View' to review candidates.</p>
				</div>
			</div>
			<div class="col-lg-3 col-sm-3 col-xs-12">
				<div class="interview_list_right_div">
					<select id="scheduleFilter">
						<option value="1">Chek-in Candidates with schedule</option>
						<option value="0">Chek-in Candidates without schedule</option>
					</select>
				</div>
				<div class="interview_list_right_ul_wrapper_div">
					<ul id="queue-item" class="interview_list_right_ul">
						
						
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" data-backdrop="static" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Start Interview</h4>
			</div>
			<div class="modal-body">
				<p>Click the start button to start the interview&hellip;</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" disabled id="start-interview-btn" data-dismiss="modal" class="waitingButton btn btn-primary"><i class="fa fa-spinner fa-spin fa-fw"></i>Waiting for Applicant</button>
				<button style="display: none;" type="button" id="start-interview-btn" data-dismiss="modal" class="startInterviewButton btn btn-primary"><i class="fa fa-video-camera fa-fw"></i>Start Interview</button>
			</div>
			</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- END -->
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
			<?php include 'include/js.php'; ?>
			<script type="text/javascript" src="js/app/chatroom.js"></script>
		</body>
	</html>