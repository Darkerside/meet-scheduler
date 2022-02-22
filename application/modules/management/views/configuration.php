<div class="container-fluid">
  <div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <form id="form-config" method="post">
            <div class="row">
              <?php foreach ($configTable as $item) {
              ?>
                <div class="col-12 col-md-2 mb-0 mb-md-3">
                  <div class="justify-content-between align-items-center h-100 d-md-flex">
                    <span class="d-inline-block"><?= $item->variable ?></span>
                    :
                  </div>
                </div>
                <div class="col-12 col-md-10 mb-3">
                  <input type="<?= formType($item); ?>" class="form-control" id="<?= $item->variable ?>" placeholder="<?= $item->variable ?>" name="<?= $item->variable ?>" value="<?= decodeJWT($item); ?>" required></input>
                </div>
              <?php } ?>
            </div>
            <div class="row m-0 p-0 mt-2">
              <button type="submit" class="btn btn-primary ml-auto">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <span class="custom-close" data-dismiss="modal"><i class="fas fa-times"></i></span>
      <form id="form-confirm" method="post">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="admin-password">Password</label>
            <input type="password" class="form-control" id="admin-password" placeholder="Admin Password" name="admin-password" required></input>
          </div>
          <div class="row m-0 p-0 mt-2 justify-content-center">
            <button type="submit" class="btn btn-primary btn-submit px-5" id="btn-save">Confirm</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
function decodeJWT($data)
{
  if ($data->variable == 'Password') {
    $jwt = $data->value;
    $decode = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $jwt)[1]))));
    return $decode->password;
  } else {
    return $data->value;
  }
}

function formType($data)
{
  if ($data->variable == 'Password') {
    return 'password';
  } 
  else if ($data->variable == 'Email') {
    return 'email';
  } else {
  return 'text';
  }
}
?>