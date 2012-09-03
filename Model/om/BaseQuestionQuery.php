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
use Smirik\QuizBundle\Model\Answer;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\QuestionPeer;
use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\QuizQuestion;
use Smirik\QuizBundle\Model\UserQuestion;

/**
 * @method QuestionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method QuestionQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method QuestionQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method QuestionQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method QuestionQuery orderByNumAnswers($order = Criteria::ASC) Order by the num_answers column
 * @method QuestionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method QuestionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method QuestionQuery groupById() Group by the id column
 * @method QuestionQuery groupByText() Group by the text column
 * @method QuestionQuery groupByType() Group by the type column
 * @method QuestionQuery groupByFile() Group by the file column
 * @method QuestionQuery groupByNumAnswers() Group by the num_answers column
 * @method QuestionQuery groupByCreatedAt() Group by the created_at column
 * @method QuestionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method QuestionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method QuestionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method QuestionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method QuestionQuery leftJoinQuizQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the QuizQuestion relation
 * @method QuestionQuery rightJoinQuizQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the QuizQuestion relation
 * @method QuestionQuery innerJoinQuizQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the QuizQuestion relation
 *
 * @method QuestionQuery leftJoinAnswer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Answer relation
 * @method QuestionQuery rightJoinAnswer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Answer relation
 * @method QuestionQuery innerJoinAnswer($relationAlias = null) Adds a INNER JOIN clause to the query using the Answer relation
 *
 * @method QuestionQuery leftJoinUserQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuestion relation
 * @method QuestionQuery rightJoinUserQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuestion relation
 * @method QuestionQuery innerJoinUserQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuestion relation
 *
 * @method Question findOne(PropelPDO $con = null) Return the first Question matching the query
 * @method Question findOneOrCreate(PropelPDO $con = null) Return the first Question matching the query, or a new Question object populated from the query conditions when no match is found
 *
 * @method Question findOneByText(string $text) Return the first Question filtered by the text column
 * @method Question findOneByType(string $type) Return the first Question filtered by the type column
 * @method Question findOneByFile(string $file) Return the first Question filtered by the file column
 * @method Question findOneByNumAnswers(int $num_answers) Return the first Question filtered by the num_answers column
 * @method Question findOneByCreatedAt(string $created_at) Return the first Question filtered by the created_at column
 * @method Question findOneByUpdatedAt(string $updated_at) Return the first Question filtered by the updated_at column
 *
 * @method array findById(int $id) Return Question objects filtered by the id column
 * @method array findByText(string $text) Return Question objects filtered by the text column
 * @method array findByType(string $type) Return Question objects filtered by the type column
 * @method array findByFile(string $file) Return Question objects filtered by the file column
 * @method array findByNumAnswers(int $num_answers) Return Question objects filtered by the num_answers column
 * @method array findByCreatedAt(string $created_at) Return Question objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Question objects filtered by the updated_at column
 */
abstract class BaseQuestionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseQuestionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\Question', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new QuestionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     QuestionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return QuestionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof QuestionQuery) {
            return $criteria;
        }
        $query = new QuestionQuery();
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
     * @return   Question|Question[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QuestionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(QuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Question A model object, or null if the key is not found
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
     * @return   Question A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `TEXT`, `TYPE`, `FILE`, `NUM_ANSWERS`, `CREATED_AT`, `UPDATED_AT` FROM `questions` WHERE `ID` = :p0';
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
            $obj = new Question();
            $obj->hydrate($row);
            QuestionPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Question|Question[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Question[]|mixed the list of results, formatted by the current formatter
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
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QuestionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QuestionPeer::ID, $keys, Criteria::IN);
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
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(QuestionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $text)) {
                $text = str_replace('*', '%', $text);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuestionPeer::TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuestionPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the file column
     *
     * Example usage:
     * <code>
     * $query->filterByFile('fooValue');   // WHERE file = 'fooValue'
     * $query->filterByFile('%fooValue%'); // WHERE file LIKE '%fooValue%'
     * </code>
     *
     * @param     string $file The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByFile($file = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($file)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $file)) {
                $file = str_replace('*', '%', $file);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuestionPeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the num_answers column
     *
     * Example usage:
     * <code>
     * $query->filterByNumAnswers(1234); // WHERE num_answers = 1234
     * $query->filterByNumAnswers(array(12, 34)); // WHERE num_answers IN (12, 34)
     * $query->filterByNumAnswers(array('min' => 12)); // WHERE num_answers > 12
     * </code>
     *
     * @param     mixed $numAnswers The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByNumAnswers($numAnswers = null, $comparison = null)
    {
        if (is_array($numAnswers)) {
            $useMinMax = false;
            if (isset($numAnswers['min'])) {
                $this->addUsingAlias(QuestionPeer::NUM_ANSWERS, $numAnswers['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numAnswers['max'])) {
                $this->addUsingAlias(QuestionPeer::NUM_ANSWERS, $numAnswers['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionPeer::NUM_ANSWERS, $numAnswers, $comparison);
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
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(QuestionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(QuestionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return QuestionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(QuestionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(QuestionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuestionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related QuizQuestion object
     *
     * @param   QuizQuestion|PropelObjectCollection $quizQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuizQuestion($quizQuestion, $comparison = null)
    {
        if ($quizQuestion instanceof QuizQuestion) {
            return $this
                ->addUsingAlias(QuestionPeer::ID, $quizQuestion->getQuestionId(), $comparison);
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
     * @return QuestionQuery The current query, for fluid interface
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
     * Filter the query by a related Answer object
     *
     * @param   Answer|PropelObjectCollection $answer  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAnswer($answer, $comparison = null)
    {
        if ($answer instanceof Answer) {
            return $this
                ->addUsingAlias(QuestionPeer::ID, $answer->getQuestionId(), $comparison);
        } elseif ($answer instanceof PropelObjectCollection) {
            return $this
                ->useAnswerQuery()
                ->filterByPrimaryKeys($answer->getPrimaryKeys())
                ->endUse();
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
     * @return QuestionQuery The current query, for fluid interface
     */
    public function joinAnswer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useAnswerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAnswer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Answer', '\Smirik\QuizBundle\Model\AnswerQuery');
    }

    /**
     * Filter the query by a related UserQuestion object
     *
     * @param   UserQuestion|PropelObjectCollection $userQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuestion($userQuestion, $comparison = null)
    {
        if ($userQuestion instanceof UserQuestion) {
            return $this
                ->addUsingAlias(QuestionPeer::ID, $userQuestion->getQuestionId(), $comparison);
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
     * @return QuestionQuery The current query, for fluid interface
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
     * @param   Question $question Object to remove from the list of results
     *
     * @return QuestionQuery The current query, for fluid interface
     */
    public function prune($question = null)
    {
        if ($question) {
            $this->addUsingAlias(QuestionPeer::ID, $question->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(QuestionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuestionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuestionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(QuestionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(QuestionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     QuestionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(QuestionPeer::CREATED_AT);
    }
}
