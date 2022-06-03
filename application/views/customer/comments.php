<div class="container-fluid">
	
	<div class="clearfix mb-3">
		<div class="float-left">
			<p class="h2 mt-4 pull-left"><?php echo $page_heading; ?></p>
		</div>
		<div class="float-right mt-4">
			<a href="#add_your_comments" class="btn btn-primary text-white pull-rigt">Add</a>
			<a href="<?php echo base_url($back_page); ?>" class="btn btn-secondary pull-rigt">Back</a>
		</div>
	</div>
	
	<!-- COMMENTS LIST -->
	<div class="card">
		<div class="card-header bg-dark text-white">
			Comments
		</div>
		
		<div class="card-body">
			
			<?php
				if(isset($comments) && !empty($comments)){
					foreach ($comments as $eachComment){
						?>
						<blockquote class="blockquote mb-0">
							<p class="h6">
								<?php echo $eachComment['comments']; ?>
							</p>
							<?php
							if(isset($eachComment['files']) && !empty($eachComment['files'])){
								?>
								<p>
								<?php
								foreach ($eachComment['files'] as $eachFile){
									$extension = pathinfo($eachFile['file'], PATHINFO_EXTENSION);
									?>
									<a href="<?php echo base_url("uploads/comments/".$eachFile['file']); ?>" class="comment_files_list" target="_blank" >
										<img src="<?php echo base_url("img/files/".$extension.".svg"); ?>" alt="" />
									</a>
									<?php
								}
								?>
								</p>
								<?php
							} 
							?>
							<footer class="blockquote-footer font-size-12"><cite><b><?php echo $eachComment['first_name']." ".$eachComment['last_name']; ?></b></cite><?php echo " on ".date("d M, Y - h:i A",strtotime($eachComment['created'])) ?></footer>
						</blockquote>
						<hr />
						<?php
					}
				}else{
					$this->load->view('common/no_data_available');
				}
			?>
		</div>
	</div>
	
	<div class="clearfix">&nbsp;</div>
	
	<!-- ADD COMMENTS -->
	<div class="card" id="add_your_comments">
		<div class="card-header bg-primary text-white">
			Add Your Comments
		</div>
		
		<div class="card-body">
			<?php /* ?><form action="<?php echo base_url("customer/save_comment"); ?>" method="POST" enctype="multipart/form-data" name="frm_add_customer_comments" id="frm_add_customer_comments"><?php */ ?>
			<form enctype="multipart/form-data" name="frm_add_customer_comments" id="frm_add_customer_comments">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="comments">Comments</label>
						<textarea class="form-control" name="comments" id="comments" rows="3" required></textarea>
					</div>
					
					<div class="form-group col-md-12">
						<input type="file" class="form-control-file" name="comment_file" id="comment_file">
						<?php /* ?><div class="custom-file">
						  <input type="file" class="custom-file-input" id="customFile">
						  <label class="custom-file-label" for="customFile">Choose file</label>
						</div><?php */ ?>
					</div>
				</div>
				
				<button id="submit_btn" type="submit" class="btn btn-primary">Save</button>
				
				<input type="hidden" name="redirect_page" id="redirect_page" value="<?php echo $redirect_page; ?>" />
				<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id; ?>" />
				
			</form>
		</div>
	</div>
</div>

<div class="clearfix">&nbsp;</div>