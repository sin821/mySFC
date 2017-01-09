<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])||($_SESSION['role']==0)||($_SESSION['role']==2)) header('location: /index.php?status=failed&msg=You need to log in.');
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

        <?php
        $query = "SELECT syllabus_id, syllabus_code FROM tbl_syllabus WHERE 1";
        $result = mysqli_query($link, $query);
        while($row = mysqli_fetch_array($result)) {
          $syllabus_code = $row['syllabus_code'];
          $syllabus_id = $row['syllabus_id'];
          ?>
          <div class="row">
            <h3>Solo List for <?php echo $syllabus_code; ?></h3>
            <div class="col-md-12">
              <table id="<?php echo $syllabus_code; ?>" class="table table-hover table=condensed table-bordered text-left">
                <thead>
                  <tr>
                    <th><small>Name</small></th>
                    <th><small>Cse.</small></th>
                    <?php
                    $query2 = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus='$syllabus_code' AND sortie_type='SOLO'";
                    $result2 = mysqli_query($link, $query2);
                    while($row2 = mysqli_fetch_array($result2)) {
                      $sortie_code = str_replace('M2-', '', $row2['sortie_code']);
                      $sortie_code = str_replace('-S', '', $sortie_code);
                      ?>
                      <th><small><?php echo $sortie_code; ?></small></th>
                      <?php
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query2 = "SELECT cadet_name, cadet_opsname, cadet_course FROM tbl_cadets WHERE cadet_syllabus='$syllabus_id'";
                  $result2 = mysqli_query($link, $query2);
                  while($row2 = mysqli_fetch_array($result2)) {
                    $cadet = $row2['cadet_name'];
                    ?>
                    <tr>
                      <td><small><a onclick="getSorties('<?php echo $cadet; ?>')"><?php echo $row2['cadet_opsname']; ?></a></small></td>
                      <td><small><?php echo $row2['cadet_course']; ?></small></td>
                      <?php
                      $query3 = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus='$syllabus_code' AND sortie_type='SOLO'";
                      $result3 = mysqli_query($link, $query3);
                      while($row3 = mysqli_fetch_array($result3)) {
                        if($syllabus_code=='MPL-M2') {
                          preg_match("/\d{3}[AS]/sm", $row3['sortie_code'], $output_array);
                        }
                        elseif($syllabus_code=='CPL-A-A'||$syllabus_code=='CPL-G-B') {
                          preg_match("/(IPTS)?\d{3}/sm", $row3['sortie_code'], $output_array);
                        }
                        $code = $output_array[0];

                        $query4 = "SELECT COUNT(flightlist_sortie) AS completed, MAX(flightlist_date) AS last_planned FROM tbl_flightlist WHERE (flightlist_pilot1='$cadet' OR flightlist_pilot2='$cadet') AND flightlist_sortie RLIKE '$code' AND flightlist_status='PostFlight'";
                        $result4 = mysqli_query($link, $query4);
                        while($row4 = mysqli_fetch_array($result4)) {
                          $class = 'text-muted';
                          $last_planned = '';
                          if($row4['completed']>=1) {
                            $class = 'success text-center';
                            $last_planned = date('d/m', strtotime($row4['last_planned']));
                          }
                          ?>
                          <td class="<?php echo $class; ?>"><small><?php echo $last_planned; ?></small></td>
                          <?php
                        }

                      }
                      ?>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php
        }
        ?>

      </div>

    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="ajax"></div>
      </div>
    </div>


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        //initialise datatables
        <?php
        $query = "SELECT syllabus_code FROM tbl_syllabus WHERE 1";
        $result = mysqli_query($link, $query);
        while($row = mysqli_fetch_array($result)) {
          ?>
          $('#<?php echo $row['syllabus_code']; ?>').DataTable({
              "scrollY": 400,
              "scrollX": true,
              "paging": false,
              "responsive": false,
              "order": [[ 1, "asc" ]],
              "fixedColumns": true,
          });
          <?php
        }
        ?>
    } );

    function getSorties(cadet) {
      $('#ajax').load( encodeURI("../ajax/modal_sortiesCompleted.php?cadet="+cadet) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    </script>
  </body>
</html>
