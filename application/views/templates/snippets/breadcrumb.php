<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1><?php echo $page ?></h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <?php echo "<li class='breadcrumb-item'><i class='fas fa-home'></i></li>"?>
        <?php getBreadcrumb("$_SERVER[REQUEST_URI]"); ?>
      </ol>
    </div>
  </div>
</div><!-- /.container-fluid -->

<?php
function getBreadcrumb($uri)
{
  $arr = explode("/", $uri);
  $count = count($arr);
  for ($x = 1; $x < $count; $x++) {
    $active = ($x == ($count - 1)) ? "'>$arr[$x]" : "'><a href='". base_url($arr[1]) ."'>$arr[$x]</a>";
    echo "<li class='breadcrumb-item " . $active . "</li>";
    // echo ' +'. $arr[$x];
  }
}
?>