<div class="container-fluid">
  <div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-2 btn-create" data-toggle="modal" data-target="#createModal">
              Create
            </button>
          <table id="user-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Division</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $data = count($usersTable);
              if ($data == 0) {
                echo "<tr>
                  <td colspan='6' class='text-center'>No Data</td>
                </tr>";
              } else {
                foreach ($usersTable as $item) {
                  echo "<tr>";
                  echo "<td>" . $item->id . "</td>";
                  echo "<td>" . $item->username . "</td>";
                  echo "<td>" . $item->email . "</td>";
                  echo "<td>" . $item->role_name . "</td>";
                  echo "<td>" . $item->division_name . "</td>";
                  echo "<td>" . $item->is_active . "</td>";
                  echo "<td>
                  <button class='btn btn-sm btn-success mr-1 btn-view' user-data='" . $item->id . "'><i class='fas fa-eye'></i></button>
                  <button class='btn btn-sm btn-primary mr-1 btn-edit' user-data='" . $item->id . "'><i class='fas fa-edit'></i></button>
                  <button class='btn btn-sm btn-danger btn-delete' user-data='" . $item->id . "'><i class='fas fa-trash'></i></button>
                  </td>";
                  echo "</tr>";
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Division</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Create User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="createUsers" method="post">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" placeholder="Username" name="username" required></input>
            </div>
            <div class="form-group mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" placeholder="Email" name="email" required></input>
            </div>
            <div class="form-group mb-3">
              <label for="password">Password</label>
              <input type="password" class="form-control" placeholder="Password" name="password" id="password" required></input>
            </div>
            <div class="form-group mb-3">
              <label for="retype">Retype Password</label>
              <input type="password" class="form-control" placeholder="Retype Password" name="retype" id="retype" required></input>
            </div>
            <div class="form-group mb-3">
              <label for="fullname">Full Name</label>
              <input type="text" class="form-control" id="full-name" placeholder="Full Name" name="fullname" required></input>
            </div>
            <div class="form-group mb-3">
              <label for="role">Role</label>
              <select id="user-role" class="form-control" placeholder="role" name="role" required>
                <option selected disabled>Loading...</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="division">Division</label>
              <select id="user-division" class="form-control" placeholder="Division" name="division" required>
                <option selected disabled>Loading...</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="is-active">Activate</label>
              <select id="is-active" class="form-control" name="is-active" required>
                <option value="null" disabled>Choose</option>
                <option value="1">Active</option>
                <option value="0">Non-Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-submit">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>