<head>
    <title>Dashboard</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/meetingagenda.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
</head>

<?php include_once("header2.php"); ?>

</div><!-- closes container from header -->
    <div id="background">
      <div id="dashboard-container">
            <h2>Welcome Back <?=$userinfo?></h2>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Active Meetings</a></li>
                    <!-- <li><a href="#tabs-2">New Meeting</a></li>
                    <li><a href="#tabs-3">Archive</a></li>
                    <li><a href="#tabs-4">Follow Ups</a></li> -->
                </ul>
                <div id="tabs-1">
                    <h3>Meeting Recurring History</h3>
                    <a href="/display/loaddashboard" name="back">Back to other meetings</a>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Meeting</th>
                                <th>Project</th>
                                <th>Date</th>
                                <th>Edit</th>
                                <th>Meeting ID</th>
                                <th>Archive</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recurrings as $recurring){ ?>
                            <tr>
                                <td><a href="/admin/meetinginfo/<?=$recurring['id']?>"><?=$recurring['name']?></a></td>
                                <td><?=$recurring['projectname']?></td>
                                <td><?=date('m/d/Y',strtotime($recurring['date']))?></td>
                                <td><a href="/admin/viewnotes/<?=$recurring['id']?>">View Notes</a></td>
                                <td><?=$recurring['id']?></td>
                                <td><a href="/admin/archive/<?=$recurring['id']?>">Archive</a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!--end recurring tabs div-->
            </div><!--end overall tabs div future implementation-->
        </div><!--end dashboard container-->
    </div><!--end background div-->
      <!-- JS -->
      <script async type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
      <script async type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
      <script>
          $(document).ready(function() {
              $("#tabs").tabs();
              $('#Recur').on('change', function() {
                  if ($(this).val() === "Yes") {
                      $("#Recur2").show()
                  } else {
                      $("#Recur2").hide()
                  }
              });
          });
      </script>
</body>
</html>
