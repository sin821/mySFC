<?php
include('../php/db_conn.php');

if($_GET['id']>=0 && isset($_GET['id'])) {
    $instruction_id = $_GET['id'];
    $query = "SELECT * FROM tbl_instructorinstructions WHERE instructorinstruction_id='$instruction_id'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
      $instruction_content = $row['instructorinstruction_content'];
    }
}
$instructor = $_GET['inst'];
?>
<div class="modal-header">
  <h4 class="modal-title block-head" id="myModalLabel">Edit Instruction for <?php echo $instructor; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
          <form id="editForm" method="POST" action="../php/.php">
            <div class="form-group">
              <label>Instruction: </label>
              <textarea class="form-control" name="instruction" id="instruction" placeholder="Any instructor-specific instructions to pass on to the next planner..." required><?php echo $instruction_content; ?></textarea>
            </div>
            <div>
              <span id="errorSpan"></span>
            </div>
          </form>

        </div>
    </div>
</div>
<div class="modal-footer">
  <div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick="submitRegistration()">Submit</button>
    </div>
</div>

<script>
function submitRegistration() {
  var error = 0;
  var email = document.getElementById('email').value;
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!re.test(email)){
      document.getElementById('errorSpan').innerHTML = "Not a valid email address.";
      error = 1;
    }
    if(error==0) {
      document.getElementById('registrationForm').submit();
    }
}
</script>