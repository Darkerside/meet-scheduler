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
            <a href="<?= base_url('notes') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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

        <div class="col-12 mb-4"><h3>Your Meet Today</h3></div>
        <div class="col-12">
          <div class="row">
            <?php
            $anyMeet = false;
            foreach ($userMeets as $meet) {
              if (checkIsMeetToday($meet->timedate)) {
                $anyMeet = true; ?>

                <div class="col-12">
                  <div class="card bg-light">
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
                            <a href="<?= $meet->url ?>" target="_blank" class="btn btn-success mr-2"><i class="fas fa-video mr-2"></i>Join</a>
                          <?php } ?>
                          <button class="btn btn-primary btn-detail"><i class="fas fa-info-circle mr-2"></i>View</button>
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
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Meeting Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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