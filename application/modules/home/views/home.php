<div class="container-fluid">
  <div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="row">
        <div class="col-lg-3 col-6">

          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $dashboard['meetList'] ?></h3>
              <p>Your Meet Schedule</p>
            </div>
            <div class="icon">
              <i class="far fa-calendar-alt"></i>
            </div>
            <a href="<?= base_url('meets') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php if ($user['role'] == 'Admin') { ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $dashboard['divisions'] ?></h3>
                <p>Divisions</p>
              </div>
              <div class="icon">
                <i class="fas fa-sitemap"></i>
              </div>
              <a href="<?= base_url('management') ?>/divisions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?= $dashboard['users'] ?></h3>
                <p>Users</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="<?= base_url('management') ?>/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?= $dashboard['totalMeets'] ?></h3>
                <p>Total Meets</p>
              </div>
              <div class="icon">
                <i class="far fa-calendar-alt"></i>
              </div>
              <a href="<?= base_url('meets') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>

        <?php if ($user['role'] == 'Division Lead') { ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?= $dashboard['divisionMeets'] ?></h3>
                <p>Division Meets</p>
              </div>
              <div class="icon">
                <i class="far fa-calendar-alt"></i>
              </div>
              <a href="<?= base_url('meets') ?>/division" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>

        <div class="col-12">
          <div class="card card-outline card-success">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3>Today Meet</h3>
              </div>
            </div>
            <div class="card-body bg-custom-light">
              <div class="row">
                <?php
                $anyMeet = false;
                foreach ($userMeets as $meet) {
                  if (checkIsMeetToday($meet->timedate)) {
                    $anyMeet = true; ?>

                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12">
                              <h3>Meet <?= $meet->division_name ?> Division</h3>
                            </div>
                            <div class="col-12"><span class="meet-header">Title</span>: <?= $meet->title ?></div>
                            <div class="col-12"><span class="meet-header">Time</span>: <?= DateTime::createFromFormat('Y-m-d H:i:s', $meet->timedate)->format('H:i'); ?> WIB</div>
                            <div class="col-12"><span class="meet-header">Date</span>: <?= DateTime::createFromFormat('Y-m-d H:i:s', $meet->timedate)->format('D, d M Y'); ?></div>
                            <div class="px-2 ml-auto">
                              <?php if (checkActivateLink($meet->timedate)) { ?>
                                <a href="<?= $meet->url ?>" target="_blank" class="btn btn-primary mr-2"><i class="fas fa-video mr-2"></i>Join</a>
                              <?php } ?>
                              <button class="btn btn-success btn-detail" meet-id="<?= $meet->meet_id ?>"><i class="fas fa-info-circle mr-2"></i>View</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php };
                };
                if (!$anyMeet) { ?>
                  <div class="col-12 text-center font-weight-bold">- No Meet Today -</div>
                <?php }; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row meet-detail-header">
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
            <div class="col-12 mb-1 meet-detail-section"><strong>Participants</strong></div>
            <div class="col-12">
              <div class="accordion" id="participants">
                <div class="card mb-0">
                  <div class="card-header" id="headingInternal">
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
                  <div class="card-header" id="headingExternal">
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
        <div class="row m-0 justify-content-between px-2 mb-2">
          <button class="btn btn-success" id="btn-send-mail"><i class="fas fa-paper-plane mr-2"></i> Send E-Mail</button>
          <button class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle mr-2"></i>Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

function checkIsMeetToday($time)
{
  $today = date('Y-m-d');
  $meetTime = DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('Y-m-d');
  return $today == $meetTime;
}

function checkActivateLink($time)
{
  $nowTime =  time();
  $meetTime = DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('U');
  $linkActive = $meetTime - $nowTime;
  return $linkActive <= 3600;
}
?>