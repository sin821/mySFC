<?php
include('../php/db_conn.php');

if($_GET['id']>=0 && isset($_GET['id'])) {
    $instruction_id = $_GET['id'];
    $query = "SELECT * FROM tbl_plannerinstructions JOIN tbl_cadets ON plannerinstruction_creator=cadet_id WHERE plannerinstruction_id='$instruction_id'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
      $instruction_content = $row['plannerinstruction_content'];
      $instruction_creator = $row['cadet_name'];
    }
}
?>
<form id="editForm" method="POST" action="../php/submitPlannerInstruction.php">

<div class="modal-header">
  <h4 class="modal-title block-head" id="myModalLabel">Edit Planner Instruction</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
          <input type="hidden" value="<?php echo $_GET['id']; ?>" name="id" />
            <div class="form-group">
              <label>Instruction: </label>
              <textarea class="form-control" name="instruction" id="instruction" placeholder="Any general instructions to pass on to the next planner..." required><?php echo $instruction_content; ?></textarea>
            </div>
            <?php
            if(isset($_GET['id']) && $_GET['id']>0) {
            ?>
              <p class="text-muted text-right"><small>Instruction created by <?php echo $instruction_creator; ?></small></p>
            <?php
            }
            ?>
            <div>
              <span id="errorSpan"></span>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
  <div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>

</form>