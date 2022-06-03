<div class="container-fluid">
	<p class="h2 mt-4"><?php echo $page_heading; ?></p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_change_profile_password" id="frm_change_profile_password" class="form-change-password">
		
		<input type="password" class="form-control" id="new_password" placeholder="New Password" required />
		<div class="clearfix">&nbsp;</div>
	
		<input type="password" class="form-control" id="verify_password" placeholder="Verify New Password" required />
		<div class="clearfix">&nbsp;</div>
	
		<button id="generate_password" class="btn btn-medium btn-warning" >Generate Strong Password</button>
	
		<div class="clearfix">&nbsp;</div>
	
		<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo base_url($redirect_page); ?>" class="btn btn-light ml-3">Cancel</a>
		
		<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
		<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>" />
			
	</form>
	
</div>