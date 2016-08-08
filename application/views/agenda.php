<html>
<head>
  <title>Meeting Agenda</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/meetingagenda.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <!-- JS -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {

          var max_fields      = 30; //maximum input boxes allowed
          var wrapper         = $(".input_fields_wrap"); //Fields wrapper
          var add_button      = $(".add_field_button"); //Add button ID

          var x = 1; //initlal text box count
          $(add_button).click(function(e){ //on add input button click
              e.preventDefault();
              if(x < max_fields){ //max input box allowed
                  x++; //text box increment
                  $(wrapper).append('<div><tr><td><select name="owner[]"><?php foreach($attendees as $attendee){ ?><option value="<?=$attendee['id']?>"><?= $attendee['first']." ".$attendee['last']?></option></td>"<?php }?>"<td><input type="text" name="follow[]"></td><td><input type="date" name="due[]"></td><td><select name="status[]"><option value="Yes">Yes</option><option value="No">No</option></select></td><td></tr><a href="#" class="remove_field">Remove</a></div>');
              }
          });

          $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
              e.preventDefault(); $(this).parent('div').remove(); x--;
          })

      });
    </script>
</head>
<body>
<!-- including header partial -->
<?php include_once("header2.php"); ?>
<!-- end header partial -->
</div><!--intentional div for formatting purposes -->
<div id="background">
      <div class="email">
        <form method="post" action="/apis/createPDF/<?=$agenda['id']?>">
          <input type="submit" name="PDF" value="Create PDF"</input>
        </form>
        <form method="post" action="/apis/sendemail/<?=$agenda['id']?>"</input>
          <input type="submit" name="email" value="Email Agenda to Participants">
        </form>
      </div>

    <div id="notes-container">
      <div id="agenda">
        <a href="/display/loaddashboard" name="back">Back to other meetings</a>
        <h2 id="meetingname">  <?=$agenda['name']?></h2>
            <ul id="date">
              <li><?=date('l F m Y',strtotime($agenda['date']))?></li>
              <li>(<?=date('h:m A',strtotime($agenda['start']))?></li> - <?= date('h:m A',strtotime($agenda['end']))?>)</li>
            </ul>

        <h4>Objectives</h4>
            <div class="objectives">
              <?=$agenda['objective']?>
            </div>

        <h4>Goals</h4>
            <div class="goals">
                <?=$agenda['goals']?>
            </div>

        <h4>Attendees</h4>
            <div class="attendees">
                  <?php foreach($attendees as $attendee){ ?>
                    <input type="checkbox" name="attendee[]" value="<?= $attendee['users_id']?>"><?=$attendee['first']." ".$attendee['last']?></input><br>
                    <?php }?>
            </div>

        <h4>Agenda</h4>
            <div class="Agenda">
                <?=$agenda['agenda']?>
            </div>

        <h4>Meeting Follow Ups</h4>
            <div class="FollowUps">
                  <form class="followtable" method="post" action="/main/updatefollows/<?=$agenda['id']?>">
                        <div class="input_fields_wrap">
                          <button class="add_field_button">Add New Followup</button>
                             <input type="hidden" name="meetingid" value="<?=$agenda['id']?>"></input>
                             <input type="submit" name="updatefollowup" value="Save Follow Ups"></input>
                              <table>
                                <thead>
                                  <tr>
                                    <th>Owner</th>
                                    <th>Follow Up</th>
                                    <th>Due</th>
                                    <th>Done?</th>
                                  <tr>
                                </thead>
                                <tbody>
                                    <div>
                                     <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    </div>
                        </div> <!--close of the new follow up input div-->
                              <?php
                              if(empty($followups)){ echo "<tr>This meeting doesn't have any follow ups yet<tr>";
                              } ?>
                             <?php if(!empty($followups)){
                                     foreach($followups as $follow){ ?>
                                          <tr>
                                             <td><?=$follow['first']?> <?=$follow['last']?></td>
                                             <td><?=$follow['followup']?></td>
                                             <td><?= date('F m, Y',strtotime($follow['duedate']))?></td>
                                             <td><?=$follow['status']?></td>
                                           </tr>
                                         <?php }
                                   }?>
                               </tbody>
                             </table>
                       </form>
                 </div>
           </div>
       </div>
   </body>
</div>
</html>
