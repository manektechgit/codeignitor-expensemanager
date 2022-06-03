<div class="container-fluid">
	
	<div class="clearfix mb-3">
		<div class="float-left">
			<p class="h2 mt-4 pull-left"><?php echo $page_heading; ?></p>
		</div>
		<div class="float-right mt-4">
			<a href="<?php echo base_url("profile/add"); ?>" class="btn btn-primary text-white pull-rigt">Add</a>
		</div>
	</div>
	
	
	<?php
		if(isset($data) && !empty($data)){
			?>
			<table class="table table-bordered table-hover h6">
				<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col">Phone</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
						global $arrStatus,$arrRoleNames;
						foreach ($data as $index=>$row){ ?>
						<tr>
							<th scope="row"><?php echo $index+1; ?></th>
							<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['phone']; ?></td>
							<td>
								<span class="badge active-badge <?php echo ($row['status']==STATUS_ACTIVE)?"badge-success activeinactive_show":"activeinactive_hide"; ?>" table-name="users" set-status="<?php echo STATUS_INACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $arrRoleNames[$fk_role_id].": ".$row['first_name']." ".$row['last_name']; ?>">
									<?php echo $arrStatus[STATUS_ACTIVE]; ?>
								</span>
								<span class="badge inactive-badge <?php echo ($row['status']==STATUS_INACTIVE)?"badge-danger activeinactive_show":"activeinactive_hide"; ?>" table-name="users" set-status="<?php echo STATUS_ACTIVE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $arrRoleNames[$fk_role_id].": ".$row['first_name']." ".$row['last_name']; ?>">
									<?php echo $arrStatus[STATUS_INACTIVE]; ?>
								</span>
							</td>
							<td>
								<?php
									$edit_page_param = "id=".encrypt_simple($row['id']);
									$edit_page_url = base_url("profile/edit?".$edit_page_param);

									$change_password_page_url =  base_url("profile/changepassword?".$edit_page_param);
								?>
								<a href="<?php echo $edit_page_url; ?>" data-toggle="tooltip" data-placement="top" title="edit">
									<i class="fa fa-edit"></i>
								</a>
								
								<a href="<?php echo $change_password_page_url; ?>" class="ml-2 text-warning" data-toggle="tooltip" data-placement="top" title="change password">
									<i class="fa fa-key"></i>
								</a>
								
								<a class="ml-2 delete-button text-danger" href="javascript:;" data-toggle="tooltip" data-placement="top" title="delete" table-name="users" set-status="<?php echo STATUS_DELETE; ?>" record-id="<?php echo $row['id']; ?>" ask-for="<?php echo $arrRoleNames[$fk_role_id].": ".$row['first_name']." ".$row['last_name']; ?>">
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