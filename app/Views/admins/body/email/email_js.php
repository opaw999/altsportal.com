<script type="text/javascript">
    $(document).ready(function() {

        email_list();

        Pusher.logToConsole = false;

        const pusher = new Pusher('530a8cc03f6febd40e05', {
            cluster: 'ap1',
            forceTLS: true
        });

        const channel = pusher.subscribe('promo-chat');

        channel.bind('new-message', function(data) {
            const activeId = $('input[name="cc"]').val();
            const senderId = data.cc;

            $.ajax({
                method: 'get',
                url: "<?php echo site_url('admins/supplier_chat_messages'); ?>",
                success: function(result) {
                    let response = JSON.parse(result);
                    $(".inbox_chat").html(response);
                }
            });

            var userId = $('.chat_list.active').attr('id');
            if (userId) {
                setTimeout(function() {
                    admin_read_message(userId)
                }, 5000);
            }

            if (senderId === activeId && data.reciever === 'admin') {
                appendSupplierMessage(data.sender, data.message, data.time, data.photo);
            }
        });

        $("#chatSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase().trim();

            $(".messenger-chat-item").filter(function() {
                // text inside chat title + desc
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });

            $('.messenger-chat-body [data-scrollbar="true"]').scrollTop(0);
        });

        $('textarea.write_msg').one('focus', function(event) {
            var id = $('input[name="cc"]').val();
            admin_read_message(id);
        });

        $('#msg_history').one('scroll', function() {
            var id = $('input[name="cc"]').val();
            admin_read_message(id);
        });

        $('textarea.write_msg').on('keydown', function(e) {

            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                send_message()
            }
        });

        $("a.msg_send_btn").click(function() {
            send_message()

        });
    });

    function send_message() {
        var feedback = $('textarea.write_msg').val();
        var cc = $('input[name = "cc"]').val();
        if (feedback.trim()) {
            $.ajax({
                method: 'post',
                url: "<?php echo site_url('admins/send_feedback'); ?>",
                data: {
                    feedback: feedback,
                    cc: cc
                },
                success: function(result) {

                    $('textarea.write_msg').val('');
                    appendAdminMessage(feedback);
                }
            });
        }
    }

    function appendAdminMessage(message) {
        const now = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
        const msgHtml = `
		<div class="widget-chat-item with-media end">
			<div class="widget-chat-media"><img alt="" src="<?= base_url('assets/img/user/admin.png') ?>"></div>
			<div class="widget-chat-info">
				<div class="widget-chat-info-container">
					<div class="widget-chat-message">${$('<div>').text(message).html()}</div>
					<div class="widget-chat-time">${now}</div>
				</div>
			</div>
		</div>`;
        $('#msg_history').append(msgHtml);
        scrollToLatestMessage();
    }

    function appendSupplierMessage(sender, message, time, photo) {
        const msgHtml = `
		<div class="widget-chat-item with-media start">
			<div class="widget-chat-media"><img alt="" src="<?= base_url() ?>${photo}"></div>
			<div class="widget-chat-info">
				<div class="widget-chat-info-container">
					<div class="widget-chat-name text-blue">${sender}</div>
					<div class="widget-chat-message">${$('<div>').text(message).html()}</div>
					<div class="widget-chat-time">${time}</div>
				</div>
			</div>
		</div>`;
        $('#msg_history').append(msgHtml);
        scrollToLatestMessage();
    }

    function chat_list(id, read) {
        $('textarea.write_msg').val('');
        $('.messenger-chat-item').removeClass('active');
        $('div#' + id).addClass('active');
        updateActiveChat(id);
        view_supplier_messages(id);
        if (!read) {
            email_list()
            admin_read_message(id)
        }
    }

    function scrollToLatestMessage() {
        $('#msg_history').scrollTop($('#msg_history')[0].scrollHeight);
    }

    function email_list() {
        $.ajax({
            method: 'get',
            url: "<?php echo site_url('admins/supplier_chat_messages'); ?>",
            success: function(result) {
                let response = JSON.parse(result);
                $(".inbox_chat").html(response);
                var userId = $('.chat_list.active').attr('id');
                view_supplier_messages(userId);
            }
        });
    }

    function view_supplier_messages(id) {
        var userId = $('.chat_list.active').attr('id');
        if (id == userId) {
            admin_read_message(id)
        }

        if (id != null) {

            $.ajax({
                method: 'post',
                url: "<?php echo site_url('admins/view_supplier_messages'); ?>",
                data: {
                    id: id
                },
                success: function(result) {
                    var data = JSON.parse(result);
                    $('div.senderHeader').html(data.sender)
                    $('div#msg_history').html(data.msg)
                    $('div.unreadMail').html(data.unreadMail)
                    $('span.unreadMail').html(data.unreadMail)
                    scrollToLatestMessage()
                }
            });
        }

    }

    function admin_read_message(id) {
        $.ajax({
            method: 'post',
            url: "<?php echo site_url('admins/admin_read_message'); ?>",
            data: {
                id
            },
            success: function(result) {
                var data = JSON.parse(result);
                $('div.unreadMail').html(data.unreadMail)
                $('span.unreadMail').html(data.unreadMail)
                $('div.chat_list.active#' + id + ' i').remove();
            }
        });
    }

    function updateActiveChat(id) {

        $.ajax({
            method: 'post',
            url: "<?php echo site_url('admins/update_active_chat'); ?>",
            data: {
                id: id
            }
        });
    }

    function newMessageForm() {
        $('div#newMessageForm').modal({
            backdrop: 'static',
            keyboard: false,
        });
        $("div#newMessageForm").modal("show");

        $.ajax({
            method: 'get',
            url: "<?php echo site_url('admins/newMessageForm'); ?>",
            success: function(data) {
                $('div.newMessageForm').html(data)
            }
        });
    }
</script>