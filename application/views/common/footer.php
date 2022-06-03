		</div>
		<!-- /#page-content-wrapper -->

	</div>
	<!-- /#wrapper -->
	
	<!-- loading div -->
	<div class="ajax-loading">
		<img src="<?php echo base_url("img/infinity.gif"); ?>" />
	</div>

	<!-- core js files -->
	<script src="<?php echo base_url("js/jquery.min.js"); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url("js/bootstrap.bundle.min.js"); ?>" type="text/javascript"></script>
	
	<!-- font awesome -->
	<script src="<?php echo base_url("plugins/fontawesome/js/all.min.js"); ?>" type="text/javascript"></script>
	
	<!-- sweet alert 2 -->
	<script src="<?php echo base_url("js/sweetalert2.min.js"); ?>" type="text/javascript"></script>
	
	<!-- datepicker -->
	<script src="<?php echo base_url("js/bootstrap-datepicker.js"); ?>"></script>
	
	<!-- Chartjs Plugin -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
	
	<!-- custom js -->
	<script src="<?php echo base_url("js/custom.js"); ?>" type="text/javascript"></script>

	<script>
		// expense chart by category
		if($("#ExpenseChartCategory").length>0){
			const ctx = document.getElementById('ExpenseChartCategory').getContext('2d');
			const myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: [<?php echo $expense_by_category_chart['labels'] ?>],
					datasets: [{
						label: '',
						data: [<?php echo $expense_by_category_chart['data'] ?>],
						backgroundColor: [<?php echo $expense_by_category_chart['backgroundColor'] ?>],
						borderColor: [<?php echo $expense_by_category_chart['borderColor'] ?>],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}
	</script>

</body>

</html>