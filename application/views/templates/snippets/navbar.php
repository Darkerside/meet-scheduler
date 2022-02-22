<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown row">
      <div class="align-self-center userinfo mr-3 text-right">
        <span class="d-block"><?= $user['username']; ?></span>
        <span class="d-block"><?= $user['role']; ?></span>
      </div>
      <div data-toggle="dropdown">
        <img src="<?php echo 'https://ui-avatars.com/api/?background=random&name='. str_replace(" ","+",$user['username']); ?>" alt="User Avatar" class="img-size-50 img-circle mr-3">
      </div>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-item" id="btn-change-password"><i class="fas fa-key mr-2"></i>Change Password</div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
      </div>
    </li>
  </ul>
</nav>
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <span class="custom-close" data-dismiss="modal"><i class="fas fa-times"></i></span>
      <form id="change-password" method="post">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="old-password">Old Password</label>
            <input type="password" class="form-control" id="old-password" placeholder="Old Password" name="old-password" required></input>
          </div>
          <div class="form-group mb-3">
            <label for="new-password">New Password</label>
            <input type="password" class="form-control" id="new-password" placeholder="New Password" name="new-password" required></input>
          </div>
          <div class="form-group mb-3">
            <label for="retype-password">Retype Password</label>
            <input type="password" class="form-control" id="retype-password" placeholder="Retype Password" name="retype-password" required></input>
          </div>
          <div class="row m-0 p-0 mt-2 justify-content-center">
            <button type="submit" class="btn btn-primary btn-submit px-5" id="btn-save">Confirm</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.navbar -->