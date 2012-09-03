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
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuiz;
use Smirik\QuizBundle\Model\UserQuizPeer;
use Smirik\QuizBundle\Model\UserQuizQuery;

/**
 * @method UserQuizQuery orderById($order = Criteria::ASC) Order by the id column
 * @method UserQuizQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method UserQuizQuery orderByQuizId($order = Criteria::ASC) Order by the quiz_id column
 * @method UserQuizQuery orderByQuestions($order = Criteria::ASC) Order by the questions column
 * @method UserQuizQuery orderByCurrent($order = Criteria::ASC) Order by the current column
 * @method UserQuizQuery orderByNumRightAnswers($order = Criteria::ASC) Order by the num_right_answers column
 * @method UserQuizQuery orderByStartedAt($order = Criteria::ASC) Order by the started_at column
 * @method UserQuizQuery orderByStoppedAt($order = Criteria::ASC) Order by the stopped_at column
 * @method UserQuizQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method UserQuizQuery orderByIsClosed($order = Criteria::ASC) Order by the is_closed column
 * @method UserQuizQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method UserQuizQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method UserQuizQuery groupById() Group by the id column
 * @method UserQuizQuery groupByUserId() Group by the user_id column
 * @method UserQuizQuery groupByQuizId() Group by the quiz_id column
 * @method UserQuizQuery groupByQuestions() Group by the questions column
 * @method UserQuizQuery groupByCurrent() Group by the current column
 * @method UserQuizQuery groupByNumRightAnswers() Group by the num_right_answers column
 * @method UserQuizQuery groupByStartedAt() Group by the started_at column
 * @method UserQuizQuery groupByStoppedAt() Group by the stopped_at column
 * @method UserQuizQuery groupByIsActive() Group by the is_active column
 * @method UserQuizQuery groupByIsClosed() Group by the is_closed column
 * @method UserQuizQuery groupByCreatedAt() Group by the created_at column
 * @method UserQuizQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method UserQuizQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method UserQuizQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method UserQuizQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method UserQuizQuery leftJoinQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the Quiz relation
 * @method UserQuizQuery rightJoinQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Quiz relation
 * @method UserQuizQuery innerJoinQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the Quiz relation
 *
 * @method UserQuizQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method UserQuizQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method UserQuizQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method UserQuizQuery leftJoinUserQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuestion relation
 * @method UserQuizQuery rightJoinUserQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuestion relation
 * @method UserQuizQuery innerJoinUserQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuestion relation
 *
 * @method UserQuiz findOne(PropelPDO $con = null) Return the first UserQuiz matching the query
 * @method UserQuiz findOneOrCreate(PropelPDO $con = null) Return the first UserQuiz matching the query, or a new UserQuiz object populated from the query conditions when no match is found
 *
 * @method UserQuiz findOneByUserId(int $user_id) Return the first UserQuiz filtered by the user_id column
 * @method UserQuiz findOneByQuizId(int $quiz_id) Return the first UserQuiz filtered by the quiz_id column
 * @method UserQuiz findOneByQuestions(string $questions) Return the first UserQuiz filtered by the questions column
 * @method UserQuiz findOneByCurrent(int $current) Return the first UserQuiz filtered by the current column
 * @method UserQuiz findOneByNumRightAnswers(int $num_right_answers) Return the first UserQuiz filtered by the num_right_answers column
 * @method UserQuiz findOneByStartedAt(string $started_at) Return the first UserQuiz filtered by the started_at column
 * @method UserQuiz findOneByStoppedAt(string $stopped_at) Return the first UserQuiz filtered by the stopped_at column
 * @method UserQuiz findOneByIsActive(boolean $is_active) Return the first UserQuiz filtered by the is_active column
 * @method UserQuiz findOneByIsClosed(boolean $is_closed) Return the first UserQuiz filtered by the is_closed column
 * @method UserQuiz findOneByCreatedAt(string $created_at) Return the first UserQuiz filtered by the created_at column
 * @method UserQuiz findOneByUpdatedAt(string $updated_at) Return the first UserQuiz filtered by the updated_at column
 *
 * @method array findById(int $id) Return UserQuiz objects filtered by the id column
 * @method array findByUserId(int $user_id) Return UserQuiz objects filtered by the user_id column
 * @method array findByQuizId(int $quiz_id) Return UserQuiz objects filtered by the quiz_id column
 * @method array findByQuestions(string $questions) Return UserQuiz objects filtered by the questions column
 * @method array findByCurrent(int $current) Return UserQuiz objects filtered by the current column
 * @method array findByNumRightAnswers(int $num_right_answers) Return UserQuiz objects filtered by the num_right_answers column
 * @method array findByStartedAt(string $started_at) Return UserQuiz objects filtered by the started_at column
 * @method array findByStoppedAt(string $stopped_at) Return UserQuiz objects filtered by the stopped_at column
 * @method array findByIsActive(boolean $is_active) Return UserQuiz objects filtered by the is_active column
 * @method array findByIsClosed(boolean $is_closed) Return UserQuiz objects filtered by the is_closed column
 * @method array findByCreatedAt(string $created_at) Return UserQuiz objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return UserQuiz objects filtered by the updated_at column
 */
abstract class BaseUserQuizQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseUserQuizQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\UserQuiz', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new UserQuizQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     UserQuizQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return UserQuizQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuizQuery) {
            return $criteria;
        }
        $query = new UserQuizQuery();
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
     * @return   UserQuiz|UserQuiz[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserQuizPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(UserQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   UserQuiz A model object, or null if the key is not found
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
     * @return   UserQuiz A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_ID`, `QUIZ_ID`, `QUESTIONS`, `CURRENT`, `NUM_RIGHT_ANSWERS`, `STARTED_AT`, `STOPPED_AT`, `IS_ACTIVE`, `IS_CLOSED`, `CREATED_AT`, `UPDATED_AT` FROM `users_quiz` WHERE `ID` = :p0';
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
            $obj = new UserQuiz();
            $obj->hydrate($row);
            UserQuizPeer::addInstanceToPool($obj, (string) $key);
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
     * @return UserQuiz|UserQuiz[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|UserQuiz[]|mixed the list of results, formatted by the current formatter
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserQuizPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserQuizPeer::ID, $keys, Criteria::IN);
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(UserQuizPeer::ID, $id, $comparison);
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UserQuizPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UserQuizPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::USER_ID, $userId, $comparison);
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByQuizId($quizId = null, $comparison = null)
    {
        if (is_array($quizId)) {
            $useMinMax = false;
            if (isset($quizId['min'])) {
                $this->addUsingAlias(UserQuizPeer::QUIZ_ID, $quizId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quizId['max'])) {
                $this->addUsingAlias(UserQuizPeer::QUIZ_ID, $quizId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::QUIZ_ID, $quizId, $comparison);
    }

    /**
     * Filter the query on the questions column
     *
     * Example usage:
     * <code>
     * $query->filterByQuestions('fooValue');   // WHERE questions = 'fooValue'
     * $query->filterByQuestions('%fooValue%'); // WHERE questions LIKE '%fooValue%'
     * </code>
     *
     * @param     string $questions The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByQuestions($questions = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($questions)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $questions)) {
                $questions = str_replace('*', '%', $questions);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::QUESTIONS, $questions, $comparison);
    }

    /**
     * Filter the query on the current column
     *
     * Example usage:
     * <code>
     * $query->filterByCurrent(1234); // WHERE current = 1234
     * $query->filterByCurrent(array(12, 34)); // WHERE current IN (12, 34)
     * $query->filterByCurrent(array('min' => 12)); // WHERE current > 12
     * </code>
     *
     * @param     mixed $current The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByCurrent($current = null, $comparison = null)
    {
        if (is_array($current)) {
            $useMinMax = false;
            if (isset($current['min'])) {
                $this->addUsingAlias(UserQuizPeer::CURRENT, $current['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($current['max'])) {
                $this->addUsingAlias(UserQuizPeer::CURRENT, $current['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::CURRENT, $current, $comparison);
    }

    /**
     * Filter the query on the num_right_answers column
     *
     * Example usage:
     * <code>
     * $query->filterByNumRightAnswers(1234); // WHERE num_right_answers = 1234
     * $query->filterByNumRightAnswers(array(12, 34)); // WHERE num_right_answers IN (12, 34)
     * $query->filterByNumRightAnswers(array('min' => 12)); // WHERE num_right_answers > 12
     * </code>
     *
     * @param     mixed $numRightAnswers The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByNumRightAnswers($numRightAnswers = null, $comparison = null)
    {
        if (is_array($numRightAnswers)) {
            $useMinMax = false;
            if (isset($numRightAnswers['min'])) {
                $this->addUsingAlias(UserQuizPeer::NUM_RIGHT_ANSWERS, $numRightAnswers['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numRightAnswers['max'])) {
                $this->addUsingAlias(UserQuizPeer::NUM_RIGHT_ANSWERS, $numRightAnswers['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::NUM_RIGHT_ANSWERS, $numRightAnswers, $comparison);
    }

    /**
     * Filter the query on the started_at column
     *
     * Example usage:
     * <code>
     * $query->filterByStartedAt('2011-03-14'); // WHERE started_at = '2011-03-14'
     * $query->filterByStartedAt('now'); // WHERE started_at = '2011-03-14'
     * $query->filterByStartedAt(array('max' => 'yesterday')); // WHERE started_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $startedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByStartedAt($startedAt = null, $comparison = null)
    {
        if (is_array($startedAt)) {
            $useMinMax = false;
            if (isset($startedAt['min'])) {
                $this->addUsingAlias(UserQuizPeer::STARTED_AT, $startedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($startedAt['max'])) {
                $this->addUsingAlias(UserQuizPeer::STARTED_AT, $startedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::STARTED_AT, $startedAt, $comparison);
    }

    /**
     * Filter the query on the stopped_at column
     *
     * Example usage:
     * <code>
     * $query->filterByStoppedAt('2011-03-14'); // WHERE stopped_at = '2011-03-14'
     * $query->filterByStoppedAt('now'); // WHERE stopped_at = '2011-03-14'
     * $query->filterByStoppedAt(array('max' => 'yesterday')); // WHERE stopped_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $stoppedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByStoppedAt($stoppedAt = null, $comparison = null)
    {
        if (is_array($stoppedAt)) {
            $useMinMax = false;
            if (isset($stoppedAt['min'])) {
                $this->addUsingAlias(UserQuizPeer::STOPPED_AT, $stoppedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stoppedAt['max'])) {
                $this->addUsingAlias(UserQuizPeer::STOPPED_AT, $stoppedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::STOPPED_AT, $stoppedAt, $comparison);
    }

    /**
     * Filter the query on the is_active column
     *
     * Example usage:
     * <code>
     * $query->filterByIsActive(true); // WHERE is_active = true
     * $query->filterByIsActive('yes'); // WHERE is_active = true
     * </code>
     *
     * @param     boolean|string $isActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $is_active = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserQuizPeer::IS_ACTIVE, $isActive, $comparison);
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByIsClosed($isClosed = null, $comparison = null)
    {
        if (is_string($isClosed)) {
            $is_closed = in_array(strtolower($isClosed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(UserQuizPeer::IS_CLOSED, $isClosed, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserQuizPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserQuizPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserQuizPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserQuizPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserQuizPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Quiz object
     *
     * @param   Quiz|PropelObjectCollection $quiz The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuiz($quiz, $comparison = null)
    {
        if ($quiz instanceof Quiz) {
            return $this
                ->addUsingAlias(UserQuizPeer::QUIZ_ID, $quiz->getId(), $comparison);
        } elseif ($quiz instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuizPeer::QUIZ_ID, $quiz->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function joinQuiz($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useQuizQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
     * @return   UserQuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(UserQuizPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UserQuizPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return UserQuizQuery The current query, for fluid interface
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
     * Filter the query by a related UserQuestion object
     *
     * @param   UserQuestion|PropelObjectCollection $userQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   UserQuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuestion($userQuestion, $comparison = null)
    {
        if ($userQuestion instanceof UserQuestion) {
            return $this
                ->addUsingAlias(UserQuizPeer::ID, $userQuestion->getUserQuizId(), $comparison);
        } elseif ($userQuestion instanceof PropelObjectCollection) {
            return $this
                ->useUserQuestionQuery()
                ->filterByPrimaryKeys($userQuestion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserQuestion() only accepts arguments of type UserQuestion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserQuestion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function joinUserQuestion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserQuestion');

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
            $this->addJoinObject($join, 'UserQuestion');
        }

        return $this;
    }

    /**
     * Use the UserQuestion relation UserQuestion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\UserQuestionQuery A secondary query class using the current class as primary query
     */
    public function useUserQuestionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserQuestion', '\Smirik\QuizBundle\Model\UserQuestionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   UserQuiz $userQuiz Object to remove from the list of results
     *
     * @return UserQuizQuery The current query, for fluid interface
     */
    public function prune($userQuiz = null)
    {
        if ($userQuiz) {
            $this->addUsingAlias(UserQuizPeer::ID, $userQuiz->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserQuizPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserQuizPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserQuizPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserQuizPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserQuizPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     UserQuizQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserQuizPeer::CREATED_AT);
    }
}
