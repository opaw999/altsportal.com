<style>
    textarea.write_msg {
        resize: none;
        min-height: 0 !important;
    }
</style>
<div id="content" class="app-content p-0">
    <div class="messenger" id="messenger">
        <div class="messenger-chat">
            <div class="messenger-chat-header d-flex">
                <div class="flex-1 position-relative">
                    <input type="text" id="chatSearch" class="form-control border-0 bg-light ps-30px" placeholder="Search">
                    <i class="fa fa-search position-absolute start-0 top-0 h-100 ps-2 ms-3px d-flex align-items-center justify-content-center"></i>
                </div>
                <div class="ps-2">
                    <!-- <a href="javascript:;" class="btn border-0 bg-light shadow-none" onclick="newMessageForm()">
                        <i class="fa fa-plus"></i>
                    </a> -->
                </div>
            </div>
            <div class="messenger-chat-body">
                <div data-scrollbar="true" data-height="100%">
                    <div class="messenger-chat-list inbox_chat"></div>
                </div>
            </div>
        </div>
        <div class="messenger-content">
            <div class="widget-chat">
                <div class="widget-chat-header">
                    <div class="d-block d-lg-none">
                        <!-- <button type="button" class="btn border-0 shadow-none" data-toggle-class="messenger-chat-content-mobile-toggled" data-target="#messenger">
                            <i class="fa fa-chevron-left fa-lg"></i>
                        </button> -->
                    </div>
                    <div class="widget-chat-header-content">
                        <div class="fs-5 fw-bold senderHeader"></div>
                    </div>
                    <div class="">
                        <button type="button" class="btn border-0 shadow-none" data-bs-toggle="dropdown" disabled>
                            <i class="fa fa-ellipsis fa-lg"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#">Action </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Another action </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Something else here </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="widget-chat-body" data-scrollbar="true" data-height="100%" id="msg_history"></div>
                <div class="widget-chat-input">
                    <div class="widget-chat-toolbar">
                        <textarea name="write_msg" class="form-control write_msg" placeholder="Write your message here..."></textarea>
                        <a href="javascript:;" id="" class="widget-chat-toolbar-link ms-auto msg_send_btn" onclick="">
                            <i class="bi bi-send fs-26px"></i>
                        </a>
                    </div>
                    <!-- <textarea name="write_msg" class="form-control write_msg" placeholder="Write your message here..."></textarea> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- #modal-dialog -->
<!-- <div class="modal fade" id="newMessageForm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send New Message</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body newMessageForm"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success">Send</a>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div> -->