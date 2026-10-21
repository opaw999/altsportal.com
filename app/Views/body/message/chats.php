<style>
	.msg_history {
		/* background: #f8f9fa; */
		/* border: 1px solid #dee2e6; */
		border-radius: 8px;
		height: 230px;
		overflow-y: auto;
		padding: 10px;
		display: flex;
		flex-direction: column;
		/* box-shadow: 0 5px 15px rgba(0, 0, 0, .3); */
	}

	.msg {
		display: flex;
		flex-direction: column;
		align-self: flex-start;
		padding: 10px 14px;
		border-radius: 18px;
		margin-bottom: 10px;
		max-width: 75%;
		word-wrap: break-word;
		overflow-wrap: break-word;
	}

	.msg.user {
		background: #f59c1a;
		color: #fff;
		align-self: flex-end;
		border-bottom-right-radius: 0;
		box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
	}

	.msg.admin {
		background: #008181;
		color: #fff;
		align-self: flex-start;
		border-bottom-left-radius: 0;
		box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
	}

	.msg.bot {
		background: #008181;
		color: #fff;
		align-self: flex-start;
		border-bottom-left-radius: 0;
		box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
	}

	.chat_date_time {
		font-style: italic;
		font-size: 11px;
		opacity: 0.8;
		align-self: flex-end;
		margin-top: 4px;
	}

	.msg.user .chat_date_time {
		color: rgba(255, 255, 255, 0.8);
	}

	.msg.admin .chat_date_time {
		color: #dce9e9ff;
	}

	.msg.bot .chat_date_time {
		color: #dce9e9ff;
	}
</style>
<div id="page-header" class="section-container page-header-container bg-dark">
	<div class="page-header-cover">
		<img src="<?= base_url('../assets/main/img/cover/cover-15.jpg') ?>" alt="" />
	</div>
	<div class="container">
		<h1 class="page-header"><b>Chat</b> Message</h1>
	</div>
</div>
<div id="product" class="section-container pt-20px">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card" style="font-size: smaller;">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12 mb-2">
								<div class="msg_history"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<textarea class="write_msg form-control" rows="2" placeholder="Type your message and press enter to send..."></textarea>
							</div>
							<!-- <div class="col-md-3">
								<button type="submit" class="btn btn-sm btn-dark btn-theme">SEND MESSAGE</button>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>