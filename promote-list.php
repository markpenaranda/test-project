<!DOCTYPE html>
<html>
<head>
	     <?php include 'include/css.php'; ?>
        <link rel="stylesheet" type="text/css" href="css/font-loader.css">
        <link rel="stylesheet" type="text/css" href="css/ad_manager_style.css">

</head>
 <body class="inside">

      <!-- MUST DECLARE HERE THE FF -->
  
     	<input type="hidden" id="userId" value="1d5828aaf4687d05d1ae">

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
            <div class="container row">
                  <div class="col-md-3">
                      <h5>Openday List To Be Promoted</h5>
                      <ul  id="opendayList">
                          
                      </ul>

                  </div>

                  <div class="col-md-3">
                      <h5>Job List To Be Promoted</h5>
                      <ul  id="jobList">
                          
                      </ul>

                  </div>

                   <div class="col-md-3">
                      <h5>Candidate List To Be Promoted</h5>
                      <ul  id="candidateList">
                          
                      </ul>

                  </div>

                 <div class="col-md-3">
                      <h5>Page List To Be Promoted</h5>
                      <ul  id="companyList">
                          
                      </ul>

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
            <script type="text/javascript" src="js/app/promote-list.js"></script>
    </body>
</html>