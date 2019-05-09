<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Relationship advisor</title>
        <link href="./StyleSheet.css" rel="stylesheet" type="text/css" />
        <!--<link rel="stylesheet" href="stylesheet-pure-css.css">-->
    </head>
    <body>
        <?php
        include 'ChoiceTable.php';
        
        $choiceIdx = 1;
        $language = 'English';
        $showAnswer = 0;
        
        if(isset( $_GET['choice'] ))
        {
            $choiceIdx = $_GET['choice'];
        }
        if(isset( $_GET['language'] ))
        {
            $language = $_GET['language'];
        }
        if(isset( $_GET['answer'] ))
        {
            $showAnswer = $_GET['answer'];
        }
        
        $reset = 'Reset';
        $title = 'RELATIONSHIP ADVISOR';
        $english = 'English';
        $slovenian = 'Slovenian';
        
        if($language == 'Slovenian')
        {
            $reset = 'Na začetek';
            $title = 'REŠEVANJE ODNOSOV';
            $english = 'Angleško';
            $slovenian = 'Slovensko';
        }
        ?>
        
        <form method="get">
            <input id="choice" type='hidden' name='choice' value='<?php echo $choiceIdx; ?>'>
            <input id="answer" type='hidden' name='answer' value='<?php echo $showAnswer; ?>'>
            
            <button class='reset' type='submit' onclick="ResetAll()"><?php echo $reset; ?></button>
            
            <input id="English" type="radio" name="language" value="English" <?php echo ($language=='English')?'checked':'' ?> onchange="this.form.submit();" />
            <label for="English"><?php echo $english; ?></label>
            
            <input id="Slovenian" type="radio" name="language" value="Slovenian" <?php echo ($language=='Slovenian')?'checked':'' ?> onchange="this.form.submit();" />
            <label for="Slovenian"><?php echo $slovenian; ?></label>
            
            <div style="width: 100%; text-align: center;">
                <div class="title"><?php echo $title; ?></div>
                <div style="display: inline-block;width: 100%;">    
                    <!--<div class="boxtext">Pick one:</div>-->
                    <!--<div class="repeater">-->
                        <?php
                            FillTableWithChildChoices($choiceIdx, $language, $showAnswer);
                        ?>
                    <!--</div>-->
                </div>
                <div class="footer">
                    <br />
                </div>
                <img alt="Center za kakovost odnosov" src="logo.png" />
            </div>
        </form>
        
        <script>
        function ResetAll()
        {
            document.getElementById('choice').value = 1;
            document.getElementById('answer').value = 0;
            
            var checkboxes = document.getElementsByTagName('input');

            for (var i=0; i<checkboxes.length; i++) {
                if (checkboxes[i].type === 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
        </script>
    </body>
</html>
