<html>
<head>
  <title>Dashboard</title>

  <?php echo 'current' . phpversion(); ?>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- JS -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

    <script>
              $(document).ready(function(){
                $("#tabs").tabs();
                $("#Recur2").show()

                $('#Recur').on('change',function(){
                    if( $(this).val() === "Yes"){
                        $("#Recur2").show()
                        $(this).addClass("ui-state-active");
                    }
                    else{
                      $("#Recur2").hide()
                    }
                });
                $("#search,#search2,#search3").keyup(function () {
                    var value = this.value.toLowerCase().trim();

                    $("table tr").each(function (index) {
                      if (!index) return;
                      $(this).find("td").each(function () {
                          var id = $(this).text().toLowerCase().trim();
                          var not_found = (id.indexOf(value) == -1);
                          $(this).closest('tr').toggle(!not_found);
                          return not_found;
                      });
                    });
                });

                $('th').click(function(){
                      var table = $(this).parents('table').eq(0)
                      var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
                      this.asc = !this.asc
                      if (!this.asc){rows = rows.reverse()}
                      for (var i = 0; i < rows.length; i++){table.append(rows[i])}
                })
                  function comparer(index) {
                      return function(a, b) {
                          var valA = getCellValue(a, index), valB = getCellValue(b, index)
                          return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
                      }
                  }
                  function getCellValue(row, index){ return $(row).children('td').eq(index).html() }
              });

              tinymce.init({
                  selector:'.richtext',
                  browser_spellcheck: true,
                  plugins: 'link advlist code spellchecker paste textcolor colorpicker visualchars wordcount contextmenu visualblocks insertdatetime hr searchreplace',
                  advlist_bullet_styles: "default circle disc square",
                  menubar: "edit view insert",
                  toolbar: 'undo redo |  bold italic | bullist numlist | styleselect | alignleft aligncenter alignright | code | spellchecker | paste | forecolor backcolor | visualchars | link | visualblocks | insertdatetime | searchreplace | fontselect |  fontsizeselect',
                  fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
               });

    </script>
</head>
<!-- including header partial -->
<?php include_once("header2.php"); ?>
<!-- end header partial -->
</div><!-- closes container from header -->
  <body>
    <div id="background">
      <div id="dashboard-container">

        <h2>Welcome Back <?=$userinfo?></h2>
        <div id="tabs">
          <ul>
            <li><a href="#tabs-1">Active Meetings</a></li>
            <li><a href="#tabs-2">New Meeting</a></li>
            <li><a href="#tabs-3">Archive</a></li>
            <li><a href="#tabs-4">Follow Ups</a></li>
          </ul>

          <div id="tabs-1">
            <input class="searchinput" type="text" id="search" type="text" placeholder="Search here">

            <h3>Active Meetings You Own</h3>
            <table class="table table-hover">
                <thead>
                  <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Project">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Date">Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th>Edit</th>
                  <th>Notes</th>
                  <th data-sort="Recurring?">Recur</th>
                  <th>Archive</th>
                  <th>Followups</th>
                </thead>
                <tbody>
              <?php  foreach($activeowned as $ownmeeting){?>
                <tr>
                  <td><a href="/admin/meetinginfo/<?= $ownmeeting['id']?>"><?= $ownmeeting['name']?></a></td>
                  <td><?= $ownmeeting['projectname']?></td>
                  <td><?= date('m/d/Y',strtotime($ownmeeting['date']))?></td>
                  <td><a href="/admin/edit/<?= $ownmeeting['id']?>">Edit</td>
                  <td><a href="/admin/viewnotes/<?= $ownmeeting['id']?>">Notes</td>
                <?php if($ownmeeting['recur'] !=="Yes"){ ?>
                  <td><?= $ownmeeting['recur']?></td>
                <?php } else{ ?>
                  <td><a href="/main/recurring/<?= $ownmeeting['id']?>"><?= $ownmeeting['recur']?></td>
                <?php } ?>
                  <td><a href="/admin/archive/<?= $ownmeeting['id']?>"><button class="btn btn-primary">Archive</button></a></td>
                  <td><a href="/apis/emailfollowup/<?= $ownmeeting['id']?>"><button class="btn btn-primary">Email</button></a></td>
                </tr>
              <?php } ?>
                </tbody>
              </table>
              <hr>

              <h3>Upcoming Meetings</h3>
              <?php if(empty($activeattend)){
                  ?><p><?="You do not have additional meetings."?><p>
              <?php  } ?>
              <?php if(!empty($activeattend)){ ?>
                    <table class="table table-hover">
                        <thead>
                          <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                          <th data-sort="Project">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                          <th data-sort="Date">Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                          <th>Edit</th>
                          <th data-sort="Recurring?">Recurring? <i class="fa fa-sort" aria-hidden="true"></i></th>
                          <th>Archive</th>
                        </thead>
                        <tbody>
                    <?php foreach($activeattend as $attend){ ?>
                        <tr>
                          <td><a href="/admin/meetinginfo"><?= $attend['name']?></td>
                          <td><?= $attend['projectname']?></td>
                          <td><?= date('m/d/Y',strtotime($attend['date']))?></td>
                          <td><a href="/admin/edit/<?= $attend['id']?>">Edit Meeting</td>
                          <td><a href="/main/recurring/<?= $attend['id']?>"><?= $attend['recur']?></td>
                          <td><a href="/admin/archive/<?= $attend['id']?>">Archive</td>
                        </tr>
                      <?php } ?>
                        </tbody>
                      </table>
                      <?php } ?>
            </div>

          <div id="tabs-2">
            <h3>New Meeting Workspace</h3>
            <div id="new_meeting_form">
              <form action="/main/new_meeting" method="post">
                <input type="hidden" name="owner" value="<?=$userid?>">
                <label>Recurring?:</label>
                <select name="Recur" id="Recur">
                  <option value="No">No</option><br>
                  <option value="Yes">Yes</option><br>
                </select><br>

                <div id="Recur2" style="display:none;">
                  <label>Your Recurrings:</label>
                    <select id="Recur2" name="Recur2">
                        <option value="">n/a</option><br>
                        <option value="New">New</option><br>
                    <?php foreach($recurring as $recur) { ?>
                        <option value="<?=$recur['name']?>"><?=$recur['name']?></option><br>
                    <? } ?>
                    </select><br>
                </div>

                <label>Project Name:</label><input type="text" name="project"><br>
                <label>Meeting Name:</label><input type="text" name="meeting"><br>
                <label>Meeting Date:</label><input type="date" name="meetingdate"><br>
                <label>Starting:</label><input type="time" name="start" step=900><br>
                <label>Ending:</label><input type="time" name="end" step=900><br>
                <label>Objectives:</label><textarea class="objectives richtext" name="objectives"></textarea><br>
                <label>Goals:</label><br><textarea class="goals richtext" name="goals" ></textarea><br>
                <label>Participants:</label><br> <textarea name="participants"></textarea><br>
                <label>Agenda:</label><br> <textarea class="agenda richtext" name="agenda"></textarea><br>
                <input type="submit" name="newmeeting" value="Let's do this"></input>
              </form>
              </div>
            </div>

          <div id="tabs-3">
              <input class="searchinput" type="text" id="search2" type="text" placeholder="Search here">

                <h3>Archived Meetings</h3>
                <table class="table table-hover">
                <thead>
                  <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Project">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Date">Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th>View</th>
                  <th data-sort="Recurring?">Recurring? <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th>Move to Current</th>
                </thead>
                <tbody>
                <?php foreach ($archived as $archive){
                  if($archive !== null){ ?>
                        <tr>
                          <td><?=$archive['name']?></td>
                          <td><?=$archive['projectname']?></td>
                          <td><?=date('m/d/Y',strtotime($archive['date']))?></td>
                          <td><a href="/admin/viewnotes/<?=$archive['meetings_id']?>">View Notes</td>
                          <td><?=$archive['recur']?></td>
                          <td><a href="/admin/active/<?=$archive['meetings_id']?>">Move</td>
                        </tr>
                    <?php  } ?>
                <?php  } ?>
                <?php if($archived == null){?>
                          </tbody>
                          </table>
                          <?php echo "You do not have any meetings archived.";
                      }?>
                  </tbody>
                  </table>
          </div>

          <div id="tabs-4">

              <input class="searchinput" type="text" id="search3" type="text" placeholder="Search here">
                <h3>Upcoming Follow Ups</h3>
                <p>These are your outstanding follow ups</p>
                  <table class="table table-hover">
                    <thead>
                      <th data-sort="Meeting">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                      <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                      <th data-sort="Meeting">Follow Up <i class="fa fa-sort" aria-hidden="true"></i></th>
                      <th data-sort="Meeting">Due Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                      <th>Source Notes</th>
                      <th>Complete?</th>
                    </thead>
                    <tbody>

                  <?php   foreach($userfollowups as $incomplete){ ?>
                      <tr>
                        <td><a href="/admin/meetinginfo/<?=$incomplete['id']?>"><?=$incomplete['projectname']?></td>
                        <td><a href="/main/viewagenda/<?=$incomplete['id']?>"><?=$incomplete['name']?></td>
                        <td><?=$incomplete['followup']?></td>
                        <td><?=date('m/d/Y',strtotime($incomplete['duedate']))?></td>
                        <td><a href="/admin/viewnotes/<?= $incomplete['id']?>">Jump to Notes</td>
                        <td><form action="/main/completefollow/<?=$incomplete['follow_id']?>" method="post">
                              <input type="submit" value="Completed"></input>
                            </form>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <hr>

                  <h3>Completed Follow Ups</h3>
                  <table class="table table-hover">
                      <p>Congrats! You've completed these follow ups for the week.</p>
                      <thead>
                        <th data-sort="Project">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th data-sort="Follow Up">Follow Up <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th data-sort="Due Date">Due Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                        <th>Source Notes</th>
                        <th>Completed</th>
                      </thead>
                      <tbody>
                          <?php   foreach($completefollows as $complete){ ?>
                            <tr>
                              <td><a href="/admin/meetinginfo/<?=$complete['id']?>"><?=$complete['projectname']?></td>
                              <td><a href="/main/viewagenda/<?=$complete['id']?>"><?=$complete['name']?></td>
                              <td><?=$complete['followup']?></td>
                              <td><?=date('m/d/Y',strtotime($complete['duedate']))?></td>
                              <td><a href="/admin/viewnotes/<?= $complete['id']?>">Jump to Notes</a></td>
                              <td>
                                  <form action="/main/incompletefollow/<?=$complete['follow_id']?>" method="post">
                                    <input type="submit" value="Incomplete"></input>
                                  </form>
                              </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
          </div>
          </div>
        </div>
  </body>
</div>
</html>
