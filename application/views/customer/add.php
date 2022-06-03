<div class="container-fluid">
	<p class="h2 mt-4"><?php echo $page_heading; ?></p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_add_customer" id="frm_add_customer">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="customer_name">Customer Name</label>
				<input type="text" class="form-control" id="customer_name" value="<?php echo $data['name']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="contact_name">Contact Name</label>
				<input type="text" class="form-control" id="contact_name" value="<?php echo $data['contact_name']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="fk_industry_id">Industry</label>
				<select id="fk_industry_id" class="form-control custom-select" required>
					<option value="">select</option>
					<?php
					foreach ($industries as $eachIndustry){
						?>
						<option value="<?php echo $eachIndustry['id']; ?>" <?php echo (isset($data['fk_industry_id']) && !empty($data['fk_industry_id']) && $data['fk_industry_id']==$eachIndustry['id'])?'selected':''; ?> ><?php echo $eachIndustry['name']; ?></option>
						<?php
					} 
					?>
				</select>
			</div>
			
			<div class="form-group col-md-6">
				<label for="fk_accountmanager_id">Account Manager</label>
				<select id="fk_accountmanager_id" class="form-control custom-select" required>
					<option value="">select</option>
					<?php
					foreach ($account_managers as $eachAccountManager){
						?>
						<option value="<?php echo $eachAccountManager['id']; ?>" <?php echo (isset($data['fk_accountmanager_id']) && !empty($data['fk_accountmanager_id']) && $data['fk_accountmanager_id']==$eachAccountManager['id'])?'selected':''; ?> ><?php echo $eachAccountManager['name']; ?></option>
						<?php
					} 
					?>
				</select>
			</div>
		
			<div class="form-group col-md-6">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" value="<?php echo $data['email']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="phone">Phone</label>
				<input type="number" class="form-control" id="phone" value="<?php echo $data['phone']; ?>" />
			</div>
			
			<div class="form-group col-md-6">
				<label for="address_line_1">Address Line 1</label>
				<input type="text" class="form-control" id="address_line_1" value="<?php echo $data['address_line_1']; ?>" />
			</div>
			
			<div class="form-group col-md-6">
				<label for="address_line_2">Address Line 2</label>
				<input type="text" class="form-control" id="address_line_2" value="<?php echo $data['address_line_2']; ?>" />
			</div>
			
			<div class="form-group col-md-4">
				<label for="city">City</label>
				<input type="text" class="form-control" id="city" value="<?php echo $data['city']; ?>" />
			</div>
			<div class="form-group col-md-4">
				<label for="state">State</label>
				<input type="text" class="form-control" id="state" value="<?php echo $data['state']; ?>" />
			</div>
			<div class="form-group col-md-4">
				<label for="country">Country</label>
				<input type="text" class="form-control" id="country" value="<?php echo $data['country']; ?>" />
			</div>
			
			<div class="form-group col-md-2">
				<label for="followup_date">Follow-up date</label>
				<input type="text" class="form-control datepicker" id="followup_date" readonly="readonly" placeholder="yyyy-mm-dd" value="<?php echo (isset($data['followup_date']) && !empty($data['followup_date']) && $data['followup_date']!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($data['followup_date'])):''; ?>" />
			</div>
			
		</div>		
			
		<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
		<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>" />
			
		<div class="clearfix">&nbsp;</div>
		<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo base_url($redirect_page); ?>" class="btn btn-light ml-3">Cancel</a>
		
		<div class="clearfix">&nbsp;</div>
			
	</form>
	
</div>