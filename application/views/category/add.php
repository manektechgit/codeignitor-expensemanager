<div class="container-fluid">
	<p class="h2 mt-4 text-info"><?php echo $page_heading; ?></p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_add_category" id="frm_add_category">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="name">Category Name</label>
				<input type="text" class="form-control" id="name" value="<?php echo $data['name']; ?>" required />
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