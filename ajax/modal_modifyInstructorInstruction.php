<?php
include('../php/db_conn.php');

if($_GET['id']>=0 && isset($_GET['id'])) {
    $instruction_id = $_GET['id'];
    $query = "SELECT * FROM tbl_instructorinstructions JOIN tbl_cadets ON instructorinstruction_creator=cadet_id WHERE instructorinstruction_id='$instruction_id'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
      $instruction_content = $row['instructorinstruction_content'];
      $instruction_creator = $row['cadet_name'];
    }
}
$instructor = $_GET['inst'];
?>
<form id="editForm" method="POST" action="../php/submitInstructorInstruction.php">

<div class="modal-header">
  <h4 class="modal-title block-head" id="myModalLabel">Edit Instruction for <?php echo $instructor; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
          <input type="hidden" value="<?php echo $_GET['id']; ?>" name="id" />
            <input type="hidden" value="<?php echo $_GET['inst']; ?>" name="instructor" />
            <div class="form-group">
              <label>Instruction: </label>
              <textarea class="form-control" name="instruction" id="instruction" placeholder="Any instructor-specific instructions to pass on to the next planner..." required><?php echo $instruction_content; ?></textarea>
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
        <?php
        if(isset($_GET['id']) && $_GET['id']>0) {
          ?>
          <button type="button" class="btn btn-danger" onclick="deleteInstruction('<?php echo $instruction_id; ?>')">Delete</button>
          <?php
        }
        ?>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>

</form>

<script>
function deleteInstruction(id) {
  if(confirm('Are you sure you want to delete this instruction?')) {
    window.location = "../php/deleteInstructorInstruction.php?id="+id;
  }
}
</script>