<div class="container-fluid">
	<p class="h2 mt-4 text-danger"><?php echo $page_heading; ?></p>
	
	<div class="clearfix">&nbsp</div>
	
	<form name="frm_add_expense" id="frm_add_expense">
		<div class="form-row">

			<div class="form-group col-md-6">
				<label for="fk_category_id">Select Category</label>
				<select id="fk_category_id" class="form-control custom-select" required>
					<option value="">select</option>
					<?php
					foreach ($categories as $eachCategory){
						?>
						<option value="<?php echo $eachCategory['id']; ?>" <?php echo (isset($data['fk_category_id']) && !empty($data['fk_category_id']) && $data['fk_category_id']==$eachCategory['id'])?'selected':''; ?> ><?php echo $eachCategory['name']; ?></option>
						<?php
					} 
					?>
				</select>
			</div>

			<div class="form-group col-md-6">
				<label for="title">Title</label>
				<input type="text" class="form-control" id="title" value="<?php echo $data['title']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="amount">Amount (<?php echo RUPEE_SYMBOL; ?>)</label>
				<input type="number" class="form-control" id="amount" value="<?php echo $data['amount']; ?>" required />
			</div>
			
			<div class="form-group col-md-6">
				<label for="expense_date">Expense Date</label>
				<input type="text" class="form-control datepicker" id="expense_date" readonly="readonly" placeholder="yyyy-mm-dd" value="<?php echo (isset($data['expense_date']) && !empty($data['expense_date']) && $data['expense_date']!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($data['expense_date'])):date("Y-m-d"); ?>" />
			</div>

			<div class="form-group col-md-12">
				<label for="income_date">Description</label>
				<textarea name="description" id="description" class="form-control"><?php echo $data['description']; ?></textarea>
			</div>
			
		</div>		
			
		<input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>" />
		<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>" />
			
		<div class="clearfix">&nbsp;</div>
		<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo base_url($redirect_page); ?>" class="btn btn-light ml-3">Cancel</a>
			
	</form>
	
</div>