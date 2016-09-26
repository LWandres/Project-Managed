<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400" rel="stylesheet">

    <!-- JS -->
    <script src="/assets/js/jquery-2.2.4.min.js"></script>
    <script src="/assets/js/jquery-3.1.0.min.js"></script>
    <script src="/assets/js/jquery-ui.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="/assets/js/dashboard.js"></script>

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

          <!-- Active Meetings tab -->
          <div id="tabs-1">
            <input class="searchinput" type="text" id="search" type="text" placeholder="search for meetings here">
            <h3>Active Meetings You Own</h3>
            <table class="table table-hover">
                <thead>
                  <th data-sort="Meeting">Meeting <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Project">Project <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Date">Date <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th data-sort="Time">Time <i class="fa fa-sort" aria-hidden="true"></i></th>
                  <th>Edit</th>
                  <th>Notes</th>
                  <th data-sort="Recurring?">Recur</th>
                  <th>Archive</th>
                  <th>Followups</th>
                </thead>
                <tbody>
              <?php  foreach($activeowned as $ownmeeting){?>
                <tr>
                  <td><a href="/admin/meetinginfo/<?= $ownmeeting['meetings_id']?>"><?= $ownmeeting['name']?></a></td>
                  <td><?= $ownmeeting['projectname']?></td>
                  <td><?= date('m/d/Y',strtotime($ownmeeting['date']))?></td>
                  <td><?= date("g:i a", strtotime($ownmeeting['start']))?> - <?= date("g:i a", strtotime($ownmeeting['end']))?></td>
                  <td><a href="/admin/edit/<?= $ownmeeting['id']?>">Edit</td>
                  <td><a href="/admin/viewnotes/<?= $ownmeeting['id']?>">Notes</td>
                <?php if($ownmeeting['recur'] !=="Yes"){ ?>
                  <td><?= $ownmeeting['recur']?></td>
                <?php } else{ ?>
                  <td><a href="/main/recurring/<?= $ownmeeting['meetings_id']?>"><?= $ownmeeting['recur']?></td>
                <?php } ?>
                  <td><a href="/admin/archive/<?= $ownmeeting['meetings_id']?>"><button class="btn btn-primary">Archive</button></a></td>
                  <td><a href="/apis/emailfollowup/<?= $ownmeeting['meetings_id']?>"><button class="btn btn-primary">Email</button></a></td>
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
                          <td><a href="/admin/meetinginfo/<?=$attend['id']?>"><?= $attend['name']?></td>
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
        <!-- End Active Meetings tab -->

        <!-- New Meetings tab -->
          <div id="tabs-2">
            <h3>New Meeting Workspace</h3>
            <div id="new_meeting_form">

              <form action="/main/new_meeting" method="post">
                <input type="hidden" name="owner" value="<?=$userid?>"></input>
                <label>Recurring?:</label>
                <select name="Recur" id="Recur">
                  <option value="No">No</option><br>
                  <option value="Yes">Yes</option><br>
                </select>

                <div class="Recurbox">
                  <label>Your Recurrings:</label>
                    <select id="Recur2" name="Recur2">
                        <option value="">n/a</option><br>
                        <option value="New">New</option><br>

                    <?php foreach($recurring as $recur) {  ?>
                        <option value="<?=$recur['name']?>"><?=$recur['name']?></option><br>
                    <?php } ?>
                    </select>
                </div>

                <div class="new_meet_float">
                    <label>Project Name:</label><input id="newprojectname" type="text" class="format" name="project" value="" required><br>
                    <label>Meeting Name:</label><input id="meetingname" type="text" class="format"  name="meeting" value="" required><br>
                    <label>Meeting Date:</label><input id="meetingdate" type="date" class="format"  name="meetingdate" value="" required><br>
                    <label>Starting Time:</label><input id="meetingstart" type="time"  class="format"  name="start" step=900 value="" required><br>
                    <label>Ending Time:</label><input id="meetingend" type="time" class="format"  name="end" step=900 value="" required><br>
                </div>
                <label>Objectives:(required)</label><textarea id="objectives" class="objectives richtext" name="objectives"></textarea><br>
                <label>Goals:(required)</label><br><textarea id="goals" class="goals richtext" name="goals" ></textarea><br>
                <label>Participants:</label><br>
                    <button type="button" id ="participantgrid" class="add_field_button btn btn-primary">Add New Participant</button> <button type="button" id="copy" class="btn btn-primary">OR Copy/Paste Participants from Gmail or Outlook</button>
                    <div class="input_fields_wrap">
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

                <div id="copypaste">
                    <p><span class="attendeenotes"> NOTE: For Gmail, place your cursor in the "To" line & press shift and the up arrow.</p></span>
                    <p><span class="attendeenotes"> Then copy recipients using CTRL + C and paste into your meeting agenda.</p></span>
                    <div id="error">Hmm. Your participant info not in a familiar format. Please reformat using the copy/paste instructions above, or use the "Add New Particpants" button instead. </div>
                    <textarea id="participants" name="participants"></textarea><br>
                </div><br><br>

                <label>Agenda:(required)</label><br><textarea id="agenda" class="agenda richtext" name="agenda"></textarea><br>
                <input id="newmeeting" type="submit" disabled="disabled" name="newmeeting" value="Let's do this"></input>
              </form>
             </div>
         </div>
         <!-- End New Meetings tab -->

         <!-- Archive tab -->
          <div id="tabs-3">
              <input class="searchinput" type="text" id="search2" type="text" placeholder="search for meetings here">
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
          <!-- Archives tab ends-->

          <!-- Follow Ups tab -->
          <div id="tabs-4">
              <input class="searchinput" type="text" id="search3" type="text" placeholder="search for meetings here"></input>
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
        <!-- Follow Ups Tab ends-->
        </div> <!--closes tabs-->
    </div> <!--closes dashboard container-->
  </body>
</div> <!--closes background-->
</html>
