<!DOCTYPE html>
<html dir="ltr" lang="en-US">
	<head>
		<?php
			include 'include/css.php';
		?>
		<!-- Document Title
		============================================= -->
		<title>Open Day Live Interview | Employer</title>
	</head>
	<body class="stretched">
		<!-- Document Wrapper
		============================================= -->
		<div id="wrapper" class="clearfix">
			<?php
				include 'include/navigation.php';
			?>


			<!-- MUST DECLARE HERE THE FF -->
			<!--  ROOM ID -->
			<input type="hidden" id="roomId" value="<?php echo $_GET['openday'] ?>">
			<!-- Current User/Admin Id -->
			<input type="hidden" id="userId" value="1">
			<!-- Applicant Id -->
			<input type="hidden" id="applicantId" value="2">
			<!-- Applicant's Initial Hour(s) -->
			<input type="hidden" id="initialTime" value="0">
			<!-- END MUST DECLATE HERE THE FF -->



			<div class="masthead">
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label>Today's Event</label>
							<select class="form-control">
								<option>Mariot Food and Beverage Bound to Qatar</option>
								<option>Mariot Food and Beverage Bound to Qatar</option>
								<option>Mariot Food and Beverage Bound to Qatar</option>
							</select>
						</div>
					</div>
					<div class="col-md-2 centered">
						<p>
							<i class="fa fa-commenting-o fa-lg"></i><br>
							<span class="label label-success">LIVE</span> Open Day Online Video Interview
						</p>
					</div>
					<div class="col-md-3 small-text">
						<div><span>Total Check-in with Schedule: <b id="checkInWithSchedule">20</b></span></div>
						<div><span>Total Not Yet Check-in with Schedule: <b id="notCheckInWithSchedule">20</b></span></div>
						<div><span>Total Waiting List: <b id="waitingListCount">5</b></span></div>

					</div>
					<div class="col-md-2">
						<button class="btn btn-danger">Stop Adding Queue</button>
					</div>
				</div>
			</div>
			<div class="row content">
				<!-- Video Camera -->
				<div class="col-md-4 centered">
					<h4>Mariot Food and Beverage Bound to Qatar</h4>
					<span id="liveCandidateDetails"></span>
					<div style="margin-top: 5px; width: 100%; height: 280px; position: relative; background-color: black;">
					<video id="remoteVideo" autoplay style="width: : 100% !important; position:absolute; left: 0; top:0"></video>
				<video id="localVideo" autoplay muted style="height: 100px; position:absolute; right: 0; bottom:0"></video>

			</div>
		</div>
		<!-- End Video Camera -->
		<!-- 2nd Column -->
		<div class="col-md-5">
			<div class="profile-viewer" style="min-height: 250px; margin-bottom: 15px;">
				<iframe src="/profile" width="100%" height="250px"></iframe>
			</div>
			<div class="row extend-hours centered">
				<div class="item"  style="">
					<label>Lapse Time</label>
					<p id="lapseTime">00:00:00</p>
				</div>
				<div class="item" style="">
					<label>Total Used Time</label>
					<p id="totalUsedTime" >00:00:00</p>
				</div>
				<div class="item" style="">
					<p><button class="btn btn-success btn-sm" data-target="#extendMoreModal" data-toggle="modal">Extend More</button></p>
				</div>
			</div>
				<div class="chat">

				<div class="chat_window">

					<ul class="messages">
					</ul>
					<div class="bottom_wrapper clearfix"><div class="message_input_wrapper"><input class="message_input" placeholder="Type your message here..."  id="message" /></div><div class="send_message"><div class="icon"></div><div class="text send">Send</div></div></div></div><div class="message_template"><li class="message"><div class="text_wrapper"><div class="text"></div></div></li></div>
				</div>
		</div>
			<!-- End 2nd Column -->
			<!-- 3rd Column -->
			<div class="col-md-3 queue-section">
				<div class="form-group">
					<select class="form-control">
						<option>Check-in Candidates with Schedule</option>
						<option>Check-in Candidates with Schedule</option>
					</select>
				</div>
				<div id="queue-item">

				</div>
			</div>
			<!-- End 3rd Column -->
			<!-- Modal -->
			<div class="modal fade" id="startInterviewModal" tabindex="-1" role="dialog" data-backdrop="static" >
				<div class="modal-dialog" role="document">
					<div class="modal-content">

						<div class="modal-body">
							<p>Click the start button to start the interview&hellip;</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="button" id="start-interview-btn" data-dismiss="modal" class="btn btn-primary"><i class="fa fa-video-camera fa-fw"></i>Start Interview</button>
						</div>
						</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
						</div><!-- /.modal -->
						<!-- Modal -->
						<div class="modal fade" id="extendMoreModal" tabindex="-1" role="dialog" data-backdrop="static" >
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Extend More Hours</h4>
									</div>
									<div class="modal-body">
										<p>
											<input type="hidden" id="rate" value="100" name="">
											<span class="centered">Openday Live Promo</span>
											<span><b>$100</b>/hour x</span>
											<span><input id="extend-hours-input" type="number" name=""></span>
											<span>no. of hours</span>
											<span>=</span>
											<span>$<span id="totalPrice">0</span></span>
										</p>

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" id="start-interview-btn" data-dismiss="modal" class="btn btn-primary">Pay Now</button>
									</div>
									</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
									</div><!-- /.modal -->
									<!-- Invite Loading -->
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
												</div><!-- #wrapper end -->

												<?php
													include 'include/js.php';
												?>
												<script type="text/javascript" src="js/app/chatroom.js"></script>
											</body>
										</html>
