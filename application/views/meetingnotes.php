<html>
<head>
    <title>Meeting Agenda</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="/assets/css/meetingagenda.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <!-- JS -->
    <script type="text/javascript" src="/assets/js/jquery-3.1.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>

    <script>
        $(document).ready(function() {
            var max_fields = 30; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div><tr><td><select name="owner[]"><?php foreach($attendees as $attendee){ ?><option value="<?=$attendee['
                        id ']?>"><?= $attendee['
                        first ']." ".$attendee['
                        last ']?></option></td>"<?php }?>"<td><input type="text" name="follow[]"></td><td><input type="date" name="due[]"></td><td><select name="status[]"><option value="Yes">Yes</option><option value="No">No</option></select></td><td></tr><a href="#" class="remove_field">Remove</a></div>'
                    );
                }
            });

            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })

            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div><tr><td><select name="owner[]"><?php foreach($attendees as $attendee){ ?><option value="<?=$attendee['
                        id ']?>"><?= $attendee['
                        first ']." ".$attendee['
                        last ']?></option></td>"<?php }?>"<td><input type="text" name="follow[]"></td><td><input type="date" name="due[]"></td><td><select name="status[]"><option value="No">No</option><option value="Yes">Yes</option></select></td><td></tr><a href="#" class="remove_field">Remove</a></div>'
                    );
                }
            });

        });

        //rich text editor
        tinymce.init({
            selector: 'textarea',
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

<body>
    </div>
    <div id="background">
        <div class="email">
            <form method="post" action="/apis/sendemail/<?=$agenda['id']?>">
                <input type="submit" name="email" value="Email Notes to Participants"></input>
            </form>

            <form method="post" action="/apis/createPDF/<?=$agenda['id']?>">
                <input type="submit" name="PDF" value="Create PDF"></input>
            </form><br><br>
        </div>


        <div id="notes-container">

            <div id="agenda">
                <a href="/display/loaddashboard" name="back">Back to other meetings</a>

                <h2 id="meetingname">  <?=$agenda['name']?></h2>
                <ul id="date">
                    <li><?=date('l F d Y',strtotime($agenda['date']))?></li>
                    <li><?= date("g:i a", strtotime($agenda['start']))?> - <?= date("g:i a", strtotime($agenda['end']))?></li>
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

                    <form class="attendees" method="post" action="/main/attendance/<?=$attendees[0]['meetings_id']?>">
                        <?php foreach($attendees as $attendee){?>
                        <input type="checkbox" name="attendee[]" value="<?= $attendee['users_id']?>"><?=$attendee['first']." ".$attendee['last']?></input><br>
                        <?php }?>
                    </form>
                </div>

                <h4>Agenda</h4>
                <div class="Agenda">
                    <?=$agenda['agenda']?>
                </div>

                <h4>Meeting Follow Ups</h4>

                <div class="FollowUps">
                    <form class="followtable" method="post" action="/main/updatefollows/<?=$agenda['id']?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Owner</th>
                                    <th>Follow Up</th>
                                    <th>Due</th>
                                    <th>Done?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(empty($followups)){ echo "This meeting doesn't have any follow ups";
                                } ?>
                                    <?php if(!empty($followups)){
                                  foreach($followups as $follow){ ?>
                                    <tr>
                                        <td><?=$follow['first']?><?=$follow['last']?></td>
                                        <td><?=$follow['followup']?></td>
                                        <td><?= date('F m, Y',strtotime($follow['duedate']))?></td>
                                        <td><?=$follow['followstatus']?></td>
                                    </tr>
                                    <?php }
                                }?>
                </div>
            </div>
            </table>
            </form>
        </div>

    </div>
</body>
</html>
