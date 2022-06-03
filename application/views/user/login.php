<form id="frm_login" class="form-signin">
	
	<h1 class=" mb-5 font-weight-normal"><?php echo SITE_NAME; ?></h1>
	
	<?php /* ?><h1 class=" mb-5 font-weight-normal"><?php echo SITE_NAME; ?></h1><?php */ ?>
      
	<?php /* ?><h5 class="h5 mb-3 font-weight-normal">Please sign in</h5><?php */ ?>

	<label for="inputEmail" class="sr-only">Email address</label>
	<input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
      
	<label for="inputPassword" class="sr-only">Password</label>
	<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    
	<p class="mb-3">&nbsp;</p>
      
	<button class="btn btn-lg btn-primary btn-block" id="submit_btn" type="submit">Sign in</button>
	<p class="mt-5 mb-3 text-muted">Copyrights &copy; <?php echo date("Y"); ?>&nbsp;<?php echo DEVELOPED_BY; ?></p>

</form>
