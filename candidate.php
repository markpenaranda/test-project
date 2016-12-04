<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<?php
		include 'include/css.php';
	?>

	<!-- Document Title
	============================================= -->
	<title>Open Day Live Interview | Applicant</title>

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
      <input type="hidden" id="roomId" value="1">
      <!-- User ID -->
      <input type="hidden" id="userId" value="2">
      <!-- Applicant's Initial Hour(s) -->
      <input type="hidden" id="initialTime" value="0">

  <!-- END MUST DECLARE HERE THE FF -->

  <div id="candidate-interview" class="row content">
    <!-- Video Camera -->
      <div class="col-md-6 col-md-offset-3 col-sm-12 centered">
        <i class="fa fa-users fa-lg"></i>
        <h4>Mariot Food and Beverage Bound to Qatar</h4>
        <span>XYZ Company <i class="fa fa-circle text-success"></i></span>
        <div  style="margin-top: 5px; width: 100%; background-color: black; position:relative; height: 350px;">
            <video id="remoteVideo" autoplay style="width: : 100%; position:absolute; left: 0; top:0"></video>
            <video id="localVideo" autoplay muted style="height: 100px; position:absolute; right: 0; bottom:0"></video>
        </div>
      </div>

    <!-- End Video Camera -->

    <!-- 2nd Column -->
      <div class="col-md-6 col-md-offset-3  candidate">
        <div>
          <p>Schedule 08:00AM - 08:30AM (Dubai Time)</p>
          <!-- <p class="pull-right" id="lapseTime"></p> -->
          
        </div>
        <div class="chat">
          <div class="chat_window">
        
   
        <ul class="messages">

        </ul>
        <div class="bottom_wrapper clearfix"><div class="message_input_wrapper"><input class="message_input" placeholder="Type your message here..."  id="message" /></div><div class="send_message"><div class="icon"></div><div class="text send">Send</div></div></div></div><div class="message_template"><li class="message"><div class="text_wrapper"><div class="text"></div></div></li></div>
        </div>

      </div>
    <!-- End 2nd Column -->

  </div>

  <div id="room-details" class="row content">
    <div class="col-md-8 col-md-offset-2 col-sm-12 centered">
        <i class="fa fa-users fa-lg"></i>
        <h4>Mariot Food and Beverage Bound to Qatar</h4>
        <fieldset style="text-align: left;">
          <legend>Open Day Online Video Interview</legend>

          

          <ul class="list-inline">
              <li><label>Company</label><span>Company XYZ</span></li>
              <li><label>Date</label><span>January 01, 2017</span></li>
              <li><label>Scheduled Time</label><span>Waiting List</span></li>
          </ul>
          <div class="row">
            <div class="col-md-7">
              <dl class="dl-horizontal">
                <dt>Introduction</dt>
                <dd>Lorem Ipsum Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</dd>
              </dl>
            </div>
            <div class="col-md-5">
              <span>Job Position - Fulltime</span>
              <dl class="dl-horizontal">
                <dt>Waiter/Waitress</dt>
                <dd><a href="#">view job details</a></dd>
                <dt>Waiter/Waitress</dt>
                <dd><a href="#">view job details</a></dd>
                <dt>Waiter/Waitress</dt>
                <dd><a href="#">view job details</a></dd>
              </dl>
            </div>
          </div>
        </fieldset>
    </div>
  </div>
  <div id="waitingDiv" class="waiting-details" class="row content centered">
      
  </div>



	</div><!-- #wrapper end -->

	

	<?php
		include 'include/js.php';
	?>

	<script type="text/javascript" src="js/app/candidate.js"></script>

</body>
</html>