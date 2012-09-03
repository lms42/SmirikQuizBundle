<?php

namespace Smirik\QuizBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use Smirik\QuizBundle\Model\Answer;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuestionPeer;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuiz;

/**
 * @method UserQuestionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserQuestionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserQuestionQuery orderByQuizId($order = Criteria::ASC) Order by the quiz_id column
 * @method UserQuestionQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method UserQuestionQuery orderByUserQuizId($order = Criteria::ASC) Order by the user_quiz_id column
 * @method UserQuestionQuery orderByAnswerId($order = Criteria::ASC) Order by the answer_id column
 * @method UserQuestionQuery orderByAnswerText($order = Criteria::ASC) Order by the answer_text column
 * @method UserQuestionQuery orderByIsRight($order = Criteria::ASC) Order by the is_right column
 * @method UserQuestionQuery orderByIsClosed($order = Criteria::ASC) Order by the is_closed column
 *
 * @method UserQuestionQuery groupById() Group by the id column
 * @method UserQuestionQuery groupByUserId() Group by the user_id column
 * @method UserQuestionQuery groupByQuizId() Group by the quiz_id column
 * @method UserQuestionQuery groupByQuestionId() Group by the question_id column
 * @method UserQuestionQuery groupByUserQuizId() Group by the user_quiz_id column
 * @method UserQuestionQuery groupByAnswerId() Group by the answer_id column
 * @method UserQuestionQuery groupByAnswerText() Group by the answer_text column
 * @method UserQuestionQuery groupByIsRight() Group by the is_right column
 * @method UserQuestionQuery groupByIsClosed() Group by the is_closed column
 *
 * @method UserQuestionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuestionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuestionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserQuestionQuery leftJoinQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Question relation
 * @method UserQuestionQuery rightJoinQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Question relation
 * @method UserQuestionQuery innerJoinQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the Question relation
 *
 * @method UserQuestionQuery leftJoinQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the Quiz relation
 * @method UserQuestionQuery rightJoinQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Quiz relation
 * @method UserQuestionQuery innerJoinQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the Quiz relation
 *
 * @method UserQuestionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserQuestionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserQuestionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserQuestionQuery leftJoinAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Answer relation
 * @method UserQuestionQuery rightJoinAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Answer relation
 * @method UserQuestionQuery innerJoinAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the Answer relation
 *
 * @method UserQuestionQuery leftJoinUserQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuiz relation
 * @method UserQuestionQuery rightJoinUserQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuiz relation
 * @method UserQuestionQuery innerJoinUserQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuiz relation
 *
 * @method UserQuestion findOne(PropelPDO $con = null) Return the first UserQuestion matching the query
 * @method UserQuestion findOneOrCreate(PropelPDO $con = null) Return the first UserQuestion matching the query, or a new UserQuestion object populated from the query conditions when no match is found
 *
 * @method UserQuestion findOneByUserId(int $user_id) Return the first UserQuestion filtered by the user_id column
 * @method UserQuestion findOneByQuizId(int $quiz_id) Return the first UserQuestion filtered by the quiz_id column
 * @method UserQuestion findOneByQuestionId(int $question_id) Return the first UserQuestion filtered by the question_id column
 * @method UserQuestion findOneByUserQuizId(int $user_quiz_id) Return the first UserQuestion filtered by the user_quiz_id column
 * @method UserQuestion findOneByAnswerId(int $answer_id) Return the first UserQuestion filtered by the answer_id column
 * @method UserQuestion findOneByAnswerText(string $answer_text) Return the first UserQuestion filtered by the answer_text column
 * @method UserQuestion findOneByIsRight(boolean $is_right) Return the first UserQuestion filtered by the is_right column
 * @method UserQuestion findOneByIsClosed(boolean $is_closed) Return the first UserQuestion filtered by the is_closed column
 *
 * @method array findById(int $id) Return UserQuestion objects filtered by the id column
 * @method array findByUserId(int $user_id) Return UserQuestion objects filtered by the user_id column
 * @method array findByQuizId(int $quiz_id) Return UserQuestion objects filtered by the quiz_id column
 * @method array findByQuestionId(int $question_id) Return UserQuestion objects filtered by the question_id column
 * @method array findByUserQuizId(int $user_quiz_id) Return UserQuestion objects filtered by the user_quiz_id column
 * @method array findByAnswerId(int $answer_id) Return UserQuestion objects filtered by the answer_id column
 * @method array findByAnswerText(string $answer_text) Return UserQuestion objects filtered by the answer_text column
 * @method array findByIsRight(boolean $is_right) Return UserQuestion objects filtered by the is_right column
 * @method array findByIsClosed(boolean $is_closed) Return UserQuestion objects filtered by the is_closed column
 */
abstract class BaseUserQuestionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuestionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\UserQuestion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuestionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserQuestionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuestionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuestionQuery) {
            return $criteria;
        }
        $query = new UserQuestionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   UserQuestion|UserQuestion[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserQuestionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   UserQuestion A model object, or null if the key is not found
     * @throws   PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   UserQuestion A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_ID`, `QUIZ_ID`, `QUESTION_ID`, `USER_QUIZ_ID`, `ANSWER_ID`, `ANSWER_TEXT`, `IS_RIGHT`, `IS_CLOSED` FROM `users_questions` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new UserQuestion();
            $obj->hydrate($row);
            UserQuestionPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return UserQuestion|UserQuestion[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|UserQuestion[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserQuestionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserQuestionPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserQuestionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserQuestionPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserQuestionPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the quiz_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuizId(1234); // WHERE quiz_id = 1234
     * $query->filterByQuizId(array(12, 34)); // WHERE quiz_id IN (12, 34)
     * $query->filterByQuizId(array('min' => 12)); // WHERE quiz_id > 12
     * </code>
     *
     * @see       filterByQuiz()
     *
     * @param     mixed $quizId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByQuizId($quizId = null, $comparison = null)
    {
        if (is_array($quizId)) {
            $useMinMax = false;
            if (isset($quizId['min'])) {
                $this->addUsingAlias(UserQuestionPeer::QUIZ_ID, $quizId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quizId['max'])) {
                $this->addUsingAlias(UserQuestionPeer::QUIZ_ID, $quizId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::QUIZ_ID, $quizId, $comparison);
    }

    /**
     * Filter the query on the question_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestionId(1234); // WHERE question_id = 1234
     * $query->filterByQuestionId(array(12, 34)); // WHERE question_id IN (12, 34)
     * $query->filterByQuestionId(array('min' => 12)); // WHERE question_id > 12
     * </code>
     *
     * @see       filterByQuestion()
     *
     * @param     mixed $questionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(UserQuestionPeer::QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(UserQuestionPeer::QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::QUESTION_ID, $questionId, $comparison);
    }

    /**
     * Filter the query on the user_quiz_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserQuizId(1234); // WHERE user_quiz_id = 1234
     * $query->filterByUserQuizId(array(12, 34)); // WHERE user_quiz_id IN (12, 34)
     * $query->filterByUserQuizId(array('min' => 12)); // WHERE user_quiz_id > 12
     * </code>
     *
     * @see       filterByUserQuiz()
     *
     * @param     mixed $userQuizId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByUserQuizId($userQuizId = null, $comparison = null)
    {
        if (is_array($userQuizId)) {
            $useMinMax = false;
            if (isset($userQuizId['min'])) {
                $this->addUsingAlias(UserQuestionPeer::USER_QUIZ_ID, $userQuizId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userQuizId['max'])) {
                $this->addUsingAlias(UserQuestionPeer::USER_QUIZ_ID, $userQuizId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::USER_QUIZ_ID, $userQuizId, $comparison);
    }

    /**
     * Filter the query on the answer_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswerId(1234); // WHERE answer_id = 1234
     * $query->filterByAnswerId(array(12, 34)); // WHERE answer_id IN (12, 34)
     * $query->filterByAnswerId(array('min' => 12)); // WHERE answer_id > 12
     * </code>
     *
     * @see       filterByAnswer()
     *
     * @param     mixed $answerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByAnswerId($answerId = null, $comparison = null)
    {
        if (is_array($answerId)) {
            $useMinMax = false;
            if (isset($answerId['min'])) {
                $this->addUsingAlias(UserQuestionPeer::ANSWER_ID, $answerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($answerId['max'])) {
                $this->addUsingAlias(UserQuestionPeer::ANSWER_ID, $answerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::ANSWER_ID, $answerId, $comparison);
    }

    /**
     * Filter the query on the answer_text column
     *
     * Example usage:
     * <code>
     * $query->filterByAnswerText('fooValue');   // WHERE answer_text = 'fooValue'
     * $query->filterByAnswerText('%fooValue%'); // WHERE answer_text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $answerText The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByAnswerText($answerText = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($answerText)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $answerText)) {
                $answerText = str_replace('*', '%', $answerText);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserQuestionPeer::ANSWER_TEXT, $answerText, $comparison);
    }

    /**
     * Filter the query on the is_right column
     *
     * Example usage:
     * <code>
     * $query->filterByIsRight(true); // WHERE is_right = true
     * $query->filterByIsRight('yes'); // WHERE is_right = true
     * </code>
     *
     * @param     boolean|string $isRight The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByIsRight($isRight = null, $comparison = null)
    {
        if (is_string($isRight)) {
            $is_right = in_array(strtolower($isRight), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserQuestionPeer::IS_RIGHT, $isRight, $comparison);
    }

    /**
     * Filter the query on the is_closed column
     *
     * Example usage:
     * <code>
     * $query->filterByIsClosed(true); // WHERE is_closed = true
     * $query->filterByIsClosed('yes'); // WHERE is_closed = true
     * </code>
     *
     * @param     boolean|string $isClosed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function filterByIsClosed($isClosed = null, $comparison = null)
    {
        if (is_string($isClosed)) {
            $is_closed = in_array(strtolower($isClosed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserQuestionPeer::IS_CLOSED, $isClosed, $comparison);
    }

    /**
     * Filter the query by a related Question object
     *
     * @param   Question|PropelObjectCollection $question The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuestion($question, $comparison = null)
    {
        if ($question instanceof Question) {
            return $this
                ->addUsingAlias(UserQuestionPeer::QUESTION_ID, $question->getId(), $comparison);
        } elseif ($question instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuestionPeer::QUESTION_ID, $question->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByQuestion() only accepts arguments of type Question or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Question relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function joinQuestion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Question');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Question');
        }

        return $this;
    }

    /**
     * Use the Question relation Question object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\QuestionQuery A secondary query class using the current class as primary query
     */
    public function useQuestionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Question', '\Smirik\QuizBundle\Model\QuestionQuery');
    }

    /**
     * Filter the query by a related Quiz object
     *
     * @param   Quiz|PropelObjectCollection $quiz The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuiz($quiz, $comparison = null)
    {
        if ($quiz instanceof Quiz) {
            return $this
                ->addUsingAlias(UserQuestionPeer::QUIZ_ID, $quiz->getId(), $comparison);
        } elseif ($quiz instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuestionPeer::QUIZ_ID, $quiz->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByQuiz() only accepts arguments of type Quiz or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Quiz relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function joinQuiz($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Quiz');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Quiz');
        }

        return $this;
    }

    /**
     * Use the Quiz relation Quiz object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\QuizQuery A secondary query class using the current class as primary query
     */
    public function useQuizQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Quiz', '\Smirik\QuizBundle\Model\QuizQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserQuestionPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuestionPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related Answer object
     *
     * @param   Answer|PropelObjectCollection $answer The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAnswer($answer, $comparison = null)
    {
        if ($answer instanceof Answer) {
            return $this
                ->addUsingAlias(UserQuestionPeer::ANSWER_ID, $answer->getId(), $comparison);
        } elseif ($answer instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuestionPeer::ANSWER_ID, $answer->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAnswer() only accepts arguments of type Answer or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Answer relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function joinAnswer($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Answer');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Answer');
        }

        return $this;
    }

    /**
     * Use the Answer relation Answer object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\AnswerQuery A secondary query class using the current class as primary query
     */
    public function useAnswerQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAnswer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Answer', '\Smirik\QuizBundle\Model\AnswerQuery');
    }

    /**
     * Filter the query by a related UserQuiz object
     *
     * @param   UserQuiz|PropelObjectCollection $userQuiz The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuiz($userQuiz, $comparison = null)
    {
        if ($userQuiz instanceof UserQuiz) {
            return $this
                ->addUsingAlias(UserQuestionPeer::USER_QUIZ_ID, $userQuiz->getId(), $comparison);
        } elseif ($userQuiz instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuestionPeer::USER_QUIZ_ID, $userQuiz->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserQuiz() only accepts arguments of type UserQuiz or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserQuiz relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function joinUserQuiz($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserQuiz');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'UserQuiz');
        }

        return $this;
    }

    /**
     * Use the UserQuiz relation UserQuiz object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\UserQuizQuery A secondary query class using the current class as primary query
     */
    public function useUserQuizQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserQuiz', '\Smirik\QuizBundle\Model\UserQuizQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserQuestion $userQuestion Object to remove from the list of results
     *
     * @return UserQuestionQuery The current query, for fluid interface
     */
    public function prune($userQuestion = null)
    {
        if ($userQuestion) {
            $this->addUsingAlias(UserQuestionPeer::ID, $userQuestion->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
