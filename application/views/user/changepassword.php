<div class="container-fluid">
	<p class="h2 mt-4">Change Password</p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_change_my_password" id="frm_change_my_password" class="form-change-password">
		
		<input type="password" class="form-control" id="current_password" placeholder="Current Password" required autofocus />
		<div class="clearfix">&nbsp;</div>

		<input type="password" class="form-control" id="new_password" placeholder="New Password" required />
		<div class="clearfix">&nbsp;</div>
	
		<input type="password" class="form-control" id="verify_password" placeholder="Verify New Password" required />
		<div class="clearfix">&nbsp;</div>
	
		<button id="generate_password" class="btn btn-medium btn-warning" >Generate Strong Password</button>
	
		<div class="clearfix">&nbsp;</div>
	
		<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo base_url(); ?>" class="btn btn-light ml-3">Cancel</a>
			
	</form>
	
</div>