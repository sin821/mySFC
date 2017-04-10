<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">
        <h1>RPL Documents</h1>

        <div class="row">
        <?php
        $dir    = '../documents/RPL_Info';
        $file = scandir($dir);
        ?>
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>File Name</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach($file as $key => $value) {
                  if ( !in_array( $value, array( '.', '..', '.DS_Store' ) ) ) {
                  ?>
                  <tr>
                    <td><a href="/documents/RPL_Info/<?php echo $value; ?>"><?php echo $value; ?></a></td>
                  </tr>
                  <?php
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div><!-- /.container -->


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        //initialise datatable
        $('#table').DataTable();
    } );
    </script>
  </body>
</html>
