<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Choice
 *
 * @author Urban
 */
class Choice
{
    public $parentChoice;
    public $id = 0;
    public $childChoiceList;
    public $AnswerWeight;
    public $allowMultipleChildChoices;
    
    function __construct($id)
    {
        $this->id = $id;
    }
    
    public function isMultipleChoice()
    {
        if ($this->parentChoice === null)
        {
            return false;
        }

        return $this->parentChoice.allowMultipleChildChoices;
    }
}
