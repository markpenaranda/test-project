<html>
	<head>
		<?php include 'include/css.php'; ?>
	</head>
	<body class="inside">
		<!-- MUST DECLARE HERE THE FF -->
		<!--  ROOM ID -->
		<input type="hidden" id="roomId" value="<?php echo $_GET['openday'] ?>">
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
									<h1 class="opnday_candidate_name">Candidate 001 : John Doe</h1>
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
			<div class="col-lg-5 col-sm-5 col-xs-12">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<div class="row">
						<ul class="todays_events_ul">
							<li>
								<h6>Todays Event</h6>
								<select>
									<option>jobsglobal</option>
								</select>
							</li>
							<li>
								<div class="row">
									<div class="col-lg-4">
										<h6>Total Check-In with Schedule</h6>
										<p id="checkInWithSchedule">50</p>
									</div>
									<div class="col-lg-5">
										<div class="row">
											<h6>Total Not yet Check-In with Schedule</h6>
											<p id="notCheckInWithSchedule">5</p>
										</div>
									</div>
									<div class="col-lg-3">
										<h6>Total Waiting List</h6>
										<p id="waitingListCount">50</p>
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
										<p>05:05:10:09</p>
									</div>
									<div class="col-lg-5 col-sm-5 col-xs-12">
										<h6>Total Used Time</h6>
										<p>05 H: 05 M <span>out of</span> 6 H</p>
									</div>
									<div class="col-lg-3 col-sm-3 col-xs-12">
										<button class="extend_more_btn hvr-shutter-in-horizontal">Extend More</button>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="openday_profile_view_wrapper">
					<ul class="openday_recruitment_tools_ul">
						<li>
							<a href="#">
								<i class="fa fa-file-text-o" aria-hidden="true"></i>
								<p>Screen Applicant</p>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-users" aria-hidden="true"></i>
								<p>Short List</p>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-download" aria-hidden="true"></i>
								<p>Download CV</p>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-weixin" aria-hidden="true"></i>
								<p>Zmail</p>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-envelope" aria-hidden="true"></i>
								<p>Email CV</p>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-flag" aria-hidden="true"></i>
								<p>Application Status</p>
							</a>
						</li>
					</ul>
					<div class="openday_profile_view_container">
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-3 col-sm-3 col-xs-12">
									<div class="row">
										<img src="http://fs1.jobsglobal.us/tree/e47/02fc3ee3c7e4584421f2bc43a.png" class="openday_profile_pic">
									</div>
								</div>
								<div class="col-lg-9 col-sm-9 col-xs-12">
									<ul class="openday_profile_details_ul">
										<li>
											<h1>Khian Smile</h1>
											<h2>Software Engineer</h2>
										</li>
										<li>
											<ul class="openday_profile_details_inner_ul">
												<li>
													<p class="label_p">Industry</p>
													<h6>
													Human Resources/Recruitement
													</h6>
												</li>
												<li>
													<p class="label_p">Location</p>
													<h6>
													UNITED ARAB EMIRATES
													</h6>
												</li>
												<li>
													<p class="label_p">Expected Salary</p>
													<h6>
													10,000 AED
													</h6>
												</li>
												<li>
													<p class="label_p">Email</p>
													<h6>
													xxxxxxxxxx@gmail.com
													</h6>
												</li>
												<li>
													<p class="label_p">Phone</p>
													<h6>
													00971 55 555 6666
													</h6>
												</li>
												<li>
													<p class="label_p">Profile Link</p>
													<h6>
													<a>
														http://jobsportal.profile.com/0982387472
													</a>
													</h6>
												</li>
											</ul>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<div class="row">
								<h3 class="profile_page_heading_h3">
								Professional Experience
								</h3>
								<div class="prof_exp_result">
									<ul class="professional_experience_ul animated fadeIn">
										<li>
											<h4>
											Web designer
											</h4>
										</li>
										<li>
											<h5>Jobsglobal</h5>
										</li>
										<li>
											<ul class="professional_exp_details_ul">
												<li>
													<p class="label_p">Industry</p>
													<h6>
													<i class="fa fa-industry" aria-hidden="true"></i> Human Resources/Recruitement
													</h6>
												</li>
												<li>
													<p class="label_p">Location</p>
													<h6>
													<i class="fa fa-map-marker" aria-hidden="true"></i> UNITED ARAB EMIRATES
													</h6>
												</li>
												<li>
													<p class="label_p">Date</p>
													<h6>
													<i class="fa fa-calendar-o" aria-hidden="true"></i> Nov 2015 - undefined NaN
													</h6>
												</li>
											</ul>
										</li>
										<li>
											<h2>Description</h2>
											<p class="description_p">
												Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
											</p>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<div class="row">
								<h3 class="profile_page_heading_h3">
								Education
								</h3>
								<div id="" class="profile_result_wrapper">
									<ul class="professional_experience_ul animated fadeIn">
										<li>
											<h4> Web designing </h4>
										</li>
										<li>
											<h5>Rays animation</h5>
										</li>
										<li>
											<ul class="professional_exp_details_ul">
												<li>
													<p class="label_p">Location</p>
													<h6>
													<i class="fa fa-map-marker" aria-hidden="true"></i>
													INDIA
													</h6>
												</li>
												<li>
													<p class="label_p">Date</p>
													<h6>
													<i class="fa fa-calendar-o" aria-hidden="true"></i>
													Feb 2012 - Mar 2015
													</h6>
												</li>
											</ul>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<div class="row">
								<h3 class="profile_page_heading_h3">
								Professional Skills
								</h3>
								<ul class="professional_skills_ul skill_result_list animated fadeIn">
									<li>
										<p>HTML</p>
									</li>
									<li>
										<p>Graphic Design</p>
									</li>
									<li>
										<p>web design</p>
									</li>
									<li>
										<p>ghdfgh</p>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
							<div class="row">
								<h3 class="profile_page_heading_h3">
								Social Information
								</h3>
								<ul id="personalInfo" class="personal_info_ul animated fadeIn">
									<div class="row">
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Birthdate</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												Apr 15, 1992
												</h4>
											</li>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Gender</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												Male
												</h4>
											</li>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Marital Status</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												Single
												</h4>
											</li>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Nationality</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												Indian
												</h4>
											</li>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Weight</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												50 KG
												</h4>
											</li>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Height</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												6.ft
												</h4>
											</li>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Availability</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												xxxxx
												</h4>
											</li>
										</div>
										<div class="col-lg-6 col-sm-6 col-xs-12">
											<li>
												<p class="label_p">Visa Status</p>
												<h4>
												<i class="fa fa-caret-right" aria-hidden="true"></i>
												Visit Visa
												</h4>
											</li>
										</div>
									</div>
								</ul>
							</div>
						</div>
					</div>
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
				<button type="button" id="start-interview-btn" data-dismiss="modal" class="startInterviewButton btn btn-primary"><i class="fa fa-video-camera fa-fw"></i>Start Interview</button>
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