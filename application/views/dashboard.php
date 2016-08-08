<html>
<head>
  <title>Dashboard</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <!-- JS -->
    <script type="text/javascript" src="/assets/js/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>

    <script>
              $(document).ready(function(){
                $("#tabs").tabs();
                $(".Recurbox").show()

                $('#Recur').on('change',function(){
                    if( $(this).val() === "Yes"){
                        $(".Recurbox").show()
                    }
                    else{
                      $(".Recurbox").hide()
                    }
                });

                var max_fields = 30; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID

                var x = 1; //initlal text box count
                $(add_button).click(function(e){ //on add input button click
                    e.preventDefault();
                    if(x < max_fields){ //max input box allowed
                        x++; //text box increment
                        $(wrapper).append('<div><tr><td><input type="text" name="first[]" class="participants"></td><td><input type="text" name="last[]" class="participants"></td><td><input type="text" name="email[]" class="participants"></td><td></tr><a href="#" class="remove_field">Remove</a></div>');
                    }
                });

                $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
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
                <input type="hidden" name="owner" value="<?=$userid?>"></input>
                <label>Recurring?:</label>
                <select name="Recur" id="Recur">
                  <option value="No">No</option><br>
                  <option value="Yes">Yes</option><br>
                </select><br>

                <div class="Recurbox">
                  <label>Your Recurrings:</label>
                    <select id="Recur2" name="Recur2">
                        <option value="">n/a</option><br>
                        <option value="New">New</option><br>
                    <?php foreach($recurring as $recur) { ?>
                        <option value="<?=$recur['name']?>"><?=$recur['name']?></option><br>
                    <?php } ?>
                    </select>
                </div>

                <label>Project Name:</label><input type="text" name="project"><br>
                <label>Meeting Name:</label><input type="text" name="meeting"><br>
                <label>Meeting Date:</label><input type="date" name="meetingdate"><br>
                <label>Starting:</label><input type="time" name="start" step=900><br>
                <label>Ending:</label><input type="time" name="end" step=900><br>
                <label>Objectives:</label><textarea class="objectives richtext" name="objectives"></textarea><br>
                <label>Goals:</label><br><textarea class="goals richtext" name="goals" ></textarea><br>
                <label>Participants:</label><br>
                <div class="input_fields_wrap">
                    <button class="add_field_button btn btn-primary">Add New Participant</button>
                    <table>
                        <thead>
                            <tr>
                                <th class="participantheader">First</th>
                                <th class="participantheader">Last</th>
                                <th class="participantheader">Email</th>
                            <tr>
                        </thead>
                        <tbody>
                            <div>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
                <label>OR Paste Participants from Gmail or Outlook Here:</label>
                    <p><span class="attendeenotes"> NOTE: For gmail, place your cursor in the "To" line & press shift and the up arrow.</p></span>
                    <p><span class="attendeenotes"> From there, copy recipients using CTRL + C and paste into your meeting agenda.</p></span>
                    <textarea name="participants"></textarea><br>

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
              <input class="searchinput" type="text" id="search3" type="text" placeholder="Search here"></input>
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
                            <?php }} ?>
                        </tbody>
                    </table>
          </div>
          </div>
        </div>
  </body>
</div>
</html>
