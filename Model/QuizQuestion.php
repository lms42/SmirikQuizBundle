<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseQuizQuestion;


/**
 * Skeleton subclass for representing a row from the 'quiz_questions' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class QuizQuestion extends BaseQuizQuestion {

	public function __toString()
	{
		return $this->getQuiz()->getTitle();
	}

} // QuizQuestion
