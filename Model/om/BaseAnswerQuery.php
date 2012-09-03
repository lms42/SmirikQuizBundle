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
use Smirik\QuizBundle\Model\AnswerPeer;
use Smirik\QuizBundle\Model\AnswerQuery;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\UserQuestion;

/**
 * @method AnswerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AnswerQuery orderByQuestionId($order = Criteria::ASC) Order by the question_id column
 * @method AnswerQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method AnswerQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method AnswerQuery orderByIsRight($order = Criteria::ASC) Order by the is_right column
 *
 * @method AnswerQuery groupById() Group by the id column
 * @method AnswerQuery groupByQuestionId() Group by the question_id column
 * @method AnswerQuery groupByTitle() Group by the title column
 * @method AnswerQuery groupByFile() Group by the file column
 * @method AnswerQuery groupByIsRight() Group by the is_right column
 *
 * @method AnswerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AnswerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AnswerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AnswerQuery leftJoinQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the Question relation
 * @method AnswerQuery rightJoinQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Question relation
 * @method AnswerQuery innerJoinQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the Question relation
 *
 * @method AnswerQuery leftJoinUserQuestion($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserQuestion relation
 * @method AnswerQuery rightJoinUserQuestion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserQuestion relation
 * @method AnswerQuery innerJoinUserQuestion($relationAlias = null) Adds a INNER JOIN clause to the query using the UserQuestion relation
 *
 * @method Answer findOne(PropelPDO $con = null) Return the first Answer matching the query
 * @method Answer findOneOrCreate(PropelPDO $con = null) Return the first Answer matching the query, or a new Answer object populated from the query conditions when no match is found
 *
 * @method Answer findOneByQuestionId(int $question_id) Return the first Answer filtered by the question_id column
 * @method Answer findOneByTitle(string $title) Return the first Answer filtered by the title column
 * @method Answer findOneByFile(string $file) Return the first Answer filtered by the file column
 * @method Answer findOneByIsRight(string $is_right) Return the first Answer filtered by the is_right column
 *
 * @method array findById(int $id) Return Answer objects filtered by the id column
 * @method array findByQuestionId(int $question_id) Return Answer objects filtered by the question_id column
 * @method array findByTitle(string $title) Return Answer objects filtered by the title column
 * @method array findByFile(string $file) Return Answer objects filtered by the file column
 * @method array findByIsRight(string $is_right) Return Answer objects filtered by the is_right column
 */
abstract class BaseAnswerQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAnswerQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Smirik\\QuizBundle\\Model\\Answer', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AnswerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     AnswerQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AnswerQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AnswerQuery) {
            return $criteria;
        }
        $query = new AnswerQuery();
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
     * @return   Answer|Answer[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AnswerPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AnswerPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Answer A model object, or null if the key is not found
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
     * @return   Answer A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `QUESTION_ID`, `TITLE`, `FILE`, `IS_RIGHT` FROM `answers` WHERE `ID` = :p0';
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
            $obj = new Answer();
            $obj->hydrate($row);
            AnswerPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Answer|Answer[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Answer[]|mixed the list of results, formatted by the current formatter
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
     * @return AnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AnswerPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AnswerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AnswerPeer::ID, $keys, Criteria::IN);
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
     * @return AnswerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(AnswerPeer::ID, $id, $comparison);
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
     * @return AnswerQuery The current query, for fluid interface
     */
    public function filterByQuestionId($questionId = null, $comparison = null)
    {
        if (is_array($questionId)) {
            $useMinMax = false;
            if (isset($questionId['min'])) {
                $this->addUsingAlias(AnswerPeer::QUESTION_ID, $questionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($questionId['max'])) {
                $this->addUsingAlias(AnswerPeer::QUESTION_ID, $questionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AnswerPeer::QUESTION_ID, $questionId, $comparison);
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
     * @return AnswerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AnswerPeer::TITLE, $title, $comparison);
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
     * @return AnswerQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AnswerPeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the is_right column
     *
     * Example usage:
     * <code>
     * $query->filterByIsRight('fooValue');   // WHERE is_right = 'fooValue'
     * $query->filterByIsRight('%fooValue%'); // WHERE is_right LIKE '%fooValue%'
     * </code>
     *
     * @param     string $isRight The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AnswerQuery The current query, for fluid interface
     */
    public function filterByIsRight($isRight = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($isRight)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $isRight)) {
                $isRight = str_replace('*', '%', $isRight);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AnswerPeer::IS_RIGHT, $isRight, $comparison);
    }

    /**
     * Filter the query by a related Question object
     *
     * @param   Question|PropelObjectCollection $question The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AnswerQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuestion($question, $comparison = null)
    {
        if ($question instanceof Question) {
            return $this
                ->addUsingAlias(AnswerPeer::QUESTION_ID, $question->getId(), $comparison);
        } elseif ($question instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AnswerPeer::QUESTION_ID, $question->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return AnswerQuery The current query, for fluid interface
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
     * Filter the query by a related UserQuestion object
     *
     * @param   UserQuestion|PropelObjectCollection $userQuestion  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AnswerQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserQuestion($userQuestion, $comparison = null)
    {
        if ($userQuestion instanceof UserQuestion) {
            return $this
                ->addUsingAlias(AnswerPeer::ID, $userQuestion->getAnswerId(), $comparison);
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
     * @return AnswerQuery The current query, for fluid interface
     */
    public function joinUserQuestion($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useUserQuestionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserQuestion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserQuestion', '\Smirik\QuizBundle\Model\UserQuestionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Answer $answer Object to remove from the list of results
     *
     * @return AnswerQuery The current query, for fluid interface
     */
    public function prune($answer = null)
    {
        if ($answer) {
            $this->addUsingAlias(AnswerPeer::ID, $answer->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
