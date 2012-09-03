<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseQuestionQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'questions' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class QuestionQuery extends BaseQuestionQuery {

	public function filterByTest($text)
	{
		return $this
			->useQuizQuestionQuery()
				->join('Quiz')
			->endUse()
			->where("quiz.TITLE LIKE '%".$text."%'")
		;
	}
	
	public function orderByTest($type)
	{
		return $this
			->useQuizQuery()
				->orderByTitle($type)
			->endUse()
		;
	}

} // QuestionQuery
