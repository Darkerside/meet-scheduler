<div class="container-fluid">
  <div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-2 btn-create" data-toggle="modal" data-target="#createModal">
              Create
            </button>
          <table id="division-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $data = count($divisionsTable);
              if ($data == 0) {
                echo "<tr>
                  <td colspan='6' class='text-center'>No Data</td>
                </tr>";
              } else {
                foreach ($divisionsTable as $item) {
                  echo "<tr>";
                  echo "<td>" . $item->id . "</td>";
                  echo "<td>" . $item->division_name . "</td>";
                  echo "<td>" . $item->is_active . "</td>";
                  echo "<td>
                  <button class='btn btn-sm btn-success mr-1 btn-view' division-data='" . $item->id . "'><i class='fas fa-eye'></i></button>
                  <button class='btn btn-sm btn-primary mr-1 btn-edit' division-data='" . $item->id . "'><i class='fas fa-edit'></i></button>
                  <button class='btn btn-sm btn-danger btn-delete' division-data='" . $item->id . "'><i class='fas fa-trash'></i></button>
                  </td>";
                  echo "</tr>";
                }
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Name</th>
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
          <h5 class="modal-title" id="createModalLabel">Create New Division</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="createDivision" method="post">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" placeholder="Name" name="name" required></input>
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