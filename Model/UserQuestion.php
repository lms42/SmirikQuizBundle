<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseUserQuestion;


/**
 * Skeleton subclass for representing a row from the 'users_questions' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class UserQuestion extends BaseUserQuestion {

  public function addAnswer($answer, $answer_text = false)
  {
    /**
     * Check is this answer for question we need
     */
    if ($this->getQuestion()->getId() != $answer->getQuestionId())
    {
      throw new \Exception ('Question and Answer does not respond');
    }

    if ($answer_text !== false)
    {
      if ($answer->getIsRight() == $answer_text)
      {
        $this->setIsRight(true);
      } else
      {
        $this->setIsRight(false);
      }
    } else
    {
      $this->setIsRight($answer->getIsRight());
    }
    $this->setAnswer($answer);
		$this->save();
  }

} // UserQuestion
