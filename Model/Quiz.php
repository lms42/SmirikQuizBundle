<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseQuiz;


/**
 * Skeleton subclass for representing a row from the 'quiz' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class Quiz extends BaseQuiz {

	public function __toString()
	{
		return $this->getTitle();
	}

} // Quiz
