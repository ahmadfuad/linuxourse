<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/foundation.css');?>" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/foundation-icons.css');?>" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/normalize.css')?>" media="screen">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/knowlinux.css')?>">
	<link rel="icon" href="<?php echo base_url('assets/img/linuxourse-logo-black.png')?>">
	<script type="text/javascript" src="<?php echo base_url('assets/js/linuxourse.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/vendor/modernizr.js')?>"></script>
	<script src="<?php echo base_url('assets/js/vendor/jquery.js')?>"></script>
	<script>
		$(document).ready(function(){
			$('#progressanimate').toggle('slow');
		});
		var rooturl = '<?php echo site_url();?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/angular/angular.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/angular/angular-sanitize.js');?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/linuxourse-master-controller.js')?>"></script>
	<?php
	//custom js setup
	if(!empty($script)){
	echo $script;//if add custom js scrript
}
?>
<title>
	<?php
		//title setup
	if(!empty($title)){
		echo $title.' :: LINUXOURSE';
	} else {
		echo 'Linux Ecourse :: LINUXOURSE';
	}
	?>
</title>
</head>
<body ng-app="appLinuxourse">
	<!-- custom modal -->
	<div id="customModal" class="reveal-modal small" data-reveal>
		<h2>Linux Ecourse</h2>
		<p class="lead">You're now students</p>
		<p>you can login using email and password which you have made!</p>
		<a class="close-reveal-modal">&#215;</a>
	</div>
	<!-- header -->
	<section  id="home_header">
		<div style="min-width:100%" class="row header">
			<div class="small-12 large-10 large-push-1 columns">
				<div class="small-3 columns">
					<a href="<?php echo site_url()?>"><div class="logo"></div></a>
				</div>
				<div class="small-9 columns">
					<ul style="float:right;padding-top:10px" class="inline-list">
						<li id="home"><a style="margin-top: 4px;" href="<?php echo site_url()?>">Home</a></li>
						<li id="discusion"><a style="margin-top: 4px;" href="<?php echo site_url('discussion/all')?>">Discussion</a></li>
						<li id="news"><a style="margin-top: 4px;" href="<?php echo site_url('news')?>">News</a></li>
						<li id="help"><a style="margin-top: 4px;" href="<?php echo site_url('news/read/TWc9PQ/Help')?>">Help</a></li>
						<li id="about"><a style="margin-top: 4px;" href="<?php echo site_url('news/read/TVE9PQ/About')?>">About</a></li>
						<?php if(!empty($this->session->userdata['student_login'])){ 
							if(!empty($this->session->userdata['student_login']['pp'])){
								$src = base_url('assets/img/avatar/'.$this->session->userdata['student_login']['pp']);
							} else {
								$src = base_url('assets/img/avatar.png');
							}
							?>
							<li><a href="#" data-dropdown="drop1" aria-controls="drop1" aria-expanded="false" class="secondary dropdown has-dropdown not-click"><img style="width:30px;border-radius:30px" src="<?php echo $src?>"/></a>
								<ul id="drop1" data-dropdown-content class="dropdownme f-dropdown" aria-hidden="true" tabindex="-1">
									<li><a href="<?php echo site_url('student/v/'.$this->session->userdata['student_login']['username']);?>"><strong><?php echo $this->session->userdata['student_login']['username'];?></strong><br/><small>My Profile Page</small></a></li>
									<li><a href="<?php echo site_url('m/test/new')?>"><span class="fi-grid"></span>  Dashboard</a></li>
									<li><a href="<?php echo site_url('m/mytest')?>"><span class="fi-grid"></span>  My Test</a></li>
									<li><a href="<?php echo site_url('m/test/new')?>"><span class="fi-grid"></span>  Join Test</a></li>
									<li><a href="<?php echo site_url('m/edit')?>"><span class="fi-widget"></span>  Update Profile</a></li>
									<li><a href="<?php echo site_url('m/logout')?>"><span class="fi-x-circle"></span> Logout</a></li>
								</ul>
							</li>
							<?php }	else { //show login link?>
							<li id="about"><a style="margin-top: 4px;" href="<?php echo site_url('p/login')?>">Login</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<section class="divideroftopmenu"></section>