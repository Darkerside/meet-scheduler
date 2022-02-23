<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link text-center">
    <span class="brand-text font-weight-light">Scheduler Meet</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar mt-2">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="<?= base_url('home') ?>" class="nav-link <?php echo ($menu == 'Home') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('meets') ?>" class="nav-link <?php echo ($menu == 'My Meets') ? 'active' : '' ?>">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
              My Meets
            </p>
          </a>
        </li>
        <?php if ($user['role'] == 'Division Lead' || $user['role'] == 'Admin') {?>
        <li class="nav-item">
          <a href="<?= base_url('meets') ?>/division" class="nav-link <?php echo ($menu == 'Division Meets') ? 'active' : '' ?>">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
              Divisions Meets
            </p>
          </a>
        </li>
        <?php };?>
        <?php if ($user['role'] == 'Admin') { ?>
          <li class="nav-header">Management</li>
          <li class="nav-item">
            <a href="<?= base_url('management') ?>/users" class="nav-link <?php echo ($menu == 'Management User') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('management') ?>/divisions" class="nav-link <?php echo ($menu == 'Management Division') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>
                Divisions
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('management') ?>/configuration" class="nav-link <?php echo ($menu == 'Configuration') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Configuration
              </p>
            </a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>