<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include 'Choice.php';

function FillTableWithChildChoices($id,$language,$showAnswer)
{
    $db = new SQLite3('RelationshipAdvice.db') or die('Unable to open database');

    $statement = $db->prepare('SELECT * FROM Choices WHERE id = :id;');
    $statement->bindValue(':id', $id);

    $result = $statement->execute();
    
    $choice = $result->fetchArray();

    $childChoiceList = $choice['childChoiceList'];
    $multipleChoiceChildren = $choice['allowMultipleChildChoices'];

    if (empty($childChoiceList))
    {
        return;
    }
    
    $pick = 'Pick one:';
    if($language == 'Slovenian')
    {
        $pick = 'Izberi eno:';
    }
    if($multipleChoiceChildren)
    {
        $pick = 'Pick any:';
        if($language == 'Slovenian')
        {
            $pick = 'Izberi poljubno:';
        }
    }
    
    echo "<div class='boxtext'>$pick</div>";
    echo "<div class='repeater'>";

    $choiceList = []; // initialize empty array
    
    $choiceCheckbox = [];
    if( !empty($_GET['check']) && is_array($_GET['check']) )
    {
        $choiceCheckbox = $_GET['check'];
    }
    
    $childChoices = $db->query("SELECT * FROM Choices WHERE ID IN ( $childChoiceList )") or die('Query failed');
    while ($row = $childChoices->fetchArray())
    {
        $idx = $row['id'];

        $childChoice = new Choice($idx);
        $childChoice->AnswerWeight[0] = $row["weight1"];
        $childChoice->AnswerWeight[1] = $row["weight2"];
        $childChoice->AnswerWeight[2] = $row["weight3"];
        $childChoice->AnswerWeight[3] = $row["weight4"];
        $childChoice->AnswerWeight[4] = $row["weight5"];

        $statement = $db->prepare('SELECT * FROM Localization WHERE id = :id;');
        $statement->bindValue(':id', $idx * 10);
        $result = $statement->execute();
        $choice = $result->fetchArray();
        $description = $choice[$language];
        
        $choiceList[$idx] = $childChoice; // append element to array
        
        if($multipleChoiceChildren)
        {
            $checked = '';
            if( in_array($idx, $choiceCheckbox) )
            {
                $checked = 'checked';
            }
            echo "<input id='check$idx' type='checkbox' name='check[]' value='$idx' $checked /><label for='check$idx'>$description</label>";
        }
        else
        {
            echo "<button class='choice' type='submit' onclick='document.getElementById(\"choice\").value = $idx;'>$description</button>";
        }
        echo "<br />";
    }
    
    if(!$multipleChoiceChildren)
    {
        return;
    }
    
    $answerButton = 'Answer';
    if($language == 'Slovenian')
    {
        $answerButton = 'Odgovor';
    }
    echo "<button class='choice' type='submit' onclick='document.getElementById(\"answer\").value = 1;'>$answerButton</button>";
    
    $answer = '[Select one or more options and click on Answer]';
    if($language == 'Slovenian')
    {
        $answer = '[Izberi eno ali več možnosti in klikni na Odgovor]';
    }

    if( $showAnswer )
    {
        $answerWeight = [ 0, 0, 0, 0, 0 ];
        
        if(!empty($choiceCheckbox))
        {
            foreach($choiceCheckbox as $value)
            {
                for ($i = 0; $i < 5; ++$i)
                {
                    $answerWeight[$i] += $choiceList[$value]->AnswerWeight[$i];
                }
            }
        }
        $highest = 0;
        for ($i = 1; $i < 5; ++$i)
        {
            if ($answerWeight[$i] > $answerWeight[$highest])
            {
                $highest = $i;
            }
        }
        
        $statement = $db->prepare('SELECT * FROM Localization WHERE id = :id;');
        $statement->bindValue(':id', $id * 10 + $highest + 1);
        $result = $statement->execute();
        $choice = $result->fetchArray();
        $answer = $choice[$language];
    }

    echo "<div class='answer'>$answer</div>";
    
    echo "</div>";
}
