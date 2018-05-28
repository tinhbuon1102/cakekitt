<div class="navbar navbar-brand-cake navbar-fixed-top">
	<div class="nav-container">
		<div class="container">
			<div class="sub_head">
				<div class="Header-supHeaderBurger hidden-pc">
					<div class="Header-button--circled">
						<button id="toggleMenu" class="linericon-menu" type="button"></button>
					</div>
				</div>
				<div class="Header-supHeaderLogo hidden-pc">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logobrand_img"><img src="http://kitt-sweets.jp/wp-content/uploads/2017/02/logo_round.png" alt="Kitt"></a>
				</div>
				<div class="sub_left hidden-xs hidden-sm">
					<span class="tel_info"><i class="fa fa-phone"></i> 03-6434-9919<span class="tel_hour">15:00~23:00(不定休)</span></span>
					<!--<ul class="cake-social-icon">
						<?php //do_action('cake_social_icon');?>
					</ul>-->
				</div>
				<div class="sub_right menu-sub-menu-right-container">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'submenuright',
							'menu_id' => 'submenu_right',
							'sort_column' => 'menu_order',
							'container' => false,
							'menu_class' => 'nav navbar-nav navbar-abs navbar-abs-right no-popup-menu'
						)
					); ?>
				</div>
			</div><!--/sub_head -->
			<nav id="toggleTarget" class="Header-navContainer hidden-pc">
				<div class="Header-navHeader">
				<ul class="mb-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mainmenuleft',
							'menu_id' => 'menu_left',
							'sort_column' => 'menu_order',
							'items_wrap' => '%3$s',
							'container' => false
						)
					); ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mainmenuright',
							'menu_id' => 'menu_right',
							'sort_column' => 'menu_order',
							'items_wrap' => '%3$s',
							'container' => false
						)
					); ?>
				</ul>
				</div>
			</nav>
			<nav class="nav_main" role="navigation">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mainmenuleft',
							'container' => false,
							'menu_id' => 'leftMenu',
							'sort_column' => 'menu_order',
							'menu_class' => 'mainmenu nav navbar-nav hidden-xs hidden-sm'
						)
					); ?>
				<div class="logo_brand menu-item hidden-sm"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logobrand_img"><img src="http://kitt-sweets.jp/wp-content/uploads/2017/02/logo_round.png" alt="Kitt"></a></div>
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'mainmenuright',
							'container' => false,
							'menu_id' => 'rightMenu',
							'sort_column' => 'menu_order',
							'menu_class' => 'mainmenu nav navbar-nav hidden-xs hidden-sm'
						)
					); ?>
			</nav>
			
		</div><!--/container-->
		<div class="float_contact hidden-pc">
			<a href="tel:0364349919" class="round_icon_link"><i class="fa fa-phone"></i> </a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/" class="round_icon_link"><i class="fa fa-envelope"></i> </a>
		</div>
	</div>
</div>