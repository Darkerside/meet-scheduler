<div class="container-fluid">
  <div class="row">
    <!-- /.col -->
    <!-- <div class="col-md-12">
      <div class="card card-outline card-info fill-height">
        <div class="card-body bg-custom-light fill" id="meet">
          <div class="row">
            
          </div>
        </div>
      </div>
    </div> -->
    <div class="col-12 mb-3">
      <div class="d-flex">
        <a href="<?= base_url('meets'); ?>/new" class="btn btn-primary ml-auto" id="create-button">Create Meet</a>
      </div>
    </div>
    <?php
    $anyMeet = false;
    $meets = ($userMeets) ? $userMeets : $divisionMeets;
    if ($meets) {
      foreach ($meets as $meet) {
        $anyMeet = true; ?>

        <div class="col-12">
          <div class="card card-outline card-primary">
            <div class="card-body d-flex">
              <div class="col-4 col-md-3 col-lg-3 col-xl-2 card-meet-icon text-center text-white">
                <div class="d-flex justify-content-center align-items-center h-100">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
              </div>
              <div class="col card-meet-summary">
                <div class="row">
                  <div class="col-12">
                    <h3><?= $meet->title ?></h3>
                  </div>
                  <div class="col-12"><span class="meet-header">Division</span>: <?= $meet->division_name ?></div>
                  <div class="col-12"><span class="meet-header">Time</span>: <?= DateTime::createFromFormat('Y-m-d H:i:s', $meet->timedate)->format('H:i'); ?> WIB</div>
                  <div class="col-12"><span class="meet-header">Date</span>: <?= DateTime::createFromFormat('Y-m-d H:i:s', $meet->timedate)->format('D, d M Y'); ?></div>
                  <div class="px-2 mt-3 ml-auto">
                    <?php if (checkActivateLink($meet->timedate)) { ?>
                      <a href="<?= $meet->url ?>" target="_blank" class="btn btn-success mr-2"><i class="fas fa-video mr-2"></i>Join</a>
                    <?php } ?>
                    <button class="btn btn-primary btn-detail" meet-id="<?= $meet->meet_id ?>"><i class="fas fa-info-circle mr-2"></i>View</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      <?php };
    }
    if (!$anyMeet) { ?>
      <div class="col-12 text-center font-weight-bold">- No Meet Schedules -</div>
    <?php }; ?>
  </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row meet-detail-header modal-blue">
          <div class="col-12 col-md-8">
            <h4 id="meeting-title"></h4>
            <span id="meeting-url"></span>
          </div>
          <div class="col-12 col-md-4 text-right my-auto">
            <div id="meeting-division"></div>
            <div id="meeting-date"></div>
            <div id="meeting-time"></div>
          </div>
        </div>
        <div class="p-2 pt-0">

          <div class="row mx-0 my-3">
            <div class="col-12" id="meeting-body"></div>
          </div>

          <div class="row mb-3">
            <div class="col-12 mb-1 meet-detail-section modal-blue"><strong>Participants</strong></div>
            <div class="col-12">
              <div class="accordion" id="participants">
                <div class="card mb-0">
                  <div class="card-header modal-blue" id="headingInternal">
                    <h2 class="mb-0">
                      <button class="btn btn-block text-left" type="button" data-toggle="collapse" data-target="#internal-member" aria-expanded="true" aria-controls="internal-member">
                        <i class="fas fa-user-friends mr-2"></i> Internal Participants
                      </button>
                    </h2>
                  </div>

                  <div id="internal-member" class="collapse" aria-labelledby="headingInternal" data-parent="#participants">
                    <div class="card-body table-responsive">
                      <table id="internal-user-table" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Full Name</th>
                            <th>Division</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan='6' class='text-center'>No Internal Participant</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header modal-blue" id="headingExternal">
                    <h2 class="mb-0">
                      <button class="btn btn-block text-left" type="button" data-toggle="collapse" data-target="#external-member" aria-expanded="true" aria-controls="external-member">
                        <i class="fas fa-user-friends mr-2"></i> External Participants
                      </button>
                    </h2>
                  </div>

                  <div id="external-member" class="collapse" aria-labelledby="headingExternal" data-parent="#participants">
                    <div class="card-body table-responsive">
                      <table id="external-user-table" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Full Name</th>
                            <th>E-Mail</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan='6' class='text-center'>No External Participant</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row m-0 px-2 mb-2" id="card-row-button">
          <?php if ($user['role'] == 'Division Lead' || $user['role'] == 'Admin') { ?><a class="btn btn-warning mr-3" id="btn-edit-meet"><i class="fas fa-edit mr-2"></i> Edit</a><?php }; ?>
          <button class="btn btn-primary" id="btn-send-mail"><i class="fas fa-paper-plane"></i> Send E-Mail</button>
          <button class="btn btn-secondary ml-auto" data-dismiss="modal"><i class="far fa-times-circle mr-2"></i>Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

function checkActivateLink($time)
{
  $nowTime =  time();
  $meetTime = DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('U');
  $linkActive = $meetTime - $nowTime;
  return $linkActive <= 3600;
}
?>