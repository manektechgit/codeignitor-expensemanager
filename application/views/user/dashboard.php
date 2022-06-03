<div class="container-fluid">
	<p class="h2 mt-4">Dashboard</p>
	
	<div class="row">
		<div class="col-4">
			<div class="card text-center">
				<div class="card-header">
					<h2>
						<i class="fas fa-university"></i>
						Balance
					</h2>
				</div>
				<h3 class="text-primary">
					<?php echo RUPEE_SYMBOL;  ?>&nbsp;<?php echo $balance; ?>
				</h3>
			</div>
		</div>

		<div class="col-4">
			<div class="card text-center">
				<div class="card-header">
					<h2>
						<i class="fas fa-hand-holding-usd"></i>
						Total Income
					</h2>
				</div>
				<h3 class="text-success">
					<?php echo RUPEE_SYMBOL;  ?>&nbsp;<?php echo $total_income; ?>
				</h3>
			</div>
		</div>

		<div class="col-4">
			<div class="card text-center">
				<div class="card-header">
					<h2>
						<i class="fas fa-file-invoice"></i>
						Total Expense
					</h2>
				</div>
				<h3 class="text-danger">
					<?php echo RUPEE_SYMBOL;  ?>&nbsp;<?php echo $total_expense; ?>
				</h3>
			</div>
		</div>
	</div>
	
	<div class="clearfix">&nbsp;</div>

	<div class="row">
		<div class="col-12">
			<div class="card text-center">
				<div class="card-header text-danger">
					<h5>
					<i class="fas fa-chart-bar"></i>
					Expense Chart by Category
					</h5>
				</div>
				<canvas id="ExpenseChartCategory"></canvas>
			</div>
		</div>
	</div>

	<div class="clearfix">&nbsp;</div>
</div>