<?php

namespace Smirik\QuizBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuiz;
use Smirik\QuizBundle\Model\UserQuizPeer;
use Smirik\QuizBundle\Model\UserQuizQuery;

abstract class BaseUserQuiz extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\QuizBundle\\Model\\UserQuizPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserQuizPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the quiz_id field.
     * @var        int
     */
    protected $quiz_id;

    /**
     * The value for the questions field.
     * @var        string
     */
    protected $questions;

    /**
     * The value for the current field.
     * @var        int
     */
    protected $current;

    /**
     * The value for the num_right_answers field.
     * @var        int
     */
    protected $num_right_answers;

    /**
     * The value for the started_at field.
     * @var        string
     */
    protected $started_at;

    /**
     * The value for the stopped_at field.
     * @var        string
     */
    protected $stopped_at;

    /**
     * The value for the is_active field.
     * @var        boolean
     */
    protected $is_active;

    /**
     * The value for the is_closed field.
     * @var        boolean
     */
    protected $is_closed;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        Quiz
     */
    protected $aQuiz;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|UserQuestion[] Collection to store aggregation of UserQuestion objects.
     */
    protected $collUserQuestions;
    protected $collUserQuestionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userQuestionsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [quiz_id] column value.
     *
     * @return int
     */
    public function getQuizId()
    {
        return $this->quiz_id;
    }

    /**
     * Get the [questions] column value.
     *
     * @return string
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Get the [current] column value.
     *
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Get the [num_right_answers] column value.
     *
     * @return int
     */
    public function getNumRightAnswers()
    {
        return $this->num_right_answers;
    }

    /**
     * Get the [optionally formatted] temporal [started_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStartedAt($format = null)
    {
        if ($this->started_at === null) {
            return null;
        }

        if ($this->started_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->started_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->started_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [optionally formatted] temporal [stopped_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStoppedAt($format = null)
    {
        if ($this->stopped_at === null) {
            return null;
        }

        if ($this->stopped_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->stopped_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->stopped_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Get the [is_closed] column value.
     *
     * @return boolean
     */
    public function getIsClosed()
    {
        return $this->is_closed;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->created_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->updated_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserQuizPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = UserQuizPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [quiz_id] column.
     *
     * @param int $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setQuizId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->quiz_id !== $v) {
            $this->quiz_id = $v;
            $this->modifiedColumns[] = UserQuizPeer::QUIZ_ID;
        }

        if ($this->aQuiz !== null && $this->aQuiz->getId() !== $v) {
            $this->aQuiz = null;
        }


        return $this;
    } // setQuizId()

    /**
     * Set the value of [questions] column.
     *
     * @param string $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setQuestions($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->questions !== $v) {
            $this->questions = $v;
            $this->modifiedColumns[] = UserQuizPeer::QUESTIONS;
        }


        return $this;
    } // setQuestions()

    /**
     * Set the value of [current] column.
     *
     * @param int $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setCurrent($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->current !== $v) {
            $this->current = $v;
            $this->modifiedColumns[] = UserQuizPeer::CURRENT;
        }


        return $this;
    } // setCurrent()

    /**
     * Set the value of [num_right_answers] column.
     *
     * @param int $v new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setNumRightAnswers($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->num_right_answers !== $v) {
            $this->num_right_answers = $v;
            $this->modifiedColumns[] = UserQuizPeer::NUM_RIGHT_ANSWERS;
        }


        return $this;
    } // setNumRightAnswers()

    /**
     * Sets the value of [started_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setStartedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->started_at !== null || $dt !== null) {
            $currentDateAsString = ($this->started_at !== null && $tmpDt = new DateTime($this->started_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->started_at = $newDateAsString;
                $this->modifiedColumns[] = UserQuizPeer::STARTED_AT;
            }
        } // if either are not null


        return $this;
    } // setStartedAt()

    /**
     * Sets the value of [stopped_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setStoppedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->stopped_at !== null || $dt !== null) {
            $currentDateAsString = ($this->stopped_at !== null && $tmpDt = new DateTime($this->stopped_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->stopped_at = $newDateAsString;
                $this->modifiedColumns[] = UserQuizPeer::STOPPED_AT;
            }
        } // if either are not null


        return $this;
    } // setStoppedAt()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = UserQuizPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of the [is_closed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setIsClosed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_closed !== $v) {
            $this->is_closed = $v;
            $this->modifiedColumns[] = UserQuizPeer::IS_CLOSED;
        }


        return $this;
    } // setIsClosed()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = UserQuizPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return UserQuiz The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = UserQuizPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->quiz_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->questions = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->current = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->num_right_answers = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->started_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->stopped_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->is_active = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->is_closed = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
            $this->created_at = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->updated_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = UserQuizPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UserQuiz object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aQuiz !== null && $this->quiz_id !== $this->aQuiz->getId()) {
            $this->aQuiz = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserQuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserQuizPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aQuiz = null;
            $this->aUser = null;
            $this->collUserQuestions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuizQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(UserQuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(UserQuizPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserQuizPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserQuizPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserQuizPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aQuiz !== null) {
                if ($this->aQuiz->isModified() || $this->aQuiz->isNew()) {
                    $affectedRows += $this->aQuiz->save($con);
                }
                $this->setQuiz($this->aQuiz);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->userQuestionsScheduledForDeletion !== null) {
                if (!$this->userQuestionsScheduledForDeletion->isEmpty()) {
                    UserQuestionQuery::create()
                        ->filterByPrimaryKeys($this->userQuestionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->userQuestionsScheduledForDeletion = null;
                }
            }

            if ($this->collUserQuestions !== null) {
                foreach ($this->collUserQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = UserQuizPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserQuizPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserQuizPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(UserQuizPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }
        if ($this->isColumnModified(UserQuizPeer::QUIZ_ID)) {
            $modifiedColumns[':p' . $index++]  = '`QUIZ_ID`';
        }
        if ($this->isColumnModified(UserQuizPeer::QUESTIONS)) {
            $modifiedColumns[':p' . $index++]  = '`QUESTIONS`';
        }
        if ($this->isColumnModified(UserQuizPeer::CURRENT)) {
            $modifiedColumns[':p' . $index++]  = '`CURRENT`';
        }
        if ($this->isColumnModified(UserQuizPeer::NUM_RIGHT_ANSWERS)) {
            $modifiedColumns[':p' . $index++]  = '`NUM_RIGHT_ANSWERS`';
        }
        if ($this->isColumnModified(UserQuizPeer::STARTED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`STARTED_AT`';
        }
        if ($this->isColumnModified(UserQuizPeer::STOPPED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`STOPPED_AT`';
        }
        if ($this->isColumnModified(UserQuizPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`IS_ACTIVE`';
        }
        if ($this->isColumnModified(UserQuizPeer::IS_CLOSED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_CLOSED`';
        }
        if ($this->isColumnModified(UserQuizPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(UserQuizPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }

        $sql = sprintf(
            'INSERT INTO `users_quiz` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`USER_ID`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`QUIZ_ID`':
                        $stmt->bindValue($identifier, $this->quiz_id, PDO::PARAM_INT);
                        break;
                    case '`QUESTIONS`':
                        $stmt->bindValue($identifier, $this->questions, PDO::PARAM_STR);
                        break;
                    case '`CURRENT`':
                        $stmt->bindValue($identifier, $this->current, PDO::PARAM_INT);
                        break;
                    case '`NUM_RIGHT_ANSWERS`':
                        $stmt->bindValue($identifier, $this->num_right_answers, PDO::PARAM_INT);
                        break;
                    case '`STARTED_AT`':
                        $stmt->bindValue($identifier, $this->started_at, PDO::PARAM_STR);
                        break;
                    case '`STOPPED_AT`':
                        $stmt->bindValue($identifier, $this->stopped_at, PDO::PARAM_STR);
                        break;
                    case '`IS_ACTIVE`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                    case '`IS_CLOSED`':
                        $stmt->bindValue($identifier, (int) $this->is_closed, PDO::PARAM_INT);
                        break;
                    case '`CREATED_AT`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`UPDATED_AT`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        } else {
            $this->validationFailures = $res;

            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aQuiz !== null) {
                if (!$this->aQuiz->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aQuiz->getValidationFailures());
                }
            }

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = UserQuizPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collUserQuestions !== null) {
                    foreach ($this->collUserQuestions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserQuizPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUserId();
                break;
            case 2:
                return $this->getQuizId();
                break;
            case 3:
                return $this->getQuestions();
                break;
            case 4:
                return $this->getCurrent();
                break;
            case 5:
                return $this->getNumRightAnswers();
                break;
            case 6:
                return $this->getStartedAt();
                break;
            case 7:
                return $this->getStoppedAt();
                break;
            case 8:
                return $this->getIsActive();
                break;
            case 9:
                return $this->getIsClosed();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['UserQuiz'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserQuiz'][$this->getPrimaryKey()] = true;
        $keys = UserQuizPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getQuizId(),
            $keys[3] => $this->getQuestions(),
            $keys[4] => $this->getCurrent(),
            $keys[5] => $this->getNumRightAnswers(),
            $keys[6] => $this->getStartedAt(),
            $keys[7] => $this->getStoppedAt(),
            $keys[8] => $this->getIsActive(),
            $keys[9] => $this->getIsClosed(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aQuiz) {
                $result['Quiz'] = $this->aQuiz->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collUserQuestions) {
                $result['UserQuestions'] = $this->collUserQuestions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = UserQuizPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUserId($value);
                break;
            case 2:
                $this->setQuizId($value);
                break;
            case 3:
                $this->setQuestions($value);
                break;
            case 4:
                $this->setCurrent($value);
                break;
            case 5:
                $this->setNumRightAnswers($value);
                break;
            case 6:
                $this->setStartedAt($value);
                break;
            case 7:
                $this->setStoppedAt($value);
                break;
            case 8:
                $this->setIsActive($value);
                break;
            case 9:
                $this->setIsClosed($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = UserQuizPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setQuizId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setQuestions($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCurrent($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setNumRightAnswers($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setStartedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setStoppedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setIsActive($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setIsClosed($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCreatedAt($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setUpdatedAt($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserQuizPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserQuizPeer::ID)) $criteria->add(UserQuizPeer::ID, $this->id);
        if ($this->isColumnModified(UserQuizPeer::USER_ID)) $criteria->add(UserQuizPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(UserQuizPeer::QUIZ_ID)) $criteria->add(UserQuizPeer::QUIZ_ID, $this->quiz_id);
        if ($this->isColumnModified(UserQuizPeer::QUESTIONS)) $criteria->add(UserQuizPeer::QUESTIONS, $this->questions);
        if ($this->isColumnModified(UserQuizPeer::CURRENT)) $criteria->add(UserQuizPeer::CURRENT, $this->current);
        if ($this->isColumnModified(UserQuizPeer::NUM_RIGHT_ANSWERS)) $criteria->add(UserQuizPeer::NUM_RIGHT_ANSWERS, $this->num_right_answers);
        if ($this->isColumnModified(UserQuizPeer::STARTED_AT)) $criteria->add(UserQuizPeer::STARTED_AT, $this->started_at);
        if ($this->isColumnModified(UserQuizPeer::STOPPED_AT)) $criteria->add(UserQuizPeer::STOPPED_AT, $this->stopped_at);
        if ($this->isColumnModified(UserQuizPeer::IS_ACTIVE)) $criteria->add(UserQuizPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(UserQuizPeer::IS_CLOSED)) $criteria->add(UserQuizPeer::IS_CLOSED, $this->is_closed);
        if ($this->isColumnModified(UserQuizPeer::CREATED_AT)) $criteria->add(UserQuizPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(UserQuizPeer::UPDATED_AT)) $criteria->add(UserQuizPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(UserQuizPeer::DATABASE_NAME);
        $criteria->add(UserQuizPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of UserQuiz (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setQuizId($this->getQuizId());
        $copyObj->setQuestions($this->getQuestions());
        $copyObj->setCurrent($this->getCurrent());
        $copyObj->setNumRightAnswers($this->getNumRightAnswers());
        $copyObj->setStartedAt($this->getStartedAt());
        $copyObj->setStoppedAt($this->getStoppedAt());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setIsClosed($this->getIsClosed());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getUserQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserQuestion($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return UserQuiz Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return UserQuizPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserQuizPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Quiz object.
     *
     * @param             Quiz $v
     * @return UserQuiz The current object (for fluent API support)
     * @throws PropelException
     */
    public function setQuiz(Quiz $v = null)
    {
        if ($v === null) {
            $this->setQuizId(NULL);
        } else {
            $this->setQuizId($v->getId());
        }

        $this->aQuiz = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Quiz object, it will not be re-added.
        if ($v !== null) {
            $v->addUserQuiz($this);
        }


        return $this;
    }


    /**
     * Get the associated Quiz object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Quiz The associated Quiz object.
     * @throws PropelException
     */
    public function getQuiz(PropelPDO $con = null)
    {
        if ($this->aQuiz === null && ($this->quiz_id !== null)) {
            $this->aQuiz = QuizQuery::create()->findPk($this->quiz_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aQuiz->addUserquizzes($this);
             */
        }

        return $this->aQuiz;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return UserQuiz The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addUserQuiz($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addUserquizzes($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('UserQuestion' == $relationName) {
            $this->initUserQuestions();
        }
    }

    /**
     * Clears out the collUserQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserQuestions()
     */
    public function clearUserQuestions()
    {
        $this->collUserQuestions = null; // important to set this to null since that means it is uninitialized
        $this->collUserQuestionsPartial = null;
    }

    /**
     * reset is the collUserQuestions collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserQuestions($v = true)
    {
        $this->collUserQuestionsPartial = $v;
    }

    /**
     * Initializes the collUserQuestions collection.
     *
     * By default this just sets the collUserQuestions collection to an empty array (like clearcollUserQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserQuestions($overrideExisting = true)
    {
        if (null !== $this->collUserQuestions && !$overrideExisting) {
            return;
        }
        $this->collUserQuestions = new PropelObjectCollection();
        $this->collUserQuestions->setModel('UserQuestion');
    }

    /**
     * Gets an array of UserQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this UserQuiz is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     * @throws PropelException
     */
    public function getUserQuestions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserQuestionsPartial && !$this->isNew();
        if (null === $this->collUserQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserQuestions) {
                // return empty collection
                $this->initUserQuestions();
            } else {
                $collUserQuestions = UserQuestionQuery::create(null, $criteria)
                    ->filterByUserQuiz($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserQuestionsPartial && count($collUserQuestions)) {
                      $this->initUserQuestions(false);

                      foreach($collUserQuestions as $obj) {
                        if (false == $this->collUserQuestions->contains($obj)) {
                          $this->collUserQuestions->append($obj);
                        }
                      }

                      $this->collUserQuestionsPartial = true;
                    }

                    return $collUserQuestions;
                }

                if($partial && $this->collUserQuestions) {
                    foreach($this->collUserQuestions as $obj) {
                        if($obj->isNew()) {
                            $collUserQuestions[] = $obj;
                        }
                    }
                }

                $this->collUserQuestions = $collUserQuestions;
                $this->collUserQuestionsPartial = false;
            }
        }

        return $this->collUserQuestions;
    }

    /**
     * Sets a collection of UserQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userQuestions A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserQuestions(PropelCollection $userQuestions, PropelPDO $con = null)
    {
        $this->userQuestionsScheduledForDeletion = $this->getUserQuestions(new Criteria(), $con)->diff($userQuestions);

        foreach ($this->userQuestionsScheduledForDeletion as $userQuestionRemoved) {
            $userQuestionRemoved->setUserQuiz(null);
        }

        $this->collUserQuestions = null;
        foreach ($userQuestions as $userQuestion) {
            $this->addUserQuestion($userQuestion);
        }

        $this->collUserQuestions = $userQuestions;
        $this->collUserQuestionsPartial = false;
    }

    /**
     * Returns the number of related UserQuestion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserQuestion objects.
     * @throws PropelException
     */
    public function countUserQuestions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserQuestionsPartial && !$this->isNew();
        if (null === $this->collUserQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserQuestions) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserQuestions());
                }
                $query = UserQuestionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByUserQuiz($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserQuestions);
        }
    }

    /**
     * Method called to associate a UserQuestion object to this object
     * through the UserQuestion foreign key attribute.
     *
     * @param    UserQuestion $l UserQuestion
     * @return UserQuiz The current object (for fluent API support)
     */
    public function addUserQuestion(UserQuestion $l)
    {
        if ($this->collUserQuestions === null) {
            $this->initUserQuestions();
            $this->collUserQuestionsPartial = true;
        }
        if (!in_array($l, $this->collUserQuestions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserQuestion($l);
        }

        return $this;
    }

    /**
     * @param	UserQuestion $userQuestion The userQuestion object to add.
     */
    protected function doAddUserQuestion($userQuestion)
    {
        $this->collUserQuestions[]= $userQuestion;
        $userQuestion->setUserQuiz($this);
    }

    /**
     * @param	UserQuestion $userQuestion The userQuestion object to remove.
     */
    public function removeUserQuestion($userQuestion)
    {
        if ($this->getUserQuestions()->contains($userQuestion)) {
            $this->collUserQuestions->remove($this->collUserQuestions->search($userQuestion));
            if (null === $this->userQuestionsScheduledForDeletion) {
                $this->userQuestionsScheduledForDeletion = clone $this->collUserQuestions;
                $this->userQuestionsScheduledForDeletion->clear();
            }
            $this->userQuestionsScheduledForDeletion[]= $userQuestion;
            $userQuestion->setUserQuiz(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserQuiz is new, it will return
     * an empty collection; or if this UserQuiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserQuiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     */
    public function getUserQuestionsJoinQuestion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuestionQuery::create(null, $criteria);
        $query->joinWith('Question', $join_behavior);

        return $this->getUserQuestions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserQuiz is new, it will return
     * an empty collection; or if this UserQuiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserQuiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     */
    public function getUserQuestionsJoinQuiz($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuestionQuery::create(null, $criteria);
        $query->joinWith('Quiz', $join_behavior);

        return $this->getUserQuestions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserQuiz is new, it will return
     * an empty collection; or if this UserQuiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserQuiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     */
    public function getUserQuestionsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuestionQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserQuestions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this UserQuiz is new, it will return
     * an empty collection; or if this UserQuiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in UserQuiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     */
    public function getUserQuestionsJoinAnswer($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuestionQuery::create(null, $criteria);
        $query->joinWith('Answer', $join_behavior);

        return $this->getUserQuestions($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->quiz_id = null;
        $this->questions = null;
        $this->current = null;
        $this->num_right_answers = null;
        $this->started_at = null;
        $this->stopped_at = null;
        $this->is_active = null;
        $this->is_closed = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUserQuestions) {
                foreach ($this->collUserQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collUserQuestions instanceof PropelCollection) {
            $this->collUserQuestions->clearIterator();
        }
        $this->collUserQuestions = null;
        $this->aQuiz = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserQuizPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     UserQuiz The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = UserQuizPeer::UPDATED_AT;

        return $this;
    }

}
