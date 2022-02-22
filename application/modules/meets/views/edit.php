<?php
class editClass {
  public $users = array();
  
  public function setUsers($data) {
    $participant = array();
    foreach ($data as $user) {
      array_push($participant, $user->user_id);
    }
    $this->users = $participant;
  }
  
  public function isSelected($user)
  {
    if (in_array($user->id, $this->users)) return 'selected';
    return '';
  }
}

function replaceSpace($data) {
  return str_replace(" ","_",$data);
}

$meetClass = new editClass();
$meetClass->setUsers($meet->users);
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-info">
        <div class="card-body">
          <form id="btn-update-meet">
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-group">
                  <label for="meet-title">Title</label>
                  <input type="text" class="form-control" id="meet-title" placeholder="Title" name="title" value="<?= $meet->title ?>" required></input>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="meet-url">URL</label>
                  <input type="text" class="form-control" id="meet-url" pattern="^(https?):\/\/[^\s$.?#].[^\s]*$" title="Valid URL" placeholder="URL" name="url" value="<?= $meet->url ?>" required></input>
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label for="meet-division">Division</label>
                  <select id="meet-division" class="form-control" placeholder="Loading..." name="division" value="<?= $meet->division_id ?>" required>
                    <?php foreach ($division_dropdown as $division) {
                      echo "<option>" . $division->division_name . "</option>";
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label for="reservationdatetime">Date and Time</label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" id="meet-timedate" data-target="#reservationdatetime" value="<?= DateTime::createFromFormat('Y-m-d H:i:s', $meet->timedate)->format('m/d/Y h:i A') ?>" required>
                    <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div class="form-group">
                  <label for="summernote">Meet Purpose</label>
                  <textarea id="summernote" rows="6"><?= $meet->body ?></textarea>
                </div>
              </div>
              <div class="col-12 font-weight-bolder mb-2">
                Internal Participant
              </div>
              <div class="col-12 mb-3">
                <div class="select2-blue">
                  <select class="select2 select2-hidden-accessible" id="select-internal" data-placeholder="Select Participants" style="width: 100%;" multiple required>
                    <?php foreach ($users as $user) {
                      echo "<option ".$meetClass->isSelected($user)." value=" . $user->id . ">" . $user->full_name . "</option>";
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-12 font-weight-bolder">
                External Participant
              </div>
              <div class="col-12 col-md-6 ml-auto mb-3">
                <div class="d-flex">
                  <input type="text" class="form-control" id="external-participant-name" placeholder="Full Name" name="name"></input>
                  <input type="text" class="form-control ml-3" id="external-participant-email" placeholder="Email" name="email"></input>
                  <span class="btn btn-outline-primary ml-3" id="add-external-participant">Add</span>
                </div>
              </div>
              <div class="col-12 mb-3">
                <div>
                  <table id="external-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $count = count($meet->ext_users);
                      if ($count == 0) {
                        echo "<tr>
                  <td colspan='4' class='text-center'>No External Participant</td>
                </tr>";
                      } else {
                        foreach ($meet->ext_users as $item) {
                          echo "<tr class='external-participant' id='external-participant-".replaceSpace($item->name)."'>";
                          echo "<td>" . $item->name . "</td>";
                          echo "<td>" . $item->email . "</td>";
                          echo "<td>
                  <span class='btn btn-sm btn-danger btn-delete' external-member='".replaceSpace($item->name)."' id='delete-external-".replaceSpace($item->name)."'><i class='fas fa-trash'></i></span>
                  </td>";
                          echo "</tr>";
                        }
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row m-0 p-0">
              <button type="submit" class="btn btn-primary ml-auto">Edit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
