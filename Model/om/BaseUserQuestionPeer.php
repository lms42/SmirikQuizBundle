<?php

namespace Smirik\QuizBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use FOS\UserBundle\Propel\UserPeer;
use Smirik\QuizBundle\Model\AnswerPeer;
use Smirik\QuizBundle\Model\QuestionPeer;
use Smirik\QuizBundle\Model\QuizPeer;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuestionPeer;
use Smirik\QuizBundle\Model\UserQuizPeer;
use Smirik\QuizBundle\Model\map\UserQuestionTableMap;

abstract class BaseUserQuestionPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'users_questions';

    /** the related Propel class for this table */
    const OM_CLASS = 'Smirik\\QuizBundle\\Model\\UserQuestion';

    /** the related TableMap class for this table */
    const TM_CLASS = 'UserQuestionTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 9;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 9;

    /** the column name for the ID field */
    const ID = 'users_questions.ID';

    /** the column name for the USER_ID field */
    const USER_ID = 'users_questions.USER_ID';

    /** the column name for the QUIZ_ID field */
    const QUIZ_ID = 'users_questions.QUIZ_ID';

    /** the column name for the QUESTION_ID field */
    const QUESTION_ID = 'users_questions.QUESTION_ID';

    /** the column name for the USER_QUIZ_ID field */
    const USER_QUIZ_ID = 'users_questions.USER_QUIZ_ID';

    /** the column name for the ANSWER_ID field */
    const ANSWER_ID = 'users_questions.ANSWER_ID';

    /** the column name for the ANSWER_TEXT field */
    const ANSWER_TEXT = 'users_questions.ANSWER_TEXT';

    /** the column name for the IS_RIGHT field */
    const IS_RIGHT = 'users_questions.IS_RIGHT';

    /** the column name for the IS_CLOSED field */
    const IS_CLOSED = 'users_questions.IS_CLOSED';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of UserQuestion objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array UserQuestion[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. UserQuestionPeer::$fieldNames[UserQuestionPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'UserId', 'QuizId', 'QuestionId', 'UserQuizId', 'AnswerId', 'AnswerText', 'IsRight', 'IsClosed', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'userId', 'quizId', 'questionId', 'userQuizId', 'answerId', 'answerText', 'isRight', 'isClosed', ),
        BasePeer::TYPE_COLNAME => array (UserQuestionPeer::ID, UserQuestionPeer::USER_ID, UserQuestionPeer::QUIZ_ID, UserQuestionPeer::QUESTION_ID, UserQuestionPeer::USER_QUIZ_ID, UserQuestionPeer::ANSWER_ID, UserQuestionPeer::ANSWER_TEXT, UserQuestionPeer::IS_RIGHT, UserQuestionPeer::IS_CLOSED, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'USER_ID', 'QUIZ_ID', 'QUESTION_ID', 'USER_QUIZ_ID', 'ANSWER_ID', 'ANSWER_TEXT', 'IS_RIGHT', 'IS_CLOSED', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'user_id', 'quiz_id', 'question_id', 'user_quiz_id', 'answer_id', 'answer_text', 'is_right', 'is_closed', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. UserQuestionPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'UserId' => 1, 'QuizId' => 2, 'QuestionId' => 3, 'UserQuizId' => 4, 'AnswerId' => 5, 'AnswerText' => 6, 'IsRight' => 7, 'IsClosed' => 8, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'userId' => 1, 'quizId' => 2, 'questionId' => 3, 'userQuizId' => 4, 'answerId' => 5, 'answerText' => 6, 'isRight' => 7, 'isClosed' => 8, ),
        BasePeer::TYPE_COLNAME => array (UserQuestionPeer::ID => 0, UserQuestionPeer::USER_ID => 1, UserQuestionPeer::QUIZ_ID => 2, UserQuestionPeer::QUESTION_ID => 3, UserQuestionPeer::USER_QUIZ_ID => 4, UserQuestionPeer::ANSWER_ID => 5, UserQuestionPeer::ANSWER_TEXT => 6, UserQuestionPeer::IS_RIGHT => 7, UserQuestionPeer::IS_CLOSED => 8, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'USER_ID' => 1, 'QUIZ_ID' => 2, 'QUESTION_ID' => 3, 'USER_QUIZ_ID' => 4, 'ANSWER_ID' => 5, 'ANSWER_TEXT' => 6, 'IS_RIGHT' => 7, 'IS_CLOSED' => 8, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'user_id' => 1, 'quiz_id' => 2, 'question_id' => 3, 'user_quiz_id' => 4, 'answer_id' => 5, 'answer_text' => 6, 'is_right' => 7, 'is_closed' => 8, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = UserQuestionPeer::getFieldNames($toType);
        $key = isset(UserQuestionPeer::$fieldKeys[$fromType][$name]) ? UserQuestionPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(UserQuestionPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, UserQuestionPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return UserQuestionPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. UserQuestionPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(UserQuestionPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(UserQuestionPeer::ID);
            $criteria->addSelectColumn(UserQuestionPeer::USER_ID);
            $criteria->addSelectColumn(UserQuestionPeer::QUIZ_ID);
            $criteria->addSelectColumn(UserQuestionPeer::QUESTION_ID);
            $criteria->addSelectColumn(UserQuestionPeer::USER_QUIZ_ID);
            $criteria->addSelectColumn(UserQuestionPeer::ANSWER_ID);
            $criteria->addSelectColumn(UserQuestionPeer::ANSWER_TEXT);
            $criteria->addSelectColumn(UserQuestionPeer::IS_RIGHT);
            $criteria->addSelectColumn(UserQuestionPeer::IS_CLOSED);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
            $criteria->addSelectColumn($alias . '.QUIZ_ID');
            $criteria->addSelectColumn($alias . '.QUESTION_ID');
            $criteria->addSelectColumn($alias . '.USER_QUIZ_ID');
            $criteria->addSelectColumn($alias . '.ANSWER_ID');
            $criteria->addSelectColumn($alias . '.ANSWER_TEXT');
            $criteria->addSelectColumn($alias . '.IS_RIGHT');
            $criteria->addSelectColumn($alias . '.IS_CLOSED');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return                 UserQuestion
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = UserQuestionPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return UserQuestionPeer::populateObjects(UserQuestionPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement durirectly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            UserQuestionPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param      UserQuestion $obj A UserQuestion object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            UserQuestionPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A UserQuestion object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof UserQuestion) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or UserQuestion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(UserQuestionPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   UserQuestion Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(UserQuestionPeer::$instances[$key])) {
                return UserQuestionPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool()
    {
        UserQuestionPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to users_questions
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = UserQuestionPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = UserQuestionPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserQuestionPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (UserQuestion object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = UserQuestionPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = UserQuestionPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserQuestionPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            UserQuestionPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Question table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinQuestion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Quiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Answer table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAnswer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserQuiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUserQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with their Question objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinQuestion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol = UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        QuestionPeer::addSelectColumns($criteria);

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = QuestionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UserQuestion) to $obj2 (Question)
                $obj2->addUserQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with their Quiz objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol = UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        QuizPeer::addSelectColumns($criteria);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = QuizPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = QuizPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    QuizPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UserQuestion) to $obj2 (Quiz)
                $obj2->addUserQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol = UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UserQuestion) to $obj2 (User)
                $obj2->addUserQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with their Answer objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAnswer(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol = UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        AnswerPeer::addSelectColumns($criteria);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AnswerPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AnswerPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AnswerPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UserQuestion) to $obj2 (Answer)
                $obj2->addUserQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with their UserQuiz objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUserQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol = UserQuestionPeer::NUM_HYDRATE_COLUMNS;
        UserQuizPeer::addSelectColumns($criteria);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserQuizPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserQuizPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserQuizPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (UserQuestion) to $obj2 (UserQuiz)
                $obj2->addUserQuestion($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuestionPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + QuizPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        AnswerPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AnswerPeer::NUM_HYDRATE_COLUMNS;

        UserQuizPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + UserQuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Question rows

            $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = QuestionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Question)
                $obj2->addUserQuestion($obj1);
            } // if joined row not null

            // Add objects for joined Quiz rows

            $key3 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = QuizPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = QuizPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    QuizPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (Quiz)
                $obj3->addUserQuestion($obj1);
            } // if joined row not null

            // Add objects for joined User rows

            $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = UserPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (User)
                $obj4->addUserQuestion($obj1);
            } // if joined row not null

            // Add objects for joined Answer rows

            $key5 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = AnswerPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = AnswerPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    AnswerPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (Answer)
                $obj5->addUserQuestion($obj1);
            } // if joined row not null

            // Add objects for joined UserQuiz rows

            $key6 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = UserQuizPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = UserQuizPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    UserQuizPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj6 (UserQuiz)
                $obj6->addUserQuestion($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Question table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptQuestion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Quiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Answer table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptAnswer(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserQuiz table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUserQuiz(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            UserQuestionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects except Question.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptQuestion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuizPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        AnswerPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AnswerPeer::NUM_HYDRATE_COLUMNS;

        UserQuizPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserQuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Quiz rows

                $key2 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuizPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuizPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuizPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Quiz)
                $obj2->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (User)
                $obj3->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Answer rows

                $key4 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AnswerPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AnswerPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AnswerPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (Answer)
                $obj4->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined UserQuiz rows

                $key5 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserQuizPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserQuizPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserQuizPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (UserQuiz)
                $obj5->addUserQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects except Quiz.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuestionPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserPeer::NUM_HYDRATE_COLUMNS;

        AnswerPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AnswerPeer::NUM_HYDRATE_COLUMNS;

        UserQuizPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserQuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Question rows

                $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuestionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Question)
                $obj2->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key3 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (User)
                $obj3->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Answer rows

                $key4 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AnswerPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AnswerPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AnswerPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (Answer)
                $obj4->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined UserQuiz rows

                $key5 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserQuizPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserQuizPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserQuizPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (UserQuiz)
                $obj5->addUserQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuestionPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + QuizPeer::NUM_HYDRATE_COLUMNS;

        AnswerPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AnswerPeer::NUM_HYDRATE_COLUMNS;

        UserQuizPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserQuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Question rows

                $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuestionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Question)
                $obj2->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Quiz rows

                $key3 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = QuizPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = QuizPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    QuizPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (Quiz)
                $obj3->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Answer rows

                $key4 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AnswerPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AnswerPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AnswerPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (Answer)
                $obj4->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined UserQuiz rows

                $key5 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserQuizPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserQuizPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserQuizPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (UserQuiz)
                $obj5->addUserQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects except Answer.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptAnswer(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuestionPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + QuizPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserQuizPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + UserQuizPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_QUIZ_ID, UserQuizPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Question rows

                $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuestionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Question)
                $obj2->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Quiz rows

                $key3 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = QuizPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = QuizPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    QuizPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (Quiz)
                $obj3->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = UserPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (User)
                $obj4->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined UserQuiz rows

                $key5 = UserQuizPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = UserQuizPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = UserQuizPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    UserQuizPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (UserQuiz)
                $obj5->addUserQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of UserQuestion objects pre-filled with all related objects except UserQuiz.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of UserQuestion objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUserQuiz(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);
        }

        UserQuestionPeer::addSelectColumns($criteria);
        $startcol2 = UserQuestionPeer::NUM_HYDRATE_COLUMNS;

        QuestionPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + QuestionPeer::NUM_HYDRATE_COLUMNS;

        QuizPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + QuizPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + UserPeer::NUM_HYDRATE_COLUMNS;

        AnswerPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AnswerPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(UserQuestionPeer::QUESTION_ID, QuestionPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::QUIZ_ID, QuizPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(UserQuestionPeer::ANSWER_ID, AnswerPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = UserQuestionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = UserQuestionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = UserQuestionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                UserQuestionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Question rows

                $key2 = QuestionPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = QuestionPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = QuestionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    QuestionPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj2 (Question)
                $obj2->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Quiz rows

                $key3 = QuizPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = QuizPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = QuizPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    QuizPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj3 (Quiz)
                $obj3->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined User rows

                $key4 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = UserPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = UserPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    UserPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj4 (User)
                $obj4->addUserQuestion($obj1);

            } // if joined row is not null

                // Add objects for joined Answer rows

                $key5 = AnswerPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = AnswerPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = AnswerPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    AnswerPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (UserQuestion) to the collection in $obj5 (Answer)
                $obj5->addUserQuestion($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(UserQuestionPeer::DATABASE_NAME)->getTable(UserQuestionPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseUserQuestionPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseUserQuestionPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new UserQuestionTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass()
    {
        return UserQuestionPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a UserQuestion or Criteria object.
     *
     * @param      mixed $values Criteria or UserQuestion object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from UserQuestion object
        }

        if ($criteria->containsKey(UserQuestionPeer::ID) && $criteria->keyContainsValue(UserQuestionPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserQuestionPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a UserQuestion or Criteria object.
     *
     * @param      mixed $values Criteria or UserQuestion object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(UserQuestionPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(UserQuestionPeer::ID);
            $value = $criteria->remove(UserQuestionPeer::ID);
            if ($value) {
                $selectCriteria->add(UserQuestionPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(UserQuestionPeer::TABLE_NAME);
            }

        } else { // $values is UserQuestion object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the users_questions table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(UserQuestionPeer::TABLE_NAME, $con, UserQuestionPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserQuestionPeer::clearInstancePool();
            UserQuestionPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a UserQuestion or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or UserQuestion object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            UserQuestionPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof UserQuestion) { // it's a model object
            // invalidate the cache for this single object
            UserQuestionPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserQuestionPeer::DATABASE_NAME);
            $criteria->add(UserQuestionPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                UserQuestionPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(UserQuestionPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            UserQuestionPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given UserQuestion object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      UserQuestion $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(UserQuestionPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(UserQuestionPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(UserQuestionPeer::DATABASE_NAME, UserQuestionPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return UserQuestion
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = UserQuestionPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(UserQuestionPeer::DATABASE_NAME);
        $criteria->add(UserQuestionPeer::ID, $pk);

        $v = UserQuestionPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return UserQuestion[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(UserQuestionPeer::DATABASE_NAME);
            $criteria->add(UserQuestionPeer::ID, $pks, Criteria::IN);
            $objs = UserQuestionPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseUserQuestionPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseUserQuestionPeer::buildTableMap();

