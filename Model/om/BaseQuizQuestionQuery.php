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
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\QuizQuestion;
use Smirik\QuizBundle\Model\QuizQuestionPeer;
use Smirik\QuizBundle\Model\QuizQuestionQuery;

/**
 * @method QuizQuestionQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method QuizQuestionQuery orderByQuizId($order = Criteria::ASC) Order by the quiz_id column
 *
 * @method QuizQuestionQuery groupByQuestionId() Group by the question_id column
 * @method QuizQuestionQuery groupByQuizId() Group by the quiz_id column
 *
 * @method QuizQuestionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method QuizQuestionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method QuizQuestionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method QuizQuestionQuery leftJoinQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Question relation
 * @method QuizQuestionQuery rightJoinQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Question relation
 * @method QuizQuestionQuery innerJoinQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the Question relation
 *
 * @method QuizQuestionQuery leftJoinQuiz($relationAlias = null) Adds a LEFT JOIN clause to the query using the Quiz relation
 * @method QuizQuestionQuery rightJoinQuiz($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Quiz relation
 * @method QuizQuestionQuery innerJoinQuiz($relationAlias = null) Adds a INNER JOIN clause to the query using the Quiz relation
 *
 * @method QuizQuestion findOne(PropelPDO $con = null) Return the first QuizQuestion matching the query
 * @method QuizQuestion findOneOrCreate(PropelPDO $con = null) Return the first QuizQuestion matching the query, or a new QuizQuestion object populated from the query conditions when no match is found
 *
 * @method QuizQuestion findOneByQuestionId(int $question_id) Return the first QuizQuestion filtered by the question_id column
 * @method QuizQuestion findOneByQuizId(int $quiz_id) Return the first QuizQuestion filtered by the quiz_id column
 *
 * @method array findByQuestionId(int $question_id) Return QuizQuestion objects filtered by the question_id column
 * @method array findByQuizId(int $quiz_id) Return QuizQuestion objects filtered by the quiz_id column
 */
abstract class BaseQuizQuestionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseQuizQuestionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\QuizQuestion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new QuizQuestionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     QuizQuestionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return QuizQuestionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof QuizQuestionQuery) {
            return $criteria;
        }
        $query = new QuizQuestionQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$question_id, $quiz_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   QuizQuestion|QuizQuestion[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QuizQuestionPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(QuizQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   QuizQuestion A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `QUESTION_ID`, `QUIZ_ID` FROM `quiz_questions` WHERE `QUESTION_ID` = :p0 AND `QUIZ_ID` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new QuizQuestion();
            $obj->hydrate($row);
            QuizQuestionPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return QuizQuestion|QuizQuestion[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|QuizQuestion[]|mixed the list of results, formatted by the current formatter
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
     * @return QuizQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(QuizQuestionPeer::QUESTION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(QuizQuestionPeer::QUIZ_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return QuizQuestionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(QuizQuestionPeer::QUESTION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(QuizQuestionPeer::QUIZ_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return QuizQuestionQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(QuizQuestionPeer::QUESTION_ID, $questionId, $comparison);
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
     * @return QuizQuestionQuery The current query, for fluid interface
     */
    public function filterByQuizId($quizId = null, $comparison = null)
    {
        if (is_array($quizId) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(QuizQuestionPeer::QUIZ_ID, $quizId, $comparison);
    }

    /**
     * Filter the query by a related Question object
     *
     * @param   Question|PropelObjectCollection $question The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuizQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuestion($question, $comparison = null)
    {
        if ($question instanceof Question) {
            return $this
                ->addUsingAlias(QuizQuestionPeer::QUESTION_ID, $question->getId(), $comparison);
        } elseif ($question instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QuizQuestionPeer::QUESTION_ID, $question->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return QuizQuestionQuery The current query, for fluid interface
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
     * @return   QuizQuestionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuiz($quiz, $comparison = null)
    {
        if ($quiz instanceof Quiz) {
            return $this
                ->addUsingAlias(QuizQuestionPeer::QUIZ_ID, $quiz->getId(), $comparison);
        } elseif ($quiz instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QuizQuestionPeer::QUIZ_ID, $quiz->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return QuizQuestionQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   QuizQuestion $quizQuestion Object to remove from the list of results
     *
     * @return QuizQuestionQuery The current query, for fluid interface
     */
    public function prune($quizQuestion = null)
    {
        if ($quizQuestion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(QuizQuestionPeer::QUESTION_ID), $quizQuestion->getQuestionId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(QuizQuestionPeer::QUIZ_ID), $quizQuestion->getQuizId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

}
