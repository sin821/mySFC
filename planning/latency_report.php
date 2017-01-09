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
        <h1>Latency Report</h1>

        <div class="row">

          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Cse.</th>
                  <th>Inst.</th>
                  <th>Lat.</th>
                  <th>Last Dual</th>
                  <th>Last Solo</th>
                  <th>Dual Plan?</th>
                  <th>Solo Plan?</th>
                  <th>Duty Period</th>
                  <th>Solo Since Dual</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $query = "SELECT * FROM vw_latency";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                $cadet = $row['dual_cadet'];
                ?>
                <tr>
                  <td><a onclick="getSorties('<?php echo $row['dual_cadet']; ?>')"><?php echo $row['dual_cadet']; ?></a></td>
                  <td><?php echo $row['cadet_course']; ?></td>
                  <td><?php echo $row['instructor_initials']; ?></td>
                  <td><?php echo $row['total_latency']; ?></td>
                  <td><?php echo $row['dual_sortie']; ?></td>
                  <td><?php echo $row['solo_sortie']; ?></td>
                  <td>
                  <?php
                  //get dual sortie if planned today
                  $query2 = "SELECT flightlist_sortie, flightlist_status FROM tbl_tms2currentplan WHERE flightlist_date=DATE(NOW()) AND flightlist_pilot2='$cadet'";
                  $result2 = mysqli_query($link, $query2);
                  while($row2 = mysqli_fetch_array($result2)) {
                    ?>
                    <?php echo $row2['flightlist_sortie']; ?><br />(<?php echo $row2['flightlist_status']; ?>) <br /><br />
                    <?php
                  }
                  ?>
                  </td>
                  <td>
                  <?php
                  //get solo sortie if planned today
                  $query3 = "SELECT flightlist_sortie, flightlist_status FROM tbl_tms2currentplan WHERE flightlist_date=DATE(NOW()) AND flightlist_pilot1='$cadet'";
                  $result3 = mysqli_query($link, $query3);
                  while($row3 = mysqli_fetch_array($result3)) {
                    ?>
                    <?php echo $row3['flightlist_sortie']; ?><br />(<?php echo $row3['flightlist_status']; ?>) <br /><br />
                    <?php
                  }
                  ?>
                  </td>
                  <td>
                  <?php
                  //check if planned today
                  $consecutive_duty = 0;
                  $query4 = "SELECT COUNT(*) AS planned_today FROM tbl_tms2currentplan WHERE flightlist_date=DATE(NOW()) AND (flightlist_pilot1='$cadet' OR flightlist_pilot2='$cadet')";
                  $result4 = mysqli_query($link, $query4);
                  while($row4 = mysqli_fetch_array($result4)) {
                    $planned_today = $row4['planned_today'];
                    if($planned_today!=0) { //if planned today
                      $consecutive_duty = 1;
                      //check if planned in the last 5 days.
                      $i = 1;
                      while($consecutive_duty==$i) { //while consecutive duty is the same number as the day being checked
                        $query5 = "SELECT COUNT(*) AS planned FROM tbl_flightlist WHERE flightlist_date=DATE(NOW() - INTERVAL $i DAY) AND (flightlist_pilot1='$cadet' OR flightlist_pilot2='$cadet') AND flightlist_status='PostFlight'";
                        $result5 = mysqli_query($link, $query5);
                        while($row5 = mysqli_fetch_array($result5)) {
                          $planned = $row5['planned'];
                          if($planned!=0) { //if planned
                            $consecutive_duty ++;
                          } //if planned
                        }
                        $i++;
                      }//while consecutive duty is the same number as the day being checked
                      
                    } //if planned today
                  }
                  echo $consecutive_duty;
                  ?>
                  </td>
                  <td>
                  <?php
                  //get number of solo sorties since last dual
                  $query6 ="SELECT COUNT(*) AS solo_count FROM tbl_flightlist WHERE (flightlist_date BETWEEN (SELECT dual_date FROM vw_lastduals WHERE dual_cadet='$cadet') AND DATE(NOW())) AND (flightlist_pilot1='$cadet') AND flightlist_status!='Cancelled'";
                  $result6 = mysqli_query($link, $query6);
                    while($row6 = mysqli_fetch_array($result6)) {
                      echo $row6['solo_count'];
                    }
                  ?>
                  </td>

                </tr>
                <?php
              }
              ?>
              </tbody>
            </table>
          </div>

          <div class="col-lg-2 col-md-12">
          </div>

        </div>
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
        //initialise datatable
        $('#table').DataTable({
            "paging": false,
            "responsive": true,
            "order": [[ 2, "asc" ],[ 3, "desc" ],[ 1, "asc" ]]
        });
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
