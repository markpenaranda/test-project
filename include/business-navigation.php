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

		            <div class="col-lg-9 col-sm-8 col-xs-10">
		            	<nav class="" role="navigation">
							<div class="navbar-header">
								<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#example-navbar-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<div id="example-navbar-collapse" class="collapse navbar-collapse">
								<ul class="header_btn_ul">
									<li>
										<a href="#">Home</a>
									</li>
							        <li>
							            <a href="#">CV search</a>
							        </li>
									<li>
										<a href="#">My Jobs</a>
									</li>
									<li>
										<a href="#">Shortlisted</a>
									</li>
									
									<li class="dropdown">
							          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Open Day <span class="caret"></span></a>
							          <ul style="background-color: #242424; text-align: left;" class="dropdown-menu">
							            <li><a href="/create-room.php">Create Open Day</a></li>
							            <li><a href="/openday-candidates.php">Candidates Per Open Day</a></li>
							            <li><a href="/room.php">Live Open Day</a></li>

							          </ul>
							     </li>
								</ul>
							</div>
						</nav>
		            </div>

		            <div class="col-lg-1 col-sm-1 col-xs-2">
		            	<div class="row">
		            		<ul class="nav_second_ul">
									<li class="notification-button dropdown_wrapper">
									
		                	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
												<i class="fa fa-globe" aria-hidden="true"></i>
												<span class="badge badge--notification">4</span>

											</a>
		                  <ul class="notification-list dropdown-menu">
		      							
		                  
		                  </ul>

		                
								</li>
								<li>
									<a href="#">
										<i class="fa fa-envelope" aria-hidden="true"></i>
									</a>
								</li>
							</ul>
						</div>
		            </div>

		            <div class="col-lg-1 col-sm-2 col-xs-2">
		                <div class="dropdown_wrapper">
		                	<a class="profile_icon_a" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		                        <img class="header_profile_icon profile_avatar">
		                        <i class="fa fa-caret-down" aria-hidden="true"></i>
		                	</a>
		                  <ul class="dropdown-menu">
		                    <li><a href="profile.php"><?= $_SESSION['user_fullname'] ?></a></li>
		                    <!--li><a href="profile.php">Profile</a></li-->
		                    <li><a href="preference.php">Preferences</a></li>
		                    <li><a href="#" id="user_logout">Logout</a></li>
		                  </ul>
		                </div>
		            </div>
		        </div>
		    </div>
		</header>