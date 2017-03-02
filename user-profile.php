<html>
    <head>
        <title id="profileName"></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">   
        <?php include 'include/css.php'; ?>
        <link rel="stylesheet" type="text/css" href="css/font-loader.css">
        <link rel="stylesheet" type="text/css" href="css/ad_manager_style.css">
    </head>

    <body class="inside">
      <!-- MUST DECLARE HERE THE FF -->
      <!-- User ID -->
        <input type="hidden" id="userId" value="<?php echo ($_GET['user_id']) ? $_GET['user_id'] : '1d5828aaf4687d05d1ae'; ?>">


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
                <main class="profile_page_main">
                    <div class="container">
                        <ul class="job_searchpage_success hidden" id="successCover">
                            <center><li><i class="fa fa-check" aria-hidden="true"></i>Successfully Uploaded Cover Photo </li></center>
                        </ul>
                        <div class="cover_photo">
                            <div id="image-cropper" class="image-editor">
                                <div class="cropit-preview" style="position: relative; width: 1140px; height: 270px; background-image: url(&quot;http://fs1.jobsglobal.us/e/act/get/name/7a7c78810f9e5876042ace979.png&quot;);"><div class="cropit-preview-image-container" style="position: absolute; overflow: hidden; left: 0px; top: 0px; width: 100%; height: 100%;"><img class="cropit-preview-image" alt="" style="transform-origin: left top 0px;"></div></div>
                                <div class="cover_resize_div">
                                    <input id="cropit-zoom-cover" class="cropit-image-zoom-input cropit-cover-range hidden" min="0" max="1" step="0.01" type="range">
                                    <input id="saveCropitCover" class="hidden" value="Save" type="submit">
                                </div>
                                <div class="profile_page_cover_browse">
                                    <span><i class="fa fa-picture-o" aria-hidden="true"></i>Change Cover</span>
                                    <input name="file" class="cropit-image-input upload_cover_btn" id="image-crop-cover" accept="image/*" type="file">
                                </div>
                            </div>
                            <div id="cover-spinner" class="spinner_wrapper hidden">
                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </main>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-4 col-xs-12">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="profile_page_avatarbox">
                                    <div class="image-editor">
                                        <div class="cropit-preview " style="position: relative; width: 180px; height: 180px; background-image: url(&quot;http://fs1.jobsglobal.us/e/act/get/name/11a3f0e7ed665876043cceed8.png&quot;);"><div class="cropit-preview-image-container" style="position: absolute; overflow: hidden; left: 0px; top: 0px; width: 100%; height: 100%;"><img class="cropit-preview-image" alt="" style="transform-origin: left top 0px;"></div></div>
                                        <div class="profile_page_avatar_browse">
                                            <span><i class="fa fa-camera" aria-hidden="true"></i>Change Profile</span>
                                            <input class="cropit-image-input upload_profile_photo_btn" id="image-crop" accept="image/*" type="file">
                                        </div>
                                        <div class="profile_pic_range_div">
                                            <input class="cropit-image-zoom-input hidden" id="cropit-zoom-profile" min="0" max="1" step="0.01" type="range">
                                            <input name="image-data" id="image-data" class="hidden-image-data" type="hidden">
                                            <input id="saveCropitProfile" class="hidden" value="Save" type="submit">
                                        </div>
                                    </div>
                                    <div id="profile-spinner" class="spinner_wrapper_profile hidden">
                                        <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <ul class="job_searchpage_success hidden" id="successUpload">
                                <li><i class="fa fa-check" aria-hidden="true"></i>Successfully Uploaded Photo </li>
                            </ul>
                            <div class="animated fadeIn hide successpersonal">
                                <ul class="job_searchpage_success">
                                    <li><i class="fa fa-check" aria-hidden="true"></i>Successfully updated.</li>
                                </ul>
                            </div>
                            
                            <div class="alertmsg_wrapper validateDiv hidden">
                                <ul class="job_searchpage_error errormsg add-form-error-container add-form-error-message hidden">
                                    <!-- APPEND ERROR MESSAGES HERE -->
                                </ul>
                            </div>
                            <div class="edit_form profile_details hide">
                                <ul class="apply_page_inner_ul">
                                    <li>
                                        <p>First Name</p>
                                        <input id="fname" name="fname" placeholder="First Name" type="text">
                                    </li>
                                    <li>
                                        <p>Middle Name</p>
                                        <input id="mname" name="mname" placeholder="Middle Name" type="text">
                                    </li>
                                    <li>
                                        <p>Last Name</p>
                                        <input id="lname" name="lname" placeholder="Last Name" type="text">
                                    </li>
                                    <li>
                                        <p>Current Country Location</p>
                                        <input type="text">
                                    </li>
                                    <li>
                                        <p>Preferred Position/s</p><p> (Hit enter to add more )</p><select id="p_position" name="p_position" multiple="multiple" class="pposition_error tokenize-sample" style="margin: 0px; padding: 0px; border: 0px none; display: none;"></select><div class="pposition_error tokenize-sample Tokenize"><ul class="TokensContainer"><li class="TokenSearch"><input size="5"></li></ul><ul class="Dropdown" style="display: none;"></ul></div>
                                    </li>
                                    <li>
                                        <p>Profile Link</p>
                                        <input id="link" name="link" placeholder="Url Link" type="text">
                                    </li>
                                    <li>
                                        <p>Preferred Industry</p>
                                        <input type="text">
                                    </li>
                                    <li>
                                        <p>Expected Salary</p>
                                        <ul class="two_column_ul">
                                            <li class="col-lg-6 col-sm-6 col-xs-6">
                                                <input id="salary" name="salary" type="text">
                                            </li>
                                            <li class="col-lg-6 col-sm-6 col-xs-6">
                                                <input type="text">
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <p>Looking for Oppurtunity</p>
                                        <div class="reg-select-container apply-select-container">
                                            <select id="opportunity_list" name="opportunity_list" type="text"><option value="">Oppurtunity Status</option><option value="1|Actively Looking for Opportunity" selected="">Actively Looking for Opportunity</option><option value="2|Not Interested for new Opportunity">Not Interested for new Opportunity</option></select>
                                            <span class="arrow" aria-hidden="true"></span>
                                        </div>
                                    </li>
                                    <li>
                                        <p>Country Code</p>
                                        <input type="text">
                                    </li>
                                    <li>
                                    <p>Mobile</p>
                                    <ul class="two_column_ul">
                                        <li class="col-lg-10 col-sm-10 col-xs-10">
                                                <input id="mobile" name="mobile" type="text">
                                        </li>
                                        <li class="col-lg-2 col-sm-2 col-xs-2">
                                            <button type="button" class="mobile_visibility_btn dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-cog" aria-hidden="true"></i>
                                            </button>
                                            <ul class="dropdown-menu mobile_visibility_ul" role="menu">
                                                <li><a class="mobile_settings" id="Public">Public</a></li>
                                                <li><a class="mobile_settings toggle_selected" id="OnlyMe">Only Me</a></li>
                                                <li><a class="mobile_settings" id="EmployerOnly">Employer Only</a></li>
                                            </ul>
                                            <input id="mobile_settings_value" value="Public" class="hidden" type="text">
                                        </li>
                                    </ul>
                                    </li>
                                    <li>
                                        <p>Ready to Relocate</p>
                                        <div class="select2-container" id="s2id_relocation"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">Select..</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" id="s2id_autogen4" type="text"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" type="text">   </div>   <ul class="select2-results">   </ul></div></div><select id="relocation" name="relocation" type="text" tabindex="-1" class="select2-offscreen"><option value="">Relocation</option><option value="1|Within the City">Within the City</option><option value="2|Within the State">Within the State</option><option value="3|Nationally">Nationally</option><option value="4|Internationaly">Internationaly</option></select>
                                    </li>
                                </ul>
                                <ul class="apply_page_btnset_ul">
                                    <li>
                                        <button type="button" id="profile_details" class="hvr-underline-reveal cancel_btn_personal">Cancel</button>
                                    </li>
                                    <li>
                                        <button type="button" class="hvr-underline-reveal profile_save">Save <i class="loading"></i></button>
                                    </li>
                                </ul>
                                <div id="prof-detail-spinner" class="spinner_wrapper hidden">
                                    <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="row" id="infoPersonalCover">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <h1 class="profile_name_h1 full_name_label">Jim carreycare</h1>
                                    <h3 class="profile_sub_h3 looking_for_label">(Actively Looking for Opportunity)</h3>
                                    <div class="col-lg-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <button id="promoteBtn" class="promote_btn">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                Promote
                                            </button>
                                        </div>
                                    </div>
                                    <ul class="profile_section_inner_ul">
                                        <li>
                                            <h2><i class="fa fa-suitcase" aria-hidden="true"></i>Preferred Position</h2>
                                            <p class="position_preferred_label"></p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-map-marker" aria-hidden="true"></i>Location</h2>
                                            <p class="location_label">India</p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-industry" aria-hidden="true"></i>Preferred Industry</h2>
                                            <p class="industry_label"><span class="itemize">Fertilizer/ Pesticides</span></p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-money" aria-hidden="true"></i>Expected Salary</h2>
                                            <p class="expected_salary_label"> </p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-envelope" aria-hidden="true"></i>Email</h2>
                                            <p class="contact_info_label">rafeeq@jobsglobal.com</p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-phone-square" aria-hidden="true"></i>Mobile Number</h2>
                                            <p class="mobile_info_label">++971563449952</p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-globe" aria-hidden="true"></i>Ready to Relocate</h2>
                                            <p class="relocation"></p>
                                        </li>
                                        <li>
                                            <h2><i class="fa fa-link" aria-hidden="true"></i>Profile Link</h2>
                                            <a href="#" class="profile_link"><a href="http://localhost/viewprofile.php?p=055828b302a84a335037" target="_new">http://localhost/viewprofile.php?p=055828b302a84a335037</a></a>
                                        </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-xs-12">
                                            <a href="#" class="profil_edit hvr-shutter-out-horizontal">Edit Profile</a>
                                        </div>
                                    </div>
                                    <ul class="pfofile_share_btnset_ul">
                                        <li>
                                            <a href="mailto:rafeeqtalr@gmail.com?subject=Profile Link&amp;body=http://localhost/viewprofile.php?p=055828b302a84a335037" class="email" id="email" target="_blank"><i class="fa fa-envelope" aria-hidden="true"></i>Email</a>
                                        </li>
                                        <li>
                                            <a href="https://twitter.com/share" data-count="none" class="twitter twitter-share-button tweet" id="tweet" target="_blank" data-url="http://localhost/viewprofile.php?p=055828b302a84a335037"><i class="fa fa-twitter" aria-hidden="true"></i>Tweet</a>
                                        </li>
                                        <li>
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=http://localhost/viewprofile.php?p=055828b302a84a335037" class="share" id="facebook" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i>Facebook</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-8 col-xs-12" id="profile_page">
                            <ul class="nav nav-tabs tab_ul">
                              <li class="active"><a data-toggle="tab" href="#home"><i class="fa fa-user-circle-o" aria-hidden="true"></i>My Profile</a></li>
                              <li><a data-toggle="tab" href="#menu1"><i class="fa fa-file-text" aria-hidden="true"></i>CV View</a></li>
                            </ul>
                            <div class="tab-content">
                              <div id="home" class="tab-pane fade in active">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <h3 class="profile_page_heading_h3">
                                            Professional Experience
                                            <a href="#" id="add_professional_exp_btn" class="add_professional_exp">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Add Experience
                                            </a>
                                        </h3>
                                        <div class="delete_confirmation_div hide animated fadeIn" role="alert" id="pEXP_delete">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> </button>
                                            <h5 id="deleteExperienceTitle"></h5>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal experience_delete" id="">Yes</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal delete_confirm_no" id="pEXP">No</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="adnimated fadeIn notification-experience hide">
                                            <ul class="job_searchpage_success">
                                                <li id="success-title-experience"></li>
                                            </ul>
                                        </div>
                                        <div class="alertmsg_wrapper errormsg add-form-error-container_exp hidden">
                                            <ul class="job_searchpage_error add-form-error-message_exp">
                                                <!-- APPEND ERROR MESSAGES HERE -->
                                            </ul>
                                        </div>
                                        <form class="edit_form professional_form hide animated fadeIn" action="" method="post">
                                            <ul class="apply_page_inner_ul">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Job Position</p>
                                                            <input id="job_position_exp" name="job_position_exp" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Name of Company</p>
                                                            <input id="comanay_name_exp" name="comanay_name_exp" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Location</p>
                                                            <input type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Industry</p>
                                                            <input type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                                <li>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-sm-5 col-xs-12">
                                                            <p>From</p>
                                                            <ul class="registration_edu_ul">
                                                                <li class="reg-select-container">
                                                                    <select id="from_month_exp" name="from_month_exp" type="text"><option value="">Month</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>
                                                                    <span class="arrow" aria-hidden="true"></span>
                                                                </li>
                                                                <li class="reg-select-container">
                                                                    <input type="text">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-5 col-sm-5 col-xs-12">
                                                            <p>To</p>
                                                            <ul class="registration_edu_ul">
                                                                <li class="reg-select-container">
                                                                    <input type="text">
                                                                </li>
                                                                <li class="reg-select-container">
                                                                    <input type="text">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-2 col-sm-2 col-xs-12">
                                                            <div class="row">
                                                                <input name="checkbox" class="present_cbox work_radio" type="checkbox"><p class="present_p">Present</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li id="wys_editor">
                                                    <p>Description</p>
                                                    <textarea></textarea>
                                                </li>
                                            </ul>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" id="professional_form" class="hvr-underline-reveal cancel_btn experience_btn experience_show_result">Cancel</button>
                                                </li>
                                                <li class="saving_btn_exp">
                                                    <button type="button" class="hvr-underline-reveal experience_save" id="save_exit">Save and Exit <i class="save_exit_loading_exp"></i></button>
                                                </li>
                                                <li class="saving_btn_exp">
                                                    <button type="button" class="hvr-underline-reveal experience_save_more" id="save_addmore">Save and Add More <i class="save_addmore_loading_exp"></i></button>
                                                </li>
                                                <li class="update_btn_exp hide">
                                                     <button type="button" class="hvr-underline-reveal experience_update" id="update_exit">Update<i class="update_loading_exp"></i></button>
                                                     <input id="experience_row_id" class="experience_row_id hide" type="text">
                                                </li>
                                            </ul>
                                            <div id="experience-spinner" class="spinner_wrapper hidden">
                                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                            </div>
                                        </form>
                                        <div class="prof_exp_result">
<ul class="professional_experience_ul delete_2266820 animated fadeIn">

<li>
    <h4>
        
    </h4>
    <ul class="edit_and_delete_btnset_ul">
        <li class="0">
            <a href="#" id="edit_2266820" class="edit_prof_exp"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        </li>
        <li class="0">
            <a href="#profile_page" id="delete_2266820" class="delete_prof_exp" data-position="" data-company=""><i class="fa fa-trash" aria-hidden="true"></i></a>
        </li>
    </ul>
</li>
<li>
    <h5></h5>
</li>
<li>
    <ul class="professional_exp_details_ul">
        <li>
            <p class="label_p">Industry</p>
            <h6>
                <i class="fa fa-industry" aria-hidden="true"></i> 
            </h6>
        </li>
        <li>
            <p class="label_p">Location</p>
            <h6>
                <i class="fa fa-map-marker" aria-hidden="true"></i> 
            </h6>
        </li>
        <li>
            <p class="label_p">Date</p>
            <h6>
                <i class="fa fa-calendar-o" aria-hidden="true"></i> undefined NaN - None
            </h6>
        </li>
    </ul>
</li>
<li>
    <h2>Description</h2>
    <p class="description_p">
        
    </p>
</li>
</ul></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12" id="scroll-to-edu">
                                    <div class="row">
                                        <h3 class="profile_page_heading_h3">
                                            Education
                                            <a href="#" id="add_education_btn" class="add_education_btn">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Add Education
                                            </a>
                                        </h3>
                                        <div class="delete_confirmation_div hide animated fadeIn" role="alert" id="education_delete">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> </button>
                                            <h5 id="deleteEducationTitle"></h5>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal education_delete" id="">Yes</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal delete_confirm_no" id="education">No</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="adnimated fadeIn notification-education hide">
                                            <ul class="job_searchpage_success">
                                                <li id="success-title-education"></li>
                                            </ul>
                                        </div>
                                        <div class="alertmsg_wrapper errormsg add-form-error-container_ecucation hidden">
                                            <ul class="job_searchpage_error add-form-error-message_education">
                                                <!-- APPEND ERROR MESSAGES HERE -->
                                            </ul>
                                        </div>
                                        <div class="edit_form education_form animated fadeIn hide">
                                            <ul class="apply_page_inner_ul">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Field of Study</p>
                                                            <input id="educ_course" name="educ_course" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Degree</p>
                                                            <div class="select2-container select2me" id="s2id_educ_degree"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">Select..</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" id="s2id_autogen7" type="text"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" type="text">   </div>   <ul class="select2-results">   </ul></div></div><select id="educ_degree" name="educ_degree" type="text" class="select2me select2-offscreen" tabindex="-1"><option value="">Degree</option><option value="Undergraduate Degree">Undergraduate Degree</option><option value="Associate's Degree">Associate's Degree</option><option value="Bachelor's Degree">Bachelor's Degree</option><option value="Graduate Degree">Graduate Degree</option><option value="Master's Degree">Master's Degree</option><option value="Doctoral Degree">Doctoral Degree</option><option value="Professional Degree">Professional Degree</option></select>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>School</p>
                                                            <input name="educ_school" id="educ_school" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Location</p>
                                                            <input type="text">
                                                        </li>
                                                    </div>
                                                </div>

                                                <li>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-sm-5 col-xs-12">
                                                            <p>From</p>
                                                            <ul class="registration_edu_ul">
                                                                <li class="reg-select-container">
                                                                    <select id="from_month_educ" name="from_month_educ" type="text"><option value="">Month</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>
                                                                    <span class="arrow" aria-hidden="true"></span>
                                                                </li>
                                                                <li class="reg-select-container">
                                                                    <input type="text">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-5 col-sm-5 col-xs-12">
                                                            <p>To</p>
                                                            <ul class="registration_edu_ul">
                                                                <li class="reg-select-container">
                                                                    <select id="to_month_educ_" name="to_month_educ_" type="text"><option value="">Month</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>
                                                                    <span class="arrow" aria-hidden="true"></span>
                                                                </li>
                                                                <li class="reg-select-container">
                                                                    <input type="">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-2 col-sm-2 col-xs-12">
                                                            <div class="row">
                                                                <input name="checkbox" class="present_cbox_educ work_radio" type="checkbox"><p class="present_p">Present</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" id="education_form" class="hvr-underline-reveal cancel_btn education_btn education_show_result">Cancel</button>
                                                </li>
                                                <li class="saving_btn_educ">
                                                    <button type="button" class="hvr-underline-reveal education_save" id="save_exit">Save and Exit <i class="save_exit_loading_exp"></i></button>
                                                </li>
                                                <li class="saving_btn_educ">
                                                    <button type="button" class="hvr-underline-reveal education_save_more" id="save_addmore">Save and Add More <i class="save_addmore_loading_educ"></i></button>
                                                </li>
                                                <li class="update_btn_educ hide">
                                                    <button type="button" class="hvr-underline-reveal education_update" id="update_exit">Update<i class="update_loading_educ"></i></button>
                                                    <input id="education_row_id" class="education_row_id hide" type="text">
                                                </li>
                                            </ul>
                                            <div id="education-spinner" class="spinner_wrapper hidden">
                                                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="profile_result_wrapper" id="result_education_list">
                                        


<div class="education_div"></div>
<ul class="professional_experience_ul educ_delete_1422702 animated fadeIn">
<input id="field_study_1422702" value="Information Technology" type="hidden">
<input id="degree_1422702" value="" type="hidden">
<input id="school_1422702" value="Indian Institute of Information Technology - IN" type="hidden">
<input id="location_1422702" value="" type="hidden">
<input id="duration_1422702" value="{&quot;start&quot;:&quot;2013-4-1&quot;,&quot;end&quot;:&quot;2013-4-1&quot;}" type="hidden">
<li>
    <h4>
        Information Technology
    </h4>
    <ul class="edit_and_delete_btnset_ul">
        <li class="0">
            <a href="#" id="educ_edit_1422702" class="edit_education"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        </li>
        <li class="0">
            <a href="#" id="educ_delete_1422702" class="delete_education" data-school="Indian Institute of Information Technology - IN" data-course="Information Technology"><i class="fa fa-trash" aria-hidden="true"></i></a>
        </li>
    </ul>
</li>
<li>
    <h5>Indian Institute of Information Technology - IN</h5>
</li>
<li>
    <ul class="professional_exp_details_ul">
        <li>
            <p class="label_p">Location</p>
            <h6>
                <i class="fa fa-map-marker" aria-hidden="true"></i> 
            </h6>
        </li>
        <li>
            <p class="label_p">Date</p>
            <h6>
                <i class="fa fa-calendar-o" aria-hidden="true"></i> Apr 2013 - Apr 2013
            </h6>
        </li>
    </ul>
</li>
</ul></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <h3 class="profile_page_heading_h3">
                                            Professional Skills
                                            <a href="#" id="add_prof_kills_btn" class="add_prof_kills_btn">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Edit Skills
                                            </a>
                                        </h3>
                                        <div class="alertmsg_wrapper errormsg add-form-error-container_skills hidden">
                                            <ul class="job_searchpage_error add-form-error-message_skills">
                                                <!-- APPEND ERROR MESSAGES HERE -->
                                            </ul>
                                        </div>
                                        <div class="adnimated fadeIn notification-skills hide">
                                            <ul class="job_searchpage_success">
                                                <li id="success-title-skills"></li>
                                            </ul>
                                        </div>
                                        <div class="edit_form skills_form hide animated fadeIn">
                                            <ul class="apply_page_inner_ul">
                                                <li>
                                                    <p>Type your Skills</p>
                                                    <input type="text">
                                                </li>
                                            </ul>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" id="skills_form" class="hvr-underline-reveal cancel_btn skills_btn skill_show_result">Cancel</button>
                                                </li>
                                                <li class="saving_btn_skill">
                                                    <button type="button" class="hvr-underline-reveal skills_save" id="save_exit">Update <i class="skills_loading"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="professional_skills_ul skill_result_list animated fadeIn"></ul>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <h3 class="profile_page_heading_h3">
                                            Social Information
                                            <a href="#" id="edit_social_info_btn" class="edit_social_info_btn">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Edit Social Info
                                            </a>
                                        </h3>
                                        <div class="alertmsg_wrapper errormsg add-form-error-container_social hidden">
                                            <ul class="job_searchpage_error add-form-error-message_social">
                                                <!-- APPEND ERROR MESSAGES HERE -->
                                            </ul>
                                        </div>
                                        <div class="adnimated fadeIn notification-social hide">
                                            <ul class="job_searchpage_success">
                                                <li id="success-title-social"></li>
                                            </ul>
                                        </div>
                                        <div class="edit_form social_info hide animated fadeIn">
                                            <ul class="apply_page_inner_ul">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-12 col-xs-12">
                                                        <li>
                                                            <p>Birthdate</p>
                                                            <ul class="birth_date_ul">
                                                                <li class="col-lg-4 col-sm-4 col-xs-4">
                                                                    <div class="reg-select-container apply-select-container">
                                                                        <select name="b_day" id="b_day" class="select2me"><option value="">Date</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>
                                                                        <span class="arrow" aria-hidden="true"></span>
                                                                    </div>
                                                                </li>
                                                                <li class="col-lg-4 col-sm-4 col-xs-4">
                                                                    <div class="reg-select-container apply-select-container">
                                                                        <select name="b_month" id="b_month" class="select2me"><option value="">Month</option><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>
                                                                        <span class="arrow" aria-hidden="true"></span>
                                                                    </div>
                                                                </li>
                                                                <li class="col-lg-4 col-sm-4 col-xs-4">
                                                                    <div class="reg-select-container apply-select-container">
                                                                        <select name="b_year" id="b_year" class="select2me"><option value="">Year</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option></select>
                                                                        <span class="arrow" aria-hidden="true"></span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-xs-12">
                                                        <li>
                                                            <p>Gender</p>
                                                            <ul class="gender_ul">
                                                                <li>
                                                                    <input name="gender" value="Male" selected="" type="radio">
                                                                    <p>Male</p>
                                                                </li>
                                                                <li>
                                                                    <input name="gender" value="Female" type="radio">
                                                                    <p>Female</p>
                                                                </li>
                                                                <li>
                                                                    <input name="gender" value="Other" type="radio">
                                                                    <p>Other</p>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Nationality</p>
                                                            
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Marital Status</p>
                                                            <div class="reg-select-container apply-select-container">
                                                                <select id="t_civil_status"><option value="">Civil Status</option><option value="1|Single">Single</option><option value="2|Married">Married</option><option value="3|Widowed">Widowed</option><option value="4|Separated">Separated</option><option value="5|Divorced">Divorced</option></select>
                                                                <span class="arrow" aria-hidden="true"></span>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Availability</p>
                                                            <input id="t_availability" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Visa Status</p>
                                                            <input id="t_visa_status" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Height(CM)</p>
                                                            <input id="t_height" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Weight (KG)</p>
                                                            <input id="t_weight" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                            </ul>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" id="social_info" class="hvr-underline-reveal cancel_btn social_btn show_social_result">Cancel</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal social_save" id="save_exit">Update <i class="social_loading"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="personal_info_ul animated fadeIn" id="personalInfo">
                                        <div class="row">
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Birthdate</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Gender</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i>male</h4>
        </li>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Marital Status</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Nationality</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i>Indian</h4>
        </li>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Weight</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Height</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Availability</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <li>
            <p class="label_p">Visa Status</p>
            <h4><i class="fa fa-caret-right" aria-hidden="true"></i></h4>
        </li>
    </div>
</div></ul>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <h3 class="profile_page_heading_h3">
                                            Other Social Information
                                            <a href="#" id="edit_other_social_info_btn" class="edit_other_social_info_btn">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Edit Social Info
                                            </a>
                                        </h3>
                                        <div class="alertmsg_wrapper errormsg add-form-error-container_other_social hidden">
                                            <ul class="job_searchpage_error add-form-error-message_other_social">
                                                <!-- APPEND ERROR MESSAGES HERE -->
                                            </ul>
                                        </div>
                                        <div class="adnimated fadeIn notification-other-social hide">
                                            <ul class="job_searchpage_success">
                                                <li id="success-title-other-social"></li>
                                            </ul>
                                        </div>
                                        <div class="edit_form other_social_info hide animated fadeIn">
                                            <ul class="apply_page_inner_ul">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Skype ID</p>
                                                            <input id="t_skype" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Facebook</p>
                                                            <input id="t_facebook" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Twitter</p>
                                                            <input id="t_google" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Yahoo ID</p>
                                                            <input id="t_yahoo" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>LinkedIn</p>
                                                            <input id="t_linkedin" type="text">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-6 col-xs-12">
                                                        <li>
                                                            <p>Instagram</p>
                                                            <input id="t_msn" type="text">
                                                        </li>
                                                    </div>
                                                </div>
                                            </ul>
                                            <ul class="apply_page_btnset_ul">
                                                <li>
                                                    <button type="button" id="other_social_info" class="hvr-underline-reveal cancel_btn social_btn show_social_media_result">Cancel</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="hvr-underline-reveal other_social_save" id="save_exit">Update <i class="other_social_loading"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="Social_info_ul animated fadeIn" id="social_info_ul">

                                        <div class="row">
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">Skype</p>
        <h4><i class="fa fa-skype" aria-hidden="true"></i></h4>
    </li>
</div>
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">Facebook</p>
        <h4><i class="fa fa-facebook-official" aria-hidden="true"></i></h4>
    </li>
</div>
</div>
<div class="row">
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">Twitter</p>
        <h4><i class="fa fa-twitter-square" aria-hidden="true"></i></h4>
    </li>
</div>
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">Yahoo ID</p>
        <h4><i class="fa fa-yahoo" aria-hidden="true"></i></h4>
    </li>
</div>
</div>
<div class="row">
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">LinkedIn</p>
        <h4><i class="fa fa-linkedin-square" aria-hidden="true"></i></h4>
    </li>
</div>
<div class="col-lg-6 col-sm-6 col-xs-12">
    <li>
        <p class="label_p">Instagram</p>
        <h4><i class="fa fa-instagram" aria-hidden="true"></i></h4>
    </li>
</div>
</div></ul>
                                    </div>
                                </div>
                              </div>
                              <div id="menu1" class="tab-pane fade hide">
                                <div class="cv_view_div" id="htmlViewer"></div>
                              </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-xs-12">
                            
                            <div class="col-lg-12 col-sm-5 col-xs-12 profile_strength_div">
                                <div class="row">
                                    <div id="profile_str" class="big percircle animate gt50"><span style="">80%</span><div class="slice"><div class="bar" style="border-color: rgb(123, 249, 32); transform: rotate(288deg);"></div><div class="fill" style="border-color: #7bf920"></div></div></div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-4 col-xs-12">
                                <div class="row">
                                    <ul class="profile_page_btn_ul" id="appendOtherInfo"><ul class="job_searchpage_error hidden" id="errorCv">
    <li id="errorCvMessage">
    </li>
</ul>
<ul class="job_searchpage_success hidden" id="successCv">
    <li><i class="fa fa-check" aria-hidden="true"></i>Successfully updated CV
</li></ul>
<li style="position: relative;">
    <div class="profile_page_upload_cv hvr-shutter-out-horizontal">
        <span><i class="fa fa-file-pdf-o" aria-hidden="true"></i>Upload CV</span>
        <input name="file" id="file" class="upload_cv" type="file">
    </div>
    <div class="spinner_wrapper hidden" id="uploadcv-spinner">
        <i class="fa fa-spinner fa-spin " aria-hidden="true" style="font-size: 14px;"></i>
    </div>
</li>
<li>
    <a class="hvr-shutter-out-horizontal" href="cover_letter.php">
        <i aria-hidden="true" class="fa fa-file-text-o">
        </i>
        My Cover Letter
    </a>
</li>
<li>
    <a class="hvr-shutter-out-horizontal" href="job_alert.php">
        <i aria-hidden="true" class="fa fa-bell">
        </i>
        My Job Alerts
    </a>
</li>
<li>
    <a class="hvr-shutter-out-horizontal" href="viewprofile.php?p=055828b302a84a335037" target="_blank" id="viewProfile">
        <i aria-hidden="true" class="fa fa-hand-o-right">
        </i>
        Profile Preview
    </a>
</li></ul>
                                </div>
                            </div>
                            <ol class="dj_right_side_ol">
                                <li id="suggested-jobs" class="hidden"><ul class="related_jobs_ul">
    <li>
        <h3>Suggested Jobs</h3>
    </li>
    <li>
        
    </li>
    <li>
        <a href="suggested_job.php" target="_blank"><i class="fa fa-hand-o-right" aria-hidden="true"></i>See All</a>
    </li>
</ul></li>
                            </ol>
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
        
    </body>
