$(document).ready(function(){
    $("#tabs").tabs();
    $(".Recurbox").show();
    $("#copypaste").hide();
    $("#error").hide();

    $('#Recur').on('change',function(){
        if( $(this).val() === "Yes"){
            $(".Recurbox").show()
        }
        else{
          $(".Recurbox").hide()
        }
    });

    $('#Recur2').on('change',function(){
        var recurmeeting = $(this).val();
        $("#meetingname").attr("value", recurmeeting);
        '<?php foreach($recurring as $recur) { ?>'
            if (recurmeeting == '<?=$recur["name"]?>'){
                $("#meetingname").attr("value", recurmeeting);
                $("#newprojectname").attr("value", '<?=$recur["projectname"]?>');
                $("#meetingdate").attr("value", '<?php echo date("Y-m-d", strtotime($recur["date"]))?>');
                $("#meetingstart").attr("value", '<?=$recur["start"]?>');
                $("#meetingend").attr("value", '<?=$recur["end"]?>');
           }
        '<?php } ?>'
    });

    var max_fields = 30; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap"); //Fields wrapper
    var add_button = $(".add_field_button"); //Add button ID

    var x = 1; //initial text box count
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

    //participants jQuery
    $('#copy').click(function(){
        $('#copypaste').toggle();
        $('.input_fields_wrap').toggle();
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

      //disables the posting button for a new meeting
      setInterval(function () {
          var objectives = tinyMCE.get('objectives').getContent();
          var goals = tinyMCE.get('goals').getContent();
          var agenda = tinyMCE.get('agenda').getContent();
          var participants =  $("#participants").val();

          if (participants != '' && participants.includes("<" && ">") !== true){
              var valid_participants = false;
              $("#error").show();
          } else{
              var valid_participants = true;
              $("#error").hide();
          }

          if( ((objectives && goals && agenda) != '') && ((objectives && goals && agenda) != null) && (valid_participants == true)){
              $("#newmeeting").removeAttr("disabled");
              $("#newmeeting").val("Let's do this!");
          } else {
              $("#newmeeting").attr("disabled", "disabled");
              $("#newmeeting").val("Please fill out all required fields");
          }
        }, 1000 ); //Runs every second

    });

    tinymce.init({
        selector: 'textarea',
        browser_spellcheck: true,
        plugins: 'link advlist spellchecker paste textcolor colorpicker wordcount contextmenu hr',
        advlist_bullet_styles: "default circle disc square",
        menubar: "edit view insert",
        toolbar: 'undo redo |  bold italic | bullist numlist | styleselect | alignleft aligncenter alignright | spellchecker | paste | forecolor backcolor | link | fontselect |  fontsizeselect',
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    });
