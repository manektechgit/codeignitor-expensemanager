<div class="container-fluid">
	
	<div class="clearfix mb-3">
		<div class="float-left">
			<p class="h2 mt-4 pull-left"><?php echo $page_heading; ?></p>
		</div>
		
		<?php if(is_admin()){ ?>
		<div class="float-right mt-4 ml-2">
			<a href="<?php echo base_url("customer/export") ?>" target="_blank" class="btn btn-success text-white"><i class="fas fa-file-excel"></i> Export</a>
		</div>
		<?php } ?>
		
		<div class="float-right mt-4">
			<a href="<?php echo base_url("customer/add"); ?>" class="btn btn-primary text-white">Add</a>
		</div>
	</div>
	
	<!-- search and filter -->
	<div class="clearfix mb-3">
		
		<div class="float-right col-md-2">
			<div class="form-group">
				<input id="clear_search_filters" name="clear_search_filters" type="button" class="btn btn-secondary" value="clear" />
			</div>
		</div>
		
		<?php if(is_admin()){ ?>
		<div class="float-right col-md-4">
			<div class="form-group">
				<select id="customer_filter_by_account_manager" name="customer_filter_by_account_manager" class="form-control custom-select">
					<option value="">select account manager</option>
					<?php foreach ($account_managers as $each_account_manager){ ?>
						<option value="<?php echo $each_account_manager['id']; ?>"><?php echo $each_account_manager['first_name']." ".$each_account_manager['last_name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php } ?>
		
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
	</div>
	
	<?php
		if(isset($data) && !empty($data)){
			?>
			<table class="table table-bordered table-hover h6 list_table">
				<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Customer Name</th>
						<th scope="col">Email</th>
						<th scope="col">Phone</th>
						<th scope="col">Account Manager</th>
						<th scope="col">Follow-up</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						global $arrStatus;
						foreach ($data as $index=>$row){
							$followup_date = (isset($row['followup_date']) && !empty($row['followup_date']) && $row['followup_date']!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($row['followup_date'])):"";
						?>
						<tr class="
							fk_accountmanager_id_<?php echo $row['fk_accountmanager_id']; ?>
							followup_date_<?php echo $followup_date; ?>
							
							"
						>
							<th scope="row"><?php echo $index+1; ?></th>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['phone']; ?></td>
							<td><?php echo $row['accountmanager_name']; ?></td>
							<td>
								<input type="text" class="form-control datepicker customer_list_followup_date_textbox" readonly="readonly" data-customer-id="<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="select/change date to update the followup date for the customer" value="<?php echo (isset($row['followup_date']) && !empty($row['followup_date']) && $row['followup_date']!="0000-00-00 00:00:00")?date("Y-m-d",strtotime($row['followup_date'])):""; ?>" placeholder="yyyy-mm-dd" style="width:110px;" />
							</td>
							<td>
								<span class="badge active-badge <?php echo ($row['status']==STATUS_ACTIVE)?"badge-success activeinactive_show":"activeinactive_hide"; ?>" table-name="customers" set-status="<?php echo STATUS_INACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['name']; ?>">
									<?php echo $arrStatus[STATUS_ACTIVE]; ?>
								</span>
								<span class="badge inactive-badge <?php echo ($row['status']==STATUS_INACTIVE)?"badge-danger activeinactive_show":"activeinactive_hide"; ?>" table-name="customers" set-status="<?php echo STATUS_ACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['name']; ?>">
									<?php echo $arrStatus[STATUS_INACTIVE]; ?>
								</span>
							</td>
							<td>
								<?php
									$edit_page_param = "id=".encrypt_simple($row['id']);
									$edit_page_url = base_url("customer/edit?".$edit_page_param);
									$comment_page_url = base_url("customer/comments?".$edit_page_param);
								?>
								<a href="<?php echo $edit_page_url; ?>" data-toggle="tooltip" data-placement="top" title="edit">
									<i class="fa fa-edit"></i>
								</a>
								
								<a class="ml-2 delete-button text-danger" href="javascript:;" data-toggle="tooltip" data-placement="top" title="delete" table-name="customers" set-status="<?php echo STATUS_DELETE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $row['name']; ?>">
									<i class="fa fa-trash"></i>
								</a>
								
								<a class="ml-2 text-warning" href="<?php echo $comment_page_url; ?>" data-toggle="tooltip" data-placement="top" title="comments">
									<i class="fas fa-comments"></i>
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