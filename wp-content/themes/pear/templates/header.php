<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<?php if ( ! is_home() ) : ?>
	<div class="burger-menu-overlay">
		<div></div>
		<div id="nav-home" class="nav-item clickable">
			<a href="/">Home</a>
		</div>
		<div id="nav-about" class="nav-item clickable">
			<a href="/#nav-about-trigger">About</a>
		</div>
		<div id="nav-blog" class="nav-item clickable">
			<a href="/#nav-blog-trigger">Blog</a>
		</div>
		<div id="nav-support" class="nav-item clickable">
			<a href="/#nav-support-trigger">Support</a>
		</div>
		<div></div>
	</div>
	<div class="burger-menu">
		<div class="burger-line burger-line-top"></div>
		<div class="burger-line burger-line-middle"></div>
		<div class="burger-line burger-line-bottom"></div>
	</div>
	<div id="top-nav" class="top-nav white static">
		<a href="<?= esc_url( home_url( '/' ) ); ?>"><span class="pear-logo"></span></a>
		<div class="nav-container">
			<div id="nav-home" class="nav-item clickable">
				<a href="/">Home</a>
			</div>
			<div id="nav-about" class="nav-item clickable">
				<a href="/#nav-about-trigger">About</a>
			</div>
			<div id="nav-blog" class="nav-item clickable">
				<a href="/#nav-blog-trigger">Blog</a>
			</div>
			<div id="nav-support" class="nav-item clickable">
				<a href="/#nav-support-trigger">Support</a>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="burger-menu-overlay">
		<div></div>
		<div id="nav-home" class="nav-item clickable">
			Home
		</div>
		<div id="nav-about" class="nav-item clickable">
			About
		</div>
		<div id="nav-blog" class="nav-item clickable">
			Blog
		</div>
		<div id="nav-support" class="nav-item clickable">
			Support
		</div>
		<div></div>
	</div>
	<div class="burger-menu">
		<div class="burger-line burger-line-top"></div>
		<div class="burger-line burger-line-middle"></div>
		<div class="burger-line burger-line-bottom"></div>
	</div>
<?php endif; ?>