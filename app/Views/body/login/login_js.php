	<script type="text/javascript">
		
		$(document).ready(function() {

			$("form#login-form").submit(function(e) {

				e.preventDefault();
				var formData = new FormData(this);

				$("button.sign_in").prop("disabled", true);
				$("span.loading_").html('<i class="fas fa-spinner fa-pulse"></i> Signing in...');
				$.ajax({
			        url: "<?php echo site_url('authentication'); ?>",
			        type: 'POST',
			        data: formData,
			        success: function (data) {

			            response = data.trim();
			            if(response == "success"){

			                location.reload();

			            } else {

			            	$("button.sign_in").prop("disabled", false);
			            	$("span.loading_").html('Sign me in');
			                $.gritter.add({
			                	image: '<?php echo base_url('assets/plugins/gritter/images/warning.png'); ?>',
            					title: 'Opsss!',
            					text: 'Invalid Account! Sorry you cannot login.'
            				});

            				return false;
			            }
			        },
			        cache: false,
			        contentType: false,
			        processData: false
			    });
			});

		});
	</script>
</body>

</html>