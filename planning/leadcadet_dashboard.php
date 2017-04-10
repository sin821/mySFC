<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])||($_SESSION['role']==0)) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
$cadet_id = $_SESSION['cadet'];
$query = "SELECT cadet_instructor FROM tbl_cadets WHERE cadet_id='$cadet_id'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
  $leadcadet_instructor = $row['cadet_instructor'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">

        <div class="row">

          <div class="col-md-2">

            <div class="panel panel-danger">
              <!-- Default panel contents -->
              <div class="panel-heading">Repeat Sorties</div>
              <!-- List group -->
              <ul class="list-group">
              <?php
              $query = "SELECT cadet_opsname, cadet_name, sortie_code, repeatedsortie_id, DATE(repeatedsortie_date) AS repeatedsortie_date FROM tbl_repeatedsorties JOIN tbl_cadets ON repeatedsortie_cadet=cadet_id JOIN tbl_sorties ON repeatedsortie_sortie=sortie_id WHERE repeatedsortie_done='0' AND cadet_instructor='$leadcadet_instructor'";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                $cadet_opsname = $row['cadet_opsname'];
                ?>
                <li class="list-group-item clickable"><a onclick="getSorties('<?php echo $cadet_opsname; ?>')"><?php echo $cadet_opsname; ?></a>
                <small class="pull-left"><a class="text-muted" onclick="markRepeatCompleted('<?php echo $row['repeatedsortie_id']; ?>')"><i class="fa fa-times"></i></a></small><br/ >
                <span class="text-muted">
                <small><?php echo $row['sortie_code']; ?></small><br />
                <small><?php echo date('j M',strtotime($row['repeatedsortie_date'])); ?></small><br />
                </span>
                </li>
                <?php
              }
              ?>
              </ul>
            </div>

            <div class="panel panel-info">
              <!-- Default panel contents -->
              <div class="panel-heading">No-Plan List</div>
              <!-- List group -->
              <ul class="list-group">
              <?php
              $query = "SELECT cadet_opsname, request_noplanstart, request_noplanend, request_remarks FROM tbl_requests JOIN tbl_cadets ON request_cadet=cadet_id WHERE request_type='no-plan' AND cadet_instructor='$leadcadet_instructor' AND (DATE(NOW())>=(request_noplanstart-INTERVAL 2 DAY) AND DATE(NOW())<=request_noplanend) ORDER BY request_noplanstart ASC";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                ?>
                <li class="list-group-item"><?php echo $row['cadet_opsname']; ?><br/ ><small class="text-muted"><?php echo date('j M',strtotime($row['request_noplanstart'])); ?> - <?php echo date('j M',strtotime($row['request_noplanend'])); ?></small><br /><small><?php echo $row['request_remarks']; ?></small></li>
                <?php
              }
              ?>
              </ul>
            </div>

            <div class="panel panel-warning">
              <!-- Default panel contents -->
              <div class="panel-heading">Other Requests</div>
              <!-- List group -->
              <ul class="list-group">
              <?php
              $query = "SELECT cadet_opsname, request_remarks, request_id, request_timestamp FROM tbl_requests JOIN tbl_cadets ON request_cadet=cadet_id WHERE request_type='message' AND request_done='0' AND cadet_instructor='$leadcadet_instructor'";
              $result = mysqli_query($link, $query);
              while($row = mysqli_fetch_array($result)) {
                ?>
                <li class="list-group-item clickable" onclick="markRequestCompleted('<?php echo $row['request_id']; ?>')"><small><?php echo $row['request_remarks']; ?></small>
                <p class="text-right text-muted"><small>- <?php echo $row['cadet_opsname']." (".date('j M',strtotime($row['request_timestamp'])).")"; ?></small></p>
                </li>
                <?php
              }
              ?>
              </ul>
            </div>

          </div>
          <div class="col-md-10">
          <?php
          $row_organiser = 1;
          $query = "SELECT instructor_id, instructor_initials FROM tbl_instructors WHERE instructor_id='$leadcadet_instructor'";
          $result = mysqli_query($link, $query);
          while($row = mysqli_fetch_array($result)) {
            $instructor_id = $row['instructor_id'];
            $instructor = $row['instructor_initials'];
            if($row_organiser%3==0) echo "<div class='row'>";
            ?>
            <div class="col-md-4">
              <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><?php echo $instructor; ?><span class="pull-right" onclick="modifyInstructorInstruction('<?php echo $instructor; ?>','0')"><i class="fa fa-plus"></i></span></div>

                <?php
                $query2 = "SELECT COUNT(*) AS instruction_count FROM tbl_instructorinstructions WHERE instructorinstruction_instructor='$instructor_id' AND instructorinstruction_done='0'";
                $result2 = mysqli_query($link, $query2);
                while($row2 = mysqli_fetch_array($result2)) {
                  $instruction_count = $row2['instruction_count'];
                }
                if($instruction_count>0) {
                  //do not do this is there are no instructions.
                  ?>
                  <div class="panel-body text-left">
                    <ul>
                    <?php
                    $query2 = "SELECT * FROM tbl_instructorinstructions WHERE instructorinstruction_instructor='$instructor_id' AND instructorinstruction_done='0'";
                    $result2 = mysqli_query($link, $query2);
                    while($row2 = mysqli_fetch_array($result2)) {
                      $instruction_id = $row2['instructorinstruction_id'];
                      ?>
                      <li><a onclick="modifyInstructorInstruction('<?php echo $instructor; ?>','<?php echo $instruction_id; ?>')"><small><?php echo $row2['instructorinstruction_content']; ?></small></a></li>
                      <?php
                    }
                    ?>
                    </ul>
                  </div>
                  <?php
                }
                ?>

                <!-- List group -->
                <ul class="list-group">
                <?php
                //get cadets by instructor
                $query2 = "SELECT cadet_name, cadet_opsname, total_latency FROM vw_latency JOIN tbl_cadets ON dual_cadet=cadet_name WHERE instructor_initials='$instructor' ORDER BY total_latency DESC, vw_latency.cadet_course ASC";
                $result2 = mysqli_query($link, $query2);
                while($row2 = mysqli_fetch_array($result2)) {

                  $cadet_name = $row2['cadet_name'];

                  //check if cadet is flying today
                  $consecutive_duty = 0;
                  $query3 = "SELECT COUNT(*) AS planned_today FROM tbl_tms2currentplan WHERE flightlist_pilot1='$cadet_name' OR flightlist_pilot2='$cadet_name'";
                  $result3 = mysqli_query($link, $query3);
                  while($row3 = mysqli_fetch_array($result3)) {
                    $isPlanned = $row3['planned_today'];

                    if($isPlanned!=0) { //if planned today
                      $consecutive_duty = 1;
                      //check if planned in the last 5 days.
                      $i = 1;
                      while($consecutive_duty==$i) { //while consecutive duty is the same number as the day being checked
                        $query5 = "SELECT COUNT(*) AS planned FROM tbl_flightlist WHERE flightlist_date=DATE(NOW() - INTERVAL $i DAY) AND (flightlist_pilot1='$cadet_name' OR flightlist_pilot2='$cadet_name') AND flightlist_status='PostFlight'";
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

                  if($consecutive_duty<5) {
                  	$duty_class = "pull-left text-success";
                  }
                  else {
                  	$duty_class = "pull-left text-red";
                  }

                  if($row2['total_latency']>=5 && $isPlanned==0) {
                    $class = "list-group-item list-group-item-danger";
                  }
                  elseif($row2['total_latency']<5 && $row2['total_latency']>=3 && $isPlanned==0) {
                    $class = "list-group-item list-group-item-warning";
                  }
                  else {
                    $class = "list-group-item list-group-item-success";
                  }
                  ?>
                  <a onclick="getSorties('<?php echo $row2['cadet_opsname']; ?>')">
                    <li class="<?php echo $class; ?>">
                    	<span class="<?php echo $duty_class; ?>"><?php echo $consecutive_duty; ?></span>
                    <?php echo $row2['cadet_opsname']; ?>
                      <span class="pull-right">
                      <?php
                      if($isPlanned==0) {
                        echo $row2['total_latency'];
                      }
                      else {
                        echo "<i class='fa fa-plane'></i> ".$row2['total_latency'];
                      }
                      ?>
                      </span>
                    </li>
                  </a>
                  <?php
                }
                ?>
                </ul>
              </div>
            </div>
            <?php
          if($row_organiser%3==0) echo "</div>";
          $row_organiser++;
          }
          ?>
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
    function getSorties(cadet) {
      $('#ajax').load( encodeURI("../ajax/modal_sortiesCompleted.php?cadet="+cadet) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    function modifyInstructorInstruction(instructor, id) {
      $('#ajax').load( encodeURI("../ajax/modal_modifyInstructorInstruction.php?inst="+instructor+"&id="+id) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    function modifyPlannerInstruction(id) {
      $('#ajax').load( encodeURI("../ajax/modal_modifyPlannerInstruction.php?id="+id) ,function(responseTxt,statusTxt,xhr){
        if(statusTxt=="success") {
          $('#myModal').modal();
        }
        if(statusTxt=="error")
          console.log("Error: "+xhr.status+": "+xhr.statusText);
      });
    }
    function markRequestCompleted(id) {
      if(confirm("Are you sure you have completed this request or passed it on to the next planner? \nMarking this as complete will remove it from this list.")) {
        $('#ajax').load( encodeURI("../php/markRequestAsComplete.php?id="+id) ,function(responseTxt,statusTxt,xhr){
          if(statusTxt=="success") {
            //refresh request box
            window.location.reload();
          }
          if(statusTxt=="error")
            console.log("Error: "+xhr.status+": "+xhr.statusText);
        });
      }
    }
    function markRepeatCompleted(id) {
      if(confirm("Are you sure this sortie has been PostFlighted i.e. green in TMS2? \nMarking this as complete will remove it from this list.")) {
        $('#ajax').load( encodeURI("../php/markRepeatAsComplete.php?id="+id) ,function(responseTxt,statusTxt,xhr){
          if(statusTxt=="success") {
            //refresh request box
            window.location.reload();
          }
          if(statusTxt=="error")
            console.log("Error: "+xhr.status+": "+xhr.statusText);
        });
      }
    }
    </script>
  </body>
</html>
