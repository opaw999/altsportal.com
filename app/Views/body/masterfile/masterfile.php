<div id="page-header" class="section-container page-header-container bg-black">
    <div class="page-header-cover">
        <img src="<?= base_url('../assets/main/img/cover/cover-15.jpg') ?>" alt="" />
    </div>
    <div class="container">
        <h1 class="page-header"><b>Masterfile</b></h1>
    </div>
</div>

<div class="container">
    <div class="checkout mt-10px mb-10px">
        <div class="checkout-header">
            <div class="row">
                <?php if (isset($alturas)) { ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="step active step_tagbilaran">
                            <a href="javascript:;" class="server_code" onclick="masterfile_list('tagbilaran')">
                                <div class="number">1</div>
                                <div class="info">
                                    <div class="title">Tagbilaran, Bohol</div>
                                    <div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta & Alturas Panglao</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_tubigon">
                            <a href="javascript:;" class="server_code" onclick="masterfile_list('tubigon')">
                                <div class="number">2</div>
                                <div class="info">
                                    <div class="title">Tubigon, Bohol</div>
                                    <div class="desc">Alturas Tubigon</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_talibon">
                            <a href="javascript:;" class="server_code" onclick="masterfile_list('talibon')">
                                <div class="number">3</div>
                                <div class="info">
                                    <div class="title">Talibon, Bohol</div>
                                    <div class="desc">Alturas Talibon</div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="step active step_tagbilaran" style="color: #6c757d;">
                            <div class="number">1</div>
                            <div class="info">
                                <div class="title">Tagbilaran, Bohol</div>
                                <div class="desc">Alturas Mall, ICM , Plaza Marcela, Alta Citta & Alturas Panglao</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_tubigon" style="color: #6c757d;">
                            <div class="number">2</div>
                            <div class="info">
                                <div class="title">Tubigon, Bohol</div>
                                <div class="desc">Alturas Tubigon</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_talibon" style="color: #6c757d;">
                            <div class="number">3</div>
                            <div class="info">
                                <div class="title">Talibon, Bohol</div>
                                <div class="desc">Alturas Talibon</div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if (isset($colonnade)) { ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_cebu">
                            <a href="javascript:;" class="server_code" onclick="masterfile_list('cebu')">
                                <div class="number">4</div>
                                <div class="info">
                                    <div class="title">Cebu</div>
                                    <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-md-3 col-sm-3">
                        <div class="step step_cebu" style="color: #6c757d;">
                            <div class="number">4</div>
                            <div class="info">
                                <div class="title">Cebu</div>
                                <div class="desc">Colonnade Colon , Mandaue & Mohon</div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="checkout-body" style="padding: 15px 5px;">
            <div class="table-responsive">
                <table id="dt_masterfile" class="table table-sm table-bordered table-hover" width="100%" style="font-size: smaller;">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Company</th>
                            <th>Store(s)</th>
                            <th>Position</th>
                            <th>Promo Type</th>
                            <th>Cutoff</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view_schedule">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Schedule</b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body view_schedule"></div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>