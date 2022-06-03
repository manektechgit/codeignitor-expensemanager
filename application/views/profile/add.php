<div class="container-fluid">
	<p class="h2 mt-4"><?php echo $page_heading; ?></p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_add_profile" id="frm_add_profile">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="first_name">First Name</label>
				<input type="text" class="form-control" id="first_name" value="<?php echo $data['first_name']; ?>" required />
			</div>
		
			<div class="form-group col-md-6">
				<label for="last_name">Last Name</label>
				<input type="text" class="form-control" id="last_name" value="<?php echo $data['last_name']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" value="<?php echo $data['email']; ?>" aria-describedby="emailHelp" required <?php echo (isset($data['email']) && !empty($data['email']))?"readonly":""; ?> />
				<small id="emailHelp" class="form-text text-muted">Email will not be editable, as because it is used for login</small>
			</div>
			
			<div class="form-group col-md-6">
				<label for="phone">Phone</label>
				<input type="number" class="form-control" id="phone" value="<?php echo $data['phone']; ?>" />
			</div>
			
			<?php if(!isset($data['id']) || empty($data['id'])){ ?>
				<div class="form-group col-md-6">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="new_password" required />
				</div>
				
				<div class="form-group col-md-6">
					<label for="confirm_password">Verify Password</label>
					<input type="password" class="form-control" id="verify_password" required />
				</div>
				
				<div class="form-group col-md-6">
					<a href="javascript:void;" id="generate_password" class="btn btn-medium btn-warning" >Generate Strong Password</a>
				</div>
			<?php } ?>
		</div>		
			
		<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
		<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>" />
			
		<div class="clearfix">&nbsp;</div>
		<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo base_url($redirect_page); ?>" class="btn btn-light ml-3">Cancel</a>
			
	</form>
	
</div>