<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseUserQuestionQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'users_questions' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class UserQuestionQuery extends BaseUserQuestionQuery {

	public function current($user_id, $user_quiz_id, $question_id)
	{
		return $this
			->filterByUserId($user_id)
			->filterByUserQuizId($user_quiz_id)
			->filterByQuestionId($question_id)
		;
	}

} // UserQuestionQuery
