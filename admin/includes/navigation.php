
<!--Top nav bar -->
<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">

		<!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>       
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a href="/E-commerce/admin/index.php" style="color: white" class="navbar-brand">Collins Boutique</a>
                    </div>

			<!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-right collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
			  <!--menu items-->
				<li><a href="index.php" style="color: white">Dashboard</a></li>
			  <li><a href="brands.php" style="color: white">Brands</a></li>
			  <li><a href="categories.php" style="color: white">Categories</a></li>
			  <li><a href="products.php" style="color: white">Products</a></li>
			  <li><a href="archived.php" style="color: white">Archived</a></li>
			  <?php if (has_permission('admin')) : ?>
			  	<li>
			  		<a href="users.php" style="color: white">Users</a>
			  	</li>
			  <?php endif; ?>
			  <li class="dropdown">
			  	<a href="#" style="color: white" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first'];?>!
			  		<span class="caret"></span>

			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="change_password.php">Change password</a></li>
			  	<li><a href="logout.php">Logout</a></li>
			  </ul>
			  </li>

			  <!--<li class="dropdown hovers">
			  	<a href="#" class="dropdown-toggle" style="color: white" data-toggle="dropdown"><span class="caret"></span></a>
			  	<ul class="dropdown-menu" role="menu">
  				<li class="second"><a href="#"></a></li>

			  	</ul>
			  </li>-->


			  </ul>
			</div>
		</div>
	</nav>
