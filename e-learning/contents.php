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
        <h1>Mass Briefs</h1>

        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Mass Brief No.</th>
                  <th>Name</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * FROM tbl_briefs WHERE 1";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td><?php echo $row['brief_id']; ?></td>
                    <td><?php echo $row['brief_massbrief']; ?></td>
                    <td><a href="player.php?brief=<?php echo $row['brief_code']; ?>"><?php echo $row['brief_code']; ?> - <?php echo $row['brief_name']; ?></a></td>
                  </tr>
                  <?php
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
