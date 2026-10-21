<script type="text/javascript">
	$(document).ready(function() {
		var unread = '<?= $unread ?>';
		var userId = '<?= session('supplierId') ?>';

		msg_history();

		Pusher.logToConsole = false; // Set to true only for debugging

		// Initialize Pusher with your real app key and cluster
		const pusher = new Pusher('530a8cc03f6febd40e05', {
			cluster: 'ap1',
			forceTLS: true
		});

		// Subscribe to the same channel the admin is broadcasting to
		const channel = pusher.subscribe('promo-chat');

		// Listen for new messages from admin
		channel.bind('new-message', function(data) {
			console.log(data)
			msg_history();
			// Replace 'userId' with the actual variable that identifies the current user
			if (data.sender && data.cc === userId && data.reciever === 'supplier') {
				appendAdminMessage(data.sender, data.message, data.time);
			}

		});

		$('textarea.write_msg').on('keyup', function() {
			let val = $(this).val().trim();
			if (val.length == 1) {
				$('div.msg_history i').remove();
				read_all_messages();
			}
		});


		$('textarea.write_msg').on('focus', function() {
			if (unread != 0) {
				$('div.msg_history i').remove();
				read_all_messages()
			}
		});

		$('button.send_btn').click(function() {
			sendMessage();
		});

		$('textarea.write_msg').keypress(function(e) {
			if (e.which == 13 && !e.shiftKey) {
				e.preventDefault();
				sendMessage();
			}
		});

	});

	function scrollToBottom() {
		const chat = $('div.msg_history');
		chat.scrollTop(chat[0].scrollHeight);
	}

	function msg_history() {
		$.ajax({
			method: 'post',
			url: "<?php echo site_url('view_message'); ?>",
			success: function(data) {
				$("div.msg_history").html(data);
				scrollToBottom()
			}
		});
	}

	function sendMessage() {
		read_all_messages()
		let message = $('textarea.write_msg').val().trim();
		if (message !== '') {

			$.ajax({
				method: 'post',
				url: "<?php echo site_url('send_message'); ?>",
				data: {
					message
				},
				success: function(data) {
					$('textarea.write_msg').val('');
					appendUserMessage(message);

				}
			});
		}
	}

	// Append user's message to chat
	function appendUserMessage(message) {
		const now = new Date().toLocaleTimeString([], {
			hour: '2-digit',
			minute: '2-digit'
		});
		const msgHtml = `
			<div class="msg user">
				<span>${$('<div>').text(message).html()}</span>
				<span class="chat_date_time">${now}</span>
			</div>`;
		$("div.msg_history").append(msgHtml);
		scrollToBottom();
	}

	// Append admin message (from Pusher)
	function appendAdminMessage(sender, message, time) {
		const msgHtml = `
			<div class="msg admin">
				<span>${$('<div>').text(message).html()}</span>
				<span class="chat_date_time">${sender} ${time}</span>
			</div>`;
		$("div.msg_history").append(msgHtml);
		scrollToBottom();
	}

	function read_all_messages() {
		$.ajax({
			method: 'get',
			url: "<?php echo site_url('read_all_messages'); ?>",
			success: function() {
				$('span.unread').html('0')
			}
		});
	}
</script>