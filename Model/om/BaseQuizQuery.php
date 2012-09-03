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
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\QuizPeer;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\QuizQuestion;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuiz;

/**
 * @method QuizQuery orderById($order = Criteria::ASC) Order by the id column
 * @method QuizQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method QuizQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method QuizQuery orderByTime($order = Criteria::ASC) Order by the time column
 * @method QuizQuery orderByNumQuestions($order = Criteria::ASC) Order by the num_questions column
 * @method QuizQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method QuizQuery orderByIsOpened($order = Criteria::ASC) Order by the is_opened column
 * @method QuizQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method QuizQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method QuizQuery groupById() Group by the id column
 * @method QuizQuery groupByTitle() Group by the title column
 * @method QuizQuery groupByDescription() Group by the description column
 * @method QuizQuery groupByTime() Group by the time column
 * @method QuizQuery groupByNumQuestions() Group by the num_questions column
 * @method QuizQuery groupByIsActive() Group by the is_active column
 * @method QuizQuery groupByIsOpened() Group by the is_opened column
 * @method QuizQuery groupByCreatedAt() Group by the created_at column
 * @method QuizQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method QuizQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method QuizQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method QuizQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method QuizQuery leftJoinLessonQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the LessonQuiz relation
 * @method QuizQuery rightJoinLessonQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LessonQuiz relation
 * @method QuizQuery innerJoinLessonQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the LessonQuiz relation
 *
 * @method QuizQuery leftJoinQuizQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the QuizQuestion relation
 * @method QuizQuery rightJoinQuizQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the QuizQuestion relation
 * @method QuizQuery innerJoinQuizQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the QuizQuestion relation
 *
 * @method QuizQuery leftJoinUserQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuestion relation
 * @method QuizQuery rightJoinUserQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuestion relation
 * @method QuizQuery innerJoinUserQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuestion relation
 *
 * @method QuizQuery leftJoinUserQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuiz relation
 * @method QuizQuery rightJoinUserQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuiz relation
 * @method QuizQuery innerJoinUserQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuiz relation
 *
 * @method Quiz findOne(PropelPDO $con = null) Return the first Quiz matching the query
 * @method Quiz findOneOrCreate(PropelPDO $con = null) Return the first Quiz matching the query, or a new Quiz object populated from the query conditions when no match is found
 *
 * @method Quiz findOneByTitle(string $title) Return the first Quiz filtered by the title column
 * @method Quiz findOneByDescription(string $description) Return the first Quiz filtered by the description column
 * @method Quiz findOneByTime(int $time) Return the first Quiz filtered by the time column
 * @method Quiz findOneByNumQuestions(int $num_questions) Return the first Quiz filtered by the num_questions column
 * @method Quiz findOneByIsActive(boolean $is_active) Return the first Quiz filtered by the is_active column
 * @method Quiz findOneByIsOpened(boolean $is_opened) Return the first Quiz filtered by the is_opened column
 * @method Quiz findOneByCreatedAt(string $created_at) Return the first Quiz filtered by the created_at column
 * @method Quiz findOneByUpdatedAt(string $updated_at) Return the first Quiz filtered by the updated_at column
 *
 * @method array findById(int $id) Return Quiz objects filtered by the id column
 * @method array findByTitle(string $title) Return Quiz objects filtered by the title column
 * @method array findByDescription(string $description) Return Quiz objects filtered by the description column
 * @method array findByTime(int $time) Return Quiz objects filtered by the time column
 * @method array findByNumQuestions(int $num_questions) Return Quiz objects filtered by the num_questions column
 * @method array findByIsActive(boolean $is_active) Return Quiz objects filtered by the is_active column
 * @method array findByIsOpened(boolean $is_opened) Return Quiz objects filtered by the is_opened column
 * @method array findByCreatedAt(string $created_at) Return Quiz objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Quiz objects filtered by the updated_at column
 */
abstract class BaseQuizQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseQuizQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\Quiz', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new QuizQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     QuizQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return QuizQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof QuizQuery) {
            return $criteria;
        }
        $query = new QuizQuery();
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
     * @return   Quiz|Quiz[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QuizPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(QuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Quiz A model object, or null if the key is not found
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
     * @return   Quiz A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `TITLE`, `DESCRIPTION`, `TIME`, `NUM_QUESTIONS`, `IS_ACTIVE`, `IS_OPENED`, `CREATED_AT`, `UPDATED_AT` FROM `quiz` WHERE `ID` = :p0';
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
            $obj = new Quiz();
            $obj->hydrate($row);
            QuizPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Quiz|Quiz[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Quiz[]|mixed the list of results, formatted by the current formatter
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QuizPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QuizPeer::ID, $keys, Criteria::IN);
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(QuizPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuizPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuizPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the time column
     *
     * Example usage:
     * <code>
     * $query->filterByTime(1234); // WHERE time = 1234
     * $query->filterByTime(array(12, 34)); // WHERE time IN (12, 34)
     * $query->filterByTime(array('min' => 12)); // WHERE time > 12
     * </code>
     *
     * @param     mixed $time The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByTime($time = null, $comparison = null)
    {
        if (is_array($time)) {
            $useMinMax = false;
            if (isset($time['min'])) {
                $this->addUsingAlias(QuizPeer::TIME, $time['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($time['max'])) {
                $this->addUsingAlias(QuizPeer::TIME, $time['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuizPeer::TIME, $time, $comparison);
    }

    /**
     * Filter the query on the num_questions column
     *
     * Example usage:
     * <code>
     * $query->filterByNumQuestions(1234); // WHERE num_questions = 1234
     * $query->filterByNumQuestions(array(12, 34)); // WHERE num_questions IN (12, 34)
     * $query->filterByNumQuestions(array('min' => 12)); // WHERE num_questions > 12
     * </code>
     *
     * @param     mixed $numQuestions The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByNumQuestions($numQuestions = null, $comparison = null)
    {
        if (is_array($numQuestions)) {
            $useMinMax = false;
            if (isset($numQuestions['min'])) {
                $this->addUsingAlias(QuizPeer::NUM_QUESTIONS, $numQuestions['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numQuestions['max'])) {
                $this->addUsingAlias(QuizPeer::NUM_QUESTIONS, $numQuestions['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuizPeer::NUM_QUESTIONS, $numQuestions, $comparison);
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $is_active = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(QuizPeer::IS_ACTIVE, $isActive, $comparison);
    }

    /**
     * Filter the query on the is_opened column
     *
     * Example usage:
     * <code>
     * $query->filterByIsOpened(true); // WHERE is_opened = true
     * $query->filterByIsOpened('yes'); // WHERE is_opened = true
     * </code>
     *
     * @param     boolean|string $isOpened The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByIsOpened($isOpened = null, $comparison = null)
    {
        if (is_string($isOpened)) {
            $is_opened = in_array(strtolower($isOpened), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(QuizPeer::IS_OPENED, $isOpened, $comparison);
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(QuizPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(QuizPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuizPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(QuizPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(QuizPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuizPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related LessonQuiz object
     *
     * @param   LessonQuiz|PropelObjectCollection $lessonQuiz  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByLessonQuiz($lessonQuiz, $comparison = null)
    {
        if ($lessonQuiz instanceof LessonQuiz) {
            return $this
                ->addUsingAlias(QuizPeer::ID, $lessonQuiz->getQuizId(), $comparison);
        } elseif ($lessonQuiz instanceof PropelObjectCollection) {
            return $this
                ->useLessonQuizQuery()
                ->filterByPrimaryKeys($lessonQuiz->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLessonQuiz() only accepts arguments of type LessonQuiz or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LessonQuiz relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function joinLessonQuiz($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LessonQuiz');

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
            $this->addJoinObject($join, 'LessonQuiz');
        }

        return $this;
    }

    /**
     * Use the LessonQuiz relation LessonQuiz object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\CourseBundle\Model\LessonQuizQuery A secondary query class using the current class as primary query
     */
    public function useLessonQuizQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLessonQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LessonQuiz', '\Smirik\CourseBundle\Model\LessonQuizQuery');
    }

    /**
     * Filter the query by a related QuizQuestion object
     *
     * @param   QuizQuestion|PropelObjectCollection $quizQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuizQuestion($quizQuestion, $comparison = null)
    {
        if ($quizQuestion instanceof QuizQuestion) {
            return $this
                ->addUsingAlias(QuizPeer::ID, $quizQuestion->getQuizId(), $comparison);
        } elseif ($quizQuestion instanceof PropelObjectCollection) {
            return $this
                ->useQuizQuestionQuery()
                ->filterByPrimaryKeys($quizQuestion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQuizQuestion() only accepts arguments of type QuizQuestion or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the QuizQuestion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function joinQuizQuestion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('QuizQuestion');

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
            $this->addJoinObject($join, 'QuizQuestion');
        }

        return $this;
    }

    /**
     * Use the QuizQuestion relation QuizQuestion object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Smirik\QuizBundle\Model\QuizQuestionQuery A secondary query class using the current class as primary query
     */
    public function useQuizQuestionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinQuizQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'QuizQuestion', '\Smirik\QuizBundle\Model\QuizQuestionQuery');
    }

    /**
     * Filter the query by a related UserQuestion object
     *
     * @param   UserQuestion|PropelObjectCollection $userQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuestion($userQuestion, $comparison = null)
    {
        if ($userQuestion instanceof UserQuestion) {
            return $this
                ->addUsingAlias(QuizPeer::ID, $userQuestion->getQuizId(), $comparison);
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
     * @return QuizQuery The current query, for fluid interface
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
     * Filter the query by a related UserQuiz object
     *
     * @param   UserQuiz|PropelObjectCollection $userQuiz  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuizQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuiz($userQuiz, $comparison = null)
    {
        if ($userQuiz instanceof UserQuiz) {
            return $this
                ->addUsingAlias(QuizPeer::ID, $userQuiz->getQuizId(), $comparison);
        } elseif ($userQuiz instanceof PropelObjectCollection) {
            return $this
                ->useUserQuizQuery()
                ->filterByPrimaryKeys($userQuiz->getPrimaryKeys())
                ->endUse();
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
     * @return QuizQuery The current query, for fluid interface
     */
    public function joinUserQuiz($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUserQuizQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserQuiz($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserQuiz', '\Smirik\QuizBundle\Model\UserQuizQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Quiz $quiz Object to remove from the list of results
     *
     * @return QuizQuery The current query, for fluid interface
     */
    public function prune($quiz = null)
    {
        if ($quiz) {
            $this->addUsingAlias(QuizPeer::ID, $quiz->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(QuizPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuizPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuizPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(QuizPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuizPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     QuizQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuizPeer::CREATED_AT);
    }
}
