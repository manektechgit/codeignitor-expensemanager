<div class="container-fluid">
	
	<div class="clearfix mb-3">
		<div class="float-left">
			<p class="h2 mt-4 pull-left text-success"><?php echo $page_heading; ?> &nbsp; ( <?php echo RUPEE_SYMBOL." ".$total_income; ?> )</p>
		</div>
		
		<div class="float-right mt-4">
			<a href="<?php echo base_url("income/add"); ?>" class="btn btn-primary text-white">Add</a>
		</div>
	</div>
	
	<!-- search and filter -->
	<div class="clearfix mb-3">
		
		<div class="float-right col-md-2">
			<div class="form-group">
				<input id="clear_search_filters" name="clear_search_filters" type="button" class="btn btn-secondary" value="clear" />
			</div>
		</div>

		<div class="float-right col-md-2">
			<div class="form-group">
				<input name="search_by_date" id="search_by_date" type="text" class="form-control datepicker" readonly="readonly" data-toggle="tooltip" data-placement="top" title="select/change date to search by date" placeholder="yyyy-mm-dd" style="width:110px;" />
			</div>
		</div>

		<div class="float-right col-md-4">
			<div class="form-group">
				<input type="text" class="form-control" name="search_in_table" id="search_in_table" placeholder="type to search..." />
			</div>
		</div>

		<div class="float-right col-md-4">
			<div class="form-group">
				<select id="filter_by_category" name="filter_by_category" class="form-control custom-select">
					<option value="">All Categories</option>
					<?php foreach ($categories as $each_category){ ?>
						<option value="<?php echo $each_category['id']; ?>"><?php echo $each_category['name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	
	<?php
		if(isset($data) && !empty($data)){
			?>
			<table class="table table-bordered table-hover h6 list_table">
				<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Title</th>
						<th scope="col">Category</th>
						<th scope="col">Income Date</th>
						<th scope="col">Amount (<?php echo RUPEE_SYMBOL; ?>)</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						global $arrStatus;
						foreach ($data as $index=>$row){
						?>
						<tr class="
							fk_category_id_<?php echo $row['fk_category_id']; ?>
							income_date_<?php echo $row['income_date']; ?>
						">
							<th scope="row"><?php echo $index+1; ?></th>
							<td><?php echo $row['title']; ?></td>
							<td><?php echo $row['category']; ?></td>
							<td><?php echo $row['income_date']; ?></td>
							<td><?php echo $row['amount']; ?></td>
							<td>
								<span class="badge active-badge <?php echo ($row['status']==STATUS_ACTIVE)?"badge-success activeinactive_show":"activeinactive_hide"; ?>" table-name="income" set-status="<?php echo STATUS_INACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['title']; ?>">
									<?php echo $arrStatus[STATUS_ACTIVE]; ?>
								</span>
								<span class="badge inactive-badge <?php echo ($row['status']==STATUS_INACTIVE)?"badge-danger activeinactive_show":"activeinactive_hide"; ?>" table-name="income" set-status="<?php echo STATUS_ACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['title']; ?>">
									<?php echo $arrStatus[STATUS_INACTIVE]; ?>
								</span>
							</td>
							<td>
								<?php
									$edit_page_param = "id=".encrypt_simple($row['id']);
									$edit_page_url = base_url("income/edit?".$edit_page_param);
								?>
								<a href="<?php echo $edit_page_url; ?>" data-toggle="tooltip" data-placement="top" title="edit">
									<i class="fa fa-edit"></i>
								</a>
								
								<a class="ml-2 delete-button text-danger" href="javascript:;" data-toggle="tooltip" data-placement="top" title="delete" table-name="income" set-status="<?php echo STATUS_DELETE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['title']; ?>">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
		}else{
			$this->load->view('common/no_data_available', $data);
		}
	?>
	
</div>