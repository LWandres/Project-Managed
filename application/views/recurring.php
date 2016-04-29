<!doctype html>
<html>
<head>

  <title>Dashboard</title>
  <link rel="stylesheet" type="text/css" href="/assets/dashboard.css">
    <script type="text/javascript" src='http://code.jquery.com/jquery-1.10.2.min.js'></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script>
              $(document).ready(function(){
                $( "#tabs" ).tabs();
                $('#Recur').on('change',function(){
                    if( $(this).val()==="Yes"){
                        $("#Recur2").show()
                        }
                        else{
                          $("#Recur2").hide()
                        }
                      });
                    });

    </script>
</head>
<div id="background">
  <body>
    <div id="container">
      <div id="header">
      <h1>Project Managed.</h1>
        <?php $session_id = $this->session->userdata('id');?>
        <ul>
          <li><a href="/access/logout">Logout</a></li>
          <li><a href="#">Resources </a></li>
          <li><a href="/access/profile/<?=$session_id?>">Edit Profile</a></li>
          <li><a href="/">Home </a></li>
        </ul>
      </div>

      <h2>Welcome Back <?=$userinfo?></h2>

      <div id="tabs">
        <ul>
          <li><a href="#tabs-1">Active Meetings</a></li>
          <li><a href="#tabs-2">New Meeting</a></li>
          <li><a href="#tabs-3">Archive</a></li>
          <li><a href="#tabs-4">Follow Ups</a></li>
        </ul>
        <div id="tabs-1">
          <h3>Meeting Recurring History</h3>
          <a href="/display/loaddashboard">Back to other meetings</a>
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
                  <td><?=$recurring['date']?></td>
                  <td><a href="/admin/viewnotes/<?=$recurring['id']?>">View Notes</a></td>
                  <td><?=$recurring['id']?></td>
                  <td><a href="/admin/archive/<?=$recurring['id']?>">Archive</a></td>
                </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>

        <div id="tabs-2">
          <h3>New Meeting Room</h3>
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
                    <option value="Meeting1">Meeting 1</option><br>
                    <option value="New">New</option><br>
                  </select><br>
              </div>

              <label>Project Name:</label><input type="text" name="project"><br>
              <label>Meeting Name:</label><input type="text" name="meeting"><br>
              <label>Meeting Date:</label><input type="date" name="meetingdate"><br>
              <label>Start Time:</label><input type="time" name="start" step=900><br>
              <label>End Time:</label> <input type="time" name="end" step=900><br>
              <label>Objectives:</label><textarea name="objectives" rows="10" cols="30"></textarea><br>
              <label>Goals:</label><br><textarea name="goals" rows="10" cols="30"></textarea><br>
              <label>Participants:</label><br> <textarea name="participants" rows="10" cols="30"></textarea><br>
              <label>Agenda:</label><br> <textarea name="agenda" rows="10" cols="30"></textarea><br>
              <label>Agenda Template:</label>
              <select name="template">
                <option value="template">Template 1</option><br>
              </select>
              <input type="submit" name="newmeeting" value="Let's do this"</input>
            </form>
            </div>
          </div>

        <div id="tabs-3">
          <table class="table table-hover">
              <h3>Archived Meetings</h3>
              <thead>
                <th>Meeting</th>
                <th>Project</th>
                <th>Date</th>
                <th>View</th>
                <th>Recurring?</th>
                <th>Move to Current</th>
              </thead>
              <tbody>
              <?php foreach ($archived as $archive){
                if($archive !== null){ ?>
                      <tr>
                        <td><?=$archive['name']?></td>
                        <td><?=$archive['projectname']?></td>
                        <td><?=$archive['date']?></td>
                        <td><a href="/admin/viewnotes/<?=$archive['meetings_id']?>">View Notes</td>
                        <td><a href="/main/recurring/<?=$archive['recurringseries_id']?>">Yes</td>
                        <td><a href="/admin/active/<?=$archive['meetings_id']?>">Move</td>
                      </tr>
                  <?php  }
                }?>
              <?php if($archived ==null){?>
                        </tbody>
                        </table>
                        <?php echo "You do not have any meetings archived.";
                      }?>
                </tbody>
                </table>
        </div>

        <div id="tabs-4">
          <table class="table table-hover">
              <h3>Upcoming Follow Ups</h3>
              <p>These are your outstanding follow ups</p>
              <thead>
                <th>Project</th>
                <th>Meeting</th>
                <th>Follow Up</th>
                <th>Due Date</th>
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
                  <td><a href="/main/viewnotes/<?=$incomplete['id']?>">Jump to Notes</td>
                  <td><form action="/main/completefollow/<?=$incomplete['follow_id']?>" method="post">
                        <input type="submit" value="Completed"></input>
                      </form></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>

            <table class="table table-hover">
                <h3>Completed Follow Ups</h3>

                <p>Congrats! You've completed these follow ups for the week.</p>
                <thead>
                  <th>Project</th>
                  <th>Meeting</th>
                  <th>Follow Up</th>
                  <th>Due Date</th>
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
                        <td><a href="/main/viewnotes/<?=$complete['id']?>">Jump to Notes</td>
                        <td><form action="/main/incompletefollow/<?=$complete['follow_id']?>" method="post">
                              <input type="submit" value="Incomplete"></input>
                            </form></td>
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
