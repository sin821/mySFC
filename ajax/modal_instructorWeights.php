<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Instructor Weights</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8">

        	<table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Instructor</th>
                        <th>Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM tbl_instructors WHERE 1 ORDER BY instructor_initials ASC";
                    $result = mysqli_query($link, $query);
                    while($row = mysqli_fetch_array($result)) {
                        $instructor_initials = $row['instructor_initials'];
                        $instructor_weight = $row['instructor_weight'];
                        ?>
                        <tr>
                            <td><?php echo $instructor_initials; ?></td>
                            <td><?php echo $instructor_weight; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
    </div>
</div>