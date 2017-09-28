<!-- start wrapper-menu -->
<div id="wrapper-menu">
	<div id="menu">
		<ul>
			<li><a href="<?php echo $url_index; ?>" title="Home">Home</a></li>
			<li><a href="<?php echo $url_what_is; ?>" title="What is?">What is?</a></li>
			<li><a href="<?php echo $url_our_team; ?>" title="Our team">Our team</a></li>
            <?php if(isset($_SESSION['id_user'])) { ?>
			<li><a href="#" title="My account">My account</a>
            <ul>
				<li><a href="<?php echo $url_emissions_index; ?>" title="Emissions">Emissions</a></li>
				<li><a href="<?php echo $url_emissions_food; ?>" title="Food emissions">Food emissions</a></li>
				<li><a href="<?php echo $url_emissions_home_water; ?>" title="Home emissions water">Home emissions water</a></li>
				<li><a href="<?php echo $url_emissions_home_gas; ?>" title="Home emissions gas">Home emissions gas</a></li>
				<li><a href="<?php echo $url_emissions_home_fuel; ?>" title="Home emissions fuel">Home emissions fuel</a></li>
				<li><a href="<?php echo $url_emissions_home_electric; ?>" title="Home emissions electric">Home emissions electric</a></li>
				<li><a href="<?php echo $url_emissions_travel_motorcycle; ?>" title="Travel emissions motorcycle">Travel emissions motorcycle</a></li>
				<li><a href="<?php echo $url_account_details; ?>" title="Account details">Account details</a></li>
				<li><a href="<?php echo $url_exit; ?>" title="Exit">Exit</a></li>
			</ul>
            </li>
			<?php } else { ?>
			<li><a href="<?php echo $url_sign_up; ?>" title="Sign up">Sign up</a> / <a href="<?php echo $url_login; ?>" title="Login">Login</a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<!-- ends wrapper-menu -->