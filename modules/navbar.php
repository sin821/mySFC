<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"></a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Flying Tools <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <!--<li><a href="/flight/cadet_dashboard.php">Dashboard</a></li>-->
            <li><a href="/flight/calculator.php">Mass, Balance & Performance</a></li>
            <li><a href="/flight/cadet_latency.php">Track Progress</a></li>
            <li><a href="/flight/planning_request.php">Planning Request</a></li>
          </ul>
        </li>
        <?php
        if($_SESSION['role']!=0) { //for planners only
          ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              Planning Tools <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <?php
              if($_SESSION['role']==1) { //for planners only
                ?>
                <li><a href="/planning/planner_dashboard.php">Dashboard</a></li>
                <li><a href="/planning/solo_list.php">Solo List</a></li>
                <li><a href="/planning/update_TMS2_flightlist.php">Update TMS2 Latency</a></li>
                <li><a href="/planning/generate_TMS2_plan.php">Input Current TMS2 Plan</a></li>
                <!--<li><a href="/planning/latency_report.php">Latency Report</a></li>-->
                <li role="separator" class="divider"></li>
                <li><a href="/planning/cadet_list.php">Manage Cadets</a></li>
                <li><a href="/planning/instructor_list.php">Manage Instructors</a></li>
                <?php
              }
              if(($_SESSION['role']==2)||($_SESSION['role']==1)) { //for lead cadets and planners only
                ?>
                <li role="separator" class="divider"></li>
                <li><a href="/planning/leadcadet_dashboard.php">Lead Cadet Dashboard</a></li>
                <?php
              }
              ?>
            </ul>
          </li>
          <?php
        }
        ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            E-Learning <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="/e-learning/player.php">Mass Briefs</a></li>
            <li><a href="/e-learning/rpl_documents.php">RPL Documents</a></li>
          </ul>
        </li>
        <li><a href="/others/contact.php">Contact</a></li>
        <li><a href="/others/donation.php">Donate</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?> <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="../user_options/update_details.php">Update User Details</a></li>
            <li><a href="../user_options/change_password.php">Change Password</a></li>
            <li><a href="../php/logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>