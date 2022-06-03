<div class="container-fluid">
	<p class="h2 mt-4">My Profile</p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="my_profile" id="my_profile">
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
				<input type="email" class="form-control" id="email" value="<?php echo $data['email']; ?>" readonly="readonly" aria-describedby="emailHelp" />
				<small id="emailHelp" class="form-text text-muted">Email is not editable.</small>
			</div>
			
			<div class="form-group col-md-6">
				<label for="phone">Phone</label>
				<input type="number" class="form-control" id="phone" value="<?php echo $data['phone']; ?>" />
			</div>
			
			<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
			<a href="<?php echo base_url(); ?>" class="btn btn-light ml-3">Cancel</a>
		</div>		
			
	</form>
	
</div>