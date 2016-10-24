<?php
  if(isset($_GET['status'])) {

      $status = $_GET['status'];
      $msg = $_GET['msg'];
      ?>
      <div class="alert alert-<?php echo $status ?>" role="alert">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span><b> Alert: </b></span>
        <?php echo $msg; ?>
      </div>
      <?php

  }
?>
<script>
//set alert to slide up after 3s
window.setTimeout(function () {
    $(".alert").slideUp();
}, 4000);
</script>