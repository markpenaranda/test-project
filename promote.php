<html>
    <head>
        <title id="profileName"></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">   
        <?php include 'include/css.php'; ?>
        <link rel="stylesheet" type="text/css" href="css/font-loader.css">
        <link rel="stylesheet" type="text/css" href="css/ad_manager_style.css">
         <style>
      #maps {
        width: 100%;
        height: 400px;
        background-color: grey;
      }
    </style>
    </head>

    <body>


     <!-- MUST DECLARE HERE THE FF -->
  
      <input type="hidden" id="tbp_id" value="<?php echo $_GET['tbp'] ?>">
      <input type="hidden" id="promotion_type" value="<?php echo $_GET['type'] ?>">
      <input type="hidden" id="userId" value="05582c0b47a4aab16bcd">

      <!-- END MUST DECLARE HERE THE FF -->




        <header>
            <div class="container">
                <div class="row">
                    <div class="col-lg-1 col-sm-2 col-xs-12">
                        <div class="brand">
                            <a href="profile.php">
                                <img src="images/logo.png" alt="" />
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-10 col-sm-8 col-xs-10">
        				<ul class="header_btn_ul">
        					<li>
        						<a href="profile.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
        					</li>
                            <li>
                                <a href="search_job.php"><i class="fa fa-search" aria-hidden="true"></i> Search Job</a>
                            </li>
        					<li>
        						<a href="my_jobs.php"><i class="fa fa-suitcase" aria-hidden="true"></i> My Jobs</a>
        					</li>

        				</ul>
                    </div>
                    <div class="col-lg-1 col-sm-2 col-xs-2">
                        <div class="dropdown_wrapper">
                        	<a class="profile_icon_a" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img class="header_profile_icon profile_avatar">
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                        	</a>
                          <ul class="dropdown-menu">
                          <li><a href="profile.php"><?= $_SESSION['user_fullname'] ?></a></li>
                          <li><a href="javascript:(0)">Your Pages</a></li>
                          <li id='pageList'></li>
                            <!--li><a href="profile.php">Profile</a></li-->
                            <li><a href="preference.php">Preferences</a></li>
                             <li><a href="employer/registration-login.php">Create Page</a>
                            </li>
                            <li><a href="#" id="user_logout">Logout</a></li>
                          </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section>
            <div class="orange_header">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <h1><i class="fa fa-suitcase" aria-hidden="true"></i>Promote Page</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mainPromoteManagement" class="container">
     
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
            <script src="https://maps.google.com/maps/api/js?key=AIzaSyChnFIHR10bZX3PlyVMVorh3Jxj-zTEE7k"></script>
            <script type="text/javascript" src="js/gmaps.js"></script>
            <script type="text/javascript" src="js/app/ad-management.js"></script>
    </body>
