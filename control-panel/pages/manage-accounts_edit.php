<?php 
	session_start();
	$account_id = $_GET['id'];

	require_once('../dao/accessDao.php');
	if((isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'Administrator') || $account_id == $_SESSION['account_id']) {		

		$carousels = accessDao::accessByGroup('carousel');
		$rewards = accessDao::accessByGroup('rewards');
		$news = accessDao::accessByGroup('news and offers');
		$partners = accessDao::accessByGroup('partners');
		$recipients = accessDao::accessByGroup('recipient');

		require_once('../dao/accountDao.php');

		if($_SESSION['account_id'] != '1' && $account_id == '1') { //REGULAR ADMIN Cannot modified a Super Admin
			echo '<script> window.history.back(); </script>';
		}
		
		$account = accountDao::accountDetail($account_id);
		$accList = accountDao::accountAdminList();
		$num = sizeof($accList);

	} else {
		echo '<script> window.history.back(); </script>';
	}

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>AllRewards cPanel | Update User Account Details</title> 

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" /> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="icon" type="image/png" href="../favicon.png">

	<link href="../lib/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
	<link href="../lib/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>

	<link href="../styles/normalize.css" rel="stylesheet" type="text/css"/>
	<link href="../styles/component.css" rel="stylesheet" type="text/css" />

	<!-- FONTAWESOME -->
	<link href="../lib/fontawesome/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	<!-- RESPONSIVE GRID -->
	<link href="../lib/responsiveGrid/responsivegrid.css" rel="stylesheet">

	<!-- FORM VALIDATOR -->
	<link href="../lib/form-validator/theme-default.min.css" rel="stylesheet" type="text/css" />

	<!-- MAIN STYLESHEETS and JS -->
	<link href="../styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="main-container">

	<div id="body-box">

		<section id="side-menu-section">
			<?php include('../inc/menu-sidemenu.php') ?>
		</section><!-- ../endof #side-menu-section -->

		<section id="main-body-section">

			<div id="content-container">

				<div class="section group col span_2_of_2">
						
					<form id="accountForm" enctype="multipart/form-data" class="formular" name="example1" method="post" action="../controller/controller.accounts.php?action=update_account">

						<div class="span-content-header">
							<h1>Update User Account Details</h1>
						</div><!-- ../endof .span-content-header -->

						<input name="id" type="hidden" value="<?= $_GET['id'] ?>">

						<div class="span-content-container form-add">

							<table>
								<tr>
									<td>
										<h2><span class="highlight">IMPORTANT:</span> Fields with an asterisk (*) mean that they are required.</h2>
									</td>
								</tr>

								<?php if($_SESSION['account_type'] == 'Administrator') { ?>
								<!-- ACCOUNT TYPE -->
								<tr>
									<td>
										<label for="user_type">Account Type <span class="highlight">*</span></label>
										<div class="remark">Choosing <b>Moderator</b> allows you to further manage the user permissions in using this CMS below.</div>
										<select id="user_type" name="user_type" tabindex="1" class="validate[required]">
											<option value="Moderator" 
											<?php 
												if($account['account_type'] == 'Moderator'){ echo 'selected'; } 
												if($account_id == $_SESSION['account_id'] || $num < 2) { echo 'disabled'; } 
											?> >Moderator
											</option>
											<option value="Administrator" <?php if($account['account_type'] == 'Administrator'){ echo 'selected'; } ?> >Administrator</option>
										</select>
									</td>
								</tr>

								<!-- ACCOUNT PERMISSIONS -->
								<tr id="permission-title" class="no-divider">
									<td>
										<label>Account Permissions</label>
									</td>
								</tr>
								<tr id="permission-options">
									<td>

										<div class="permission-div">
											<b>Featured Ads</b>
											<?php foreach($carousels as $carousel) { 
												$access = accessDao::accessPage($_GET['id'], $carousel['permission_id']); ?>
											<div>
												<label><?= $carousel['permission_pages'] ?>
													<input name="carousel[]" type="checkbox" value="<?= $carousel['permission_id'] ?>" <?php if($access) { echo 'checked'; }  ?> >
												</label>
											</div>
											<?php } ?>
										</div>

										<div class="permission-div">
											<b>Rewards</b>
											<?php foreach($rewards as $reward) { 
												$access = accessDao::accessPage($_GET['id'], $reward['permission_id']); ?>
											<div>
												<label><?= $reward['permission_pages'] ?>
													<input name="reward[]" type="checkbox" value="<?= $reward['permission_id'] ?>" <?php if($access) { echo 'checked'; }  ?> >
												</label>
											</div>
											<?php } ?>
										</div>

										<div class="permission-div">
											<b>News &amp; Events</b>
											<?php foreach($news as $offer) { 
												$access = accessDao::accessPage($_GET['id'], $offer['permission_id']); ?>
											<div>
												<label><?= $offer['permission_pages'] ?>
													<input name="offer[]" type="checkbox" value="<?= $offer['permission_id'] ?>" <?php if($access) { echo 'checked'; }  ?> >
												</label>
											</div>
											<?php } ?>
										</div>

										<div class="permission-div">
											<b>Partners</b>
											<?php foreach($partners as $partner) { 
												$access = accessDao::accessPage($_GET['id'], $partner['permission_id']); ?>
											<div>
												<label><?= $partner['permission_pages'] ?>
													<input name="partner[]" type="checkbox" value="<?= $partner['permission_id'] ?>" <?php if($access) { echo 'checked'; }  ?> >
												</label>
											</div>
											<?php } ?>
										</div>

										<div class="permission-div">
											<b>Inquiry Recipients</b>
											<?php foreach($recipients as $recipient) { 
												$access = accessDao::accessPage($_GET['id'], $recipient['permission_id']); ?>
											<div>
												<label><?= $recipient['permission_pages'] ?>
													<input name="recipient[]" type="checkbox" value="<?= $recipient['permission_id'] ?>" <?php if($access) { echo 'checked'; }  ?> >
												</label>
											</div>
											<?php } ?>
										</div>
									</td>
								</tr>
								<?php } ?>

								<!-- LOGIN INFORMATION -->
								<tr class="no-divider">
									<td>
										<label>Login Information</label>
									</td>
								</tr>
								<!-- USERNAME -->
								<tr class="no-divider">
									<td>
										<label for="username">Username <span class="highlight">*</span></label>
										<input name="username" id="username" type="text" value="<?= $account['account_username'] ?>" tabindex="2"
											data-sanitize="trim lower"
											data-validation="required"/>
									</td>
								</tr>

								<!-- PASSWORD -->
								<tr>
									<td>
										<input name="password" id="password" type="hidden" value="<?= $account['account_password'] ?>" />

										<label for="password_confirmation">Password</label>
										<div class="remark">Leave this blank if you do not wish to change.</div>
										<input name="password_confirmation" id="password_confirmation" type="password" tabindex="3"
											data-sanitize="trim"
											data-validation="strength"
											data-validation-strength="2"
											data-validation-optional="true"/>

										<label for="password">Confirm Password</label>
										<div class="remark">Leave this blank if you do not wish to change.</div>
										<input name="password" id="password" type="password" tabindex="4"
											data-sanitize="trim"
											data-validation="confirmation"
											data-validation-depends-on="password_confirmation"/>
									</td>
								</tr>

								<!-- GENERAL INFORMATION -->
								<tr class="no-divider">
									<td>
										<label>General Information</label>
									</td>
								</tr>
								<!-- FULL NAME -->
								<tr>
									<td>
										<label for="display_name">Full Name <span class="highlight">*</span></label>
										<input name="display_name" id="display_name" type="text" value="<?= $account['account_display_name'] ?>" tabindex="5"
											data-sanitize="trim"
											data-validation="custom length"
											data-validation-regexp="^[a-zA-Z\s]*$"
											data-validation-length="min3"/>
									</td>
								</tr>
								<!-- FULL NAME -->
								<tr>
									<td>
										<label for="email_address">E-mail Address <span class="highlight">*</span></label>
										<input name="email_address" id="email_address" type="email" value="<?= $account['account_email'] ?>" tabindex="6"
											data-sanitize="trim"
											data-validation="email"/>
									</td>
								</tr>
							</table><!-- ../endof table -->
							
						</div><!-- ../endof .span-content-container -->

						<div class="span-content-footer">
							<button class="cms-btn btn" tabindex="7">Update</button>
							<a href="manage-accounts.php" class="cms-btn cancel-btn btn">Cancel</a>
						</div><!-- ../endof .span-content-footer -->
					
					</form>	

				</div><!-- ../endof .section.group -->
				
			</div><!-- ../endof #content-container -->
			
		</section><!-- ../endof #main-body-section -->

	</div><!-- ../endof #body-box -->

</div><!-- ../endof #main-container -->

<script src="../lib/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="../lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

<!-- FORM VALIDATOR -->
<script src="../scripts/custom-file-input.js" type="text/javascript"></script>
<script src="../lib/form-validator/jquery.form-validator.min.js" type="text/javascript"></script>

<!-- CUSTOM SCRIPTS -->
<script src="../scripts/accounts.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#menu-accounts").addClass('active');
	
	$.validate({
		form : '#accountForm',
		modules   : 'security, sanitize',
		onModulesLoaded : function() {
			//Show strength of password
			$('input[name="password"]').displayPasswordStrength();
		}
	});

	$("#menu-logout").click(function(){
		var name = $("#dname").val();
		if ( confirm("You are currently logged-in as " + name + ". Are you sure you want to sign out?") == true) {
			location.href='../logout.php';
		} else {
			return;
		}
	});
});
</script>

</body>
</html>