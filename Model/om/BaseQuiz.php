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
use Smirik\CourseBundle\Model\LessonQuiz;
use Smirik\CourseBundle\Model\LessonQuizQuery;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\QuizPeer;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\QuizQuestion;
use Smirik\QuizBundle\Model\QuizQuestionQuery;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuiz;
use Smirik\QuizBundle\Model\UserQuizQuery;

abstract class BaseQuiz extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\QuizBundle\\Model\\QuizPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        QuizPeer
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
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the time field.
     * @var        int
     */
    protected $time;

    /**
     * The value for the num_questions field.
     * @var        int
     */
    protected $num_questions;

    /**
     * The value for the is_active field.
     * @var        boolean
     */
    protected $is_active;

    /**
     * The value for the is_opened field.
     * @var        boolean
     */
    protected $is_opened;

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
     * @var        PropelObjectCollection|LessonQuiz[] Collection to store aggregation of LessonQuiz objects.
     */
    protected $collLessonquizzes;
    protected $collLessonquizzesPartial;

    /**
     * @var        PropelObjectCollection|QuizQuestion[] Collection to store aggregation of QuizQuestion objects.
     */
    protected $collQuizQuestions;
    protected $collQuizQuestionsPartial;

    /**
     * @var        PropelObjectCollection|UserQuestion[] Collection to store aggregation of UserQuestion objects.
     */
    protected $collUserQuestions;
    protected $collUserQuestionsPartial;

    /**
     * @var        PropelObjectCollection|UserQuiz[] Collection to store aggregation of UserQuiz objects.
     */
    protected $collUserquizzes;
    protected $collUserquizzesPartial;

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
    protected $lessonquizzesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $quizQuestionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userQuestionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $userquizzesScheduledForDeletion = null;

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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [time] column value.
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get the [num_questions] column value.
     *
     * @return int
     */
    public function getNumQuestions()
    {
        return $this->num_questions;
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
     * Get the [is_opened] column value.
     *
     * @return boolean
     */
    public function getIsOpened()
    {
        return $this->is_opened;
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
     * @return Quiz The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = QuizPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Quiz The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = QuizPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return Quiz The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = QuizPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [time] column.
     *
     * @param int $v new value
     * @return Quiz The current object (for fluent API support)
     */
    public function setTime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->time !== $v) {
            $this->time = $v;
            $this->modifiedColumns[] = QuizPeer::TIME;
        }


        return $this;
    } // setTime()

    /**
     * Set the value of [num_questions] column.
     *
     * @param int $v new value
     * @return Quiz The current object (for fluent API support)
     */
    public function setNumQuestions($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->num_questions !== $v) {
            $this->num_questions = $v;
            $this->modifiedColumns[] = QuizPeer::NUM_QUESTIONS;
        }


        return $this;
    } // setNumQuestions()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Quiz The current object (for fluent API support)
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
            $this->modifiedColumns[] = QuizPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of the [is_opened] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Quiz The current object (for fluent API support)
     */
    public function setIsOpened($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_opened !== $v) {
            $this->is_opened = $v;
            $this->modifiedColumns[] = QuizPeer::IS_OPENED;
        }


        return $this;
    } // setIsOpened()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Quiz The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = QuizPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Quiz The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = QuizPeer::UPDATED_AT;
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
            $this->title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->time = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->num_questions = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->is_active = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->is_opened = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->created_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->updated_at = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = QuizPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Quiz object", $e);
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
            $con = Propel::getConnection(QuizPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = QuizPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collLessonquizzes = null;

            $this->collQuizQuestions = null;

            $this->collUserQuestions = null;

            $this->collUserquizzes = null;

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
            $con = Propel::getConnection(QuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = QuizQuery::create()
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
            $con = Propel::getConnection(QuizPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(QuizPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(QuizPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(QuizPeer::UPDATED_AT)) {
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
                QuizPeer::addInstanceToPool($this);
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

            if ($this->lessonquizzesScheduledForDeletion !== null) {
                if (!$this->lessonquizzesScheduledForDeletion->isEmpty()) {
                    LessonQuizQuery::create()
                        ->filterByPrimaryKeys($this->lessonquizzesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lessonquizzesScheduledForDeletion = null;
                }
            }

            if ($this->collLessonquizzes !== null) {
                foreach ($this->collLessonquizzes as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->quizQuestionsScheduledForDeletion !== null) {
                if (!$this->quizQuestionsScheduledForDeletion->isEmpty()) {
                    QuizQuestionQuery::create()
                        ->filterByPrimaryKeys($this->quizQuestionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->quizQuestionsScheduledForDeletion = null;
                }
            }

            if ($this->collQuizQuestions !== null) {
                foreach ($this->collQuizQuestions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

            if ($this->userquizzesScheduledForDeletion !== null) {
                if (!$this->userquizzesScheduledForDeletion->isEmpty()) {
                    foreach ($this->userquizzesScheduledForDeletion as $userQuiz) {
                        // need to save related object because we set the relation to null
                        $userQuiz->save($con);
                    }
                    $this->userquizzesScheduledForDeletion = null;
                }
            }

            if ($this->collUserquizzes !== null) {
                foreach ($this->collUserquizzes as $referrerFK) {
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

        $this->modifiedColumns[] = QuizPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . QuizPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(QuizPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(QuizPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(QuizPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`DESCRIPTION`';
        }
        if ($this->isColumnModified(QuizPeer::TIME)) {
            $modifiedColumns[':p' . $index++]  = '`TIME`';
        }
        if ($this->isColumnModified(QuizPeer::NUM_QUESTIONS)) {
            $modifiedColumns[':p' . $index++]  = '`NUM_QUESTIONS`';
        }
        if ($this->isColumnModified(QuizPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`IS_ACTIVE`';
        }
        if ($this->isColumnModified(QuizPeer::IS_OPENED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_OPENED`';
        }
        if ($this->isColumnModified(QuizPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(QuizPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }

        $sql = sprintf(
            'INSERT INTO `quiz` (%s) VALUES (%s)',
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
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`DESCRIPTION`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`TIME`':
                        $stmt->bindValue($identifier, $this->time, PDO::PARAM_INT);
                        break;
                    case '`NUM_QUESTIONS`':
                        $stmt->bindValue($identifier, $this->num_questions, PDO::PARAM_INT);
                        break;
                    case '`IS_ACTIVE`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                    case '`IS_OPENED`':
                        $stmt->bindValue($identifier, (int) $this->is_opened, PDO::PARAM_INT);
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


            if (($retval = QuizPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collLessonquizzes !== null) {
                    foreach ($this->collLessonquizzes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collQuizQuestions !== null) {
                    foreach ($this->collQuizQuestions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserQuestions !== null) {
                    foreach ($this->collUserQuestions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collUserquizzes !== null) {
                    foreach ($this->collUserquizzes as $referrerFK) {
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
        $pos = QuizPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitle();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getTime();
                break;
            case 4:
                return $this->getNumQuestions();
                break;
            case 5:
                return $this->getIsActive();
                break;
            case 6:
                return $this->getIsOpened();
                break;
            case 7:
                return $this->getCreatedAt();
                break;
            case 8:
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
        if (isset($alreadyDumpedObjects['Quiz'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Quiz'][$this->getPrimaryKey()] = true;
        $keys = QuizPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getTime(),
            $keys[4] => $this->getNumQuestions(),
            $keys[5] => $this->getIsActive(),
            $keys[6] => $this->getIsOpened(),
            $keys[7] => $this->getCreatedAt(),
            $keys[8] => $this->getUpdatedAt(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collLessonquizzes) {
                $result['Lessonquizzes'] = $this->collLessonquizzes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collQuizQuestions) {
                $result['QuizQuestions'] = $this->collQuizQuestions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserQuestions) {
                $result['UserQuestions'] = $this->collUserQuestions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collUserquizzes) {
                $result['Userquizzes'] = $this->collUserquizzes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = QuizPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitle($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setTime($value);
                break;
            case 4:
                $this->setNumQuestions($value);
                break;
            case 5:
                $this->setIsActive($value);
                break;
            case 6:
                $this->setIsOpened($value);
                break;
            case 7:
                $this->setCreatedAt($value);
                break;
            case 8:
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
        $keys = QuizPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setTime($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setNumQuestions($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setIsActive($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setIsOpened($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCreatedAt($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUpdatedAt($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(QuizPeer::DATABASE_NAME);

        if ($this->isColumnModified(QuizPeer::ID)) $criteria->add(QuizPeer::ID, $this->id);
        if ($this->isColumnModified(QuizPeer::TITLE)) $criteria->add(QuizPeer::TITLE, $this->title);
        if ($this->isColumnModified(QuizPeer::DESCRIPTION)) $criteria->add(QuizPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(QuizPeer::TIME)) $criteria->add(QuizPeer::TIME, $this->time);
        if ($this->isColumnModified(QuizPeer::NUM_QUESTIONS)) $criteria->add(QuizPeer::NUM_QUESTIONS, $this->num_questions);
        if ($this->isColumnModified(QuizPeer::IS_ACTIVE)) $criteria->add(QuizPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(QuizPeer::IS_OPENED)) $criteria->add(QuizPeer::IS_OPENED, $this->is_opened);
        if ($this->isColumnModified(QuizPeer::CREATED_AT)) $criteria->add(QuizPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(QuizPeer::UPDATED_AT)) $criteria->add(QuizPeer::UPDATED_AT, $this->updated_at);

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
        $criteria = new Criteria(QuizPeer::DATABASE_NAME);
        $criteria->add(QuizPeer::ID, $this->id);

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
     * @param object $copyObj An object of Quiz (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setTime($this->getTime());
        $copyObj->setNumQuestions($this->getNumQuestions());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setIsOpened($this->getIsOpened());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getLessonquizzes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLessonQuiz($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getQuizQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuizQuestion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserQuestions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserQuestion($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getUserquizzes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUserQuiz($relObj->copy($deepCopy));
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
     * @return Quiz Clone of current object.
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
     * @return QuizPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new QuizPeer();
        }

        return self::$peer;
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
        if ('LessonQuiz' == $relationName) {
            $this->initLessonquizzes();
        }
        if ('QuizQuestion' == $relationName) {
            $this->initQuizQuestions();
        }
        if ('UserQuestion' == $relationName) {
            $this->initUserQuestions();
        }
        if ('UserQuiz' == $relationName) {
            $this->initUserquizzes();
        }
    }

    /**
     * Clears out the collLessonquizzes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLessonquizzes()
     */
    public function clearLessonquizzes()
    {
        $this->collLessonquizzes = null; // important to set this to null since that means it is uninitialized
        $this->collLessonquizzesPartial = null;
    }

    /**
     * reset is the collLessonquizzes collection loaded partially
     *
     * @return void
     */
    public function resetPartialLessonquizzes($v = true)
    {
        $this->collLessonquizzesPartial = $v;
    }

    /**
     * Initializes the collLessonquizzes collection.
     *
     * By default this just sets the collLessonquizzes collection to an empty array (like clearcollLessonquizzes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLessonquizzes($overrideExisting = true)
    {
        if (null !== $this->collLessonquizzes && !$overrideExisting) {
            return;
        }
        $this->collLessonquizzes = new PropelObjectCollection();
        $this->collLessonquizzes->setModel('LessonQuiz');
    }

    /**
     * Gets an array of LessonQuiz objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Quiz is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|LessonQuiz[] List of LessonQuiz objects
     * @throws PropelException
     */
    public function getLessonquizzes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collLessonquizzesPartial && !$this->isNew();
        if (null === $this->collLessonquizzes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLessonquizzes) {
                // return empty collection
                $this->initLessonquizzes();
            } else {
                $collLessonquizzes = LessonQuizQuery::create(null, $criteria)
                    ->filterByQuiz($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collLessonquizzesPartial && count($collLessonquizzes)) {
                      $this->initLessonquizzes(false);

                      foreach($collLessonquizzes as $obj) {
                        if (false == $this->collLessonquizzes->contains($obj)) {
                          $this->collLessonquizzes->append($obj);
                        }
                      }

                      $this->collLessonquizzesPartial = true;
                    }

                    return $collLessonquizzes;
                }

                if($partial && $this->collLessonquizzes) {
                    foreach($this->collLessonquizzes as $obj) {
                        if($obj->isNew()) {
                            $collLessonquizzes[] = $obj;
                        }
                    }
                }

                $this->collLessonquizzes = $collLessonquizzes;
                $this->collLessonquizzesPartial = false;
            }
        }

        return $this->collLessonquizzes;
    }

    /**
     * Sets a collection of LessonQuiz objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $lessonquizzes A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setLessonquizzes(PropelCollection $lessonquizzes, PropelPDO $con = null)
    {
        $this->lessonquizzesScheduledForDeletion = $this->getLessonquizzes(new Criteria(), $con)->diff($lessonquizzes);

        foreach ($this->lessonquizzesScheduledForDeletion as $lessonQuizRemoved) {
            $lessonQuizRemoved->setQuiz(null);
        }

        $this->collLessonquizzes = null;
        foreach ($lessonquizzes as $lessonQuiz) {
            $this->addLessonQuiz($lessonQuiz);
        }

        $this->collLessonquizzes = $lessonquizzes;
        $this->collLessonquizzesPartial = false;
    }

    /**
     * Returns the number of related LessonQuiz objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related LessonQuiz objects.
     * @throws PropelException
     */
    public function countLessonquizzes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collLessonquizzesPartial && !$this->isNew();
        if (null === $this->collLessonquizzes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLessonquizzes) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getLessonquizzes());
                }
                $query = LessonQuizQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByQuiz($this)
                    ->count($con);
            }
        } else {
            return count($this->collLessonquizzes);
        }
    }

    /**
     * Method called to associate a LessonQuiz object to this object
     * through the LessonQuiz foreign key attribute.
     *
     * @param    LessonQuiz $l LessonQuiz
     * @return Quiz The current object (for fluent API support)
     */
    public function addLessonQuiz(LessonQuiz $l)
    {
        if ($this->collLessonquizzes === null) {
            $this->initLessonquizzes();
            $this->collLessonquizzesPartial = true;
        }
        if (!in_array($l, $this->collLessonquizzes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddLessonQuiz($l);
        }

        return $this;
    }

    /**
     * @param	LessonQuiz $lessonQuiz The lessonQuiz object to add.
     */
    protected function doAddLessonQuiz($lessonQuiz)
    {
        $this->collLessonquizzes[]= $lessonQuiz;
        $lessonQuiz->setQuiz($this);
    }

    /**
     * @param	LessonQuiz $lessonQuiz The lessonQuiz object to remove.
     */
    public function removeLessonQuiz($lessonQuiz)
    {
        if ($this->getLessonquizzes()->contains($lessonQuiz)) {
            $this->collLessonquizzes->remove($this->collLessonquizzes->search($lessonQuiz));
            if (null === $this->lessonquizzesScheduledForDeletion) {
                $this->lessonquizzesScheduledForDeletion = clone $this->collLessonquizzes;
                $this->lessonquizzesScheduledForDeletion->clear();
            }
            $this->lessonquizzesScheduledForDeletion[]= $lessonQuiz;
            $lessonQuiz->setQuiz(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related Lessonquizzes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|LessonQuiz[] List of LessonQuiz objects
     */
    public function getLessonquizzesJoinLesson($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = LessonQuizQuery::create(null, $criteria);
        $query->joinWith('Lesson', $join_behavior);

        return $this->getLessonquizzes($query, $con);
    }

    /**
     * Clears out the collQuizQuestions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuizQuestions()
     */
    public function clearQuizQuestions()
    {
        $this->collQuizQuestions = null; // important to set this to null since that means it is uninitialized
        $this->collQuizQuestionsPartial = null;
    }

    /**
     * reset is the collQuizQuestions collection loaded partially
     *
     * @return void
     */
    public function resetPartialQuizQuestions($v = true)
    {
        $this->collQuizQuestionsPartial = $v;
    }

    /**
     * Initializes the collQuizQuestions collection.
     *
     * By default this just sets the collQuizQuestions collection to an empty array (like clearcollQuizQuestions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuizQuestions($overrideExisting = true)
    {
        if (null !== $this->collQuizQuestions && !$overrideExisting) {
            return;
        }
        $this->collQuizQuestions = new PropelObjectCollection();
        $this->collQuizQuestions->setModel('QuizQuestion');
    }

    /**
     * Gets an array of QuizQuestion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Quiz is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|QuizQuestion[] List of QuizQuestion objects
     * @throws PropelException
     */
    public function getQuizQuestions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collQuizQuestionsPartial && !$this->isNew();
        if (null === $this->collQuizQuestions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQuizQuestions) {
                // return empty collection
                $this->initQuizQuestions();
            } else {
                $collQuizQuestions = QuizQuestionQuery::create(null, $criteria)
                    ->filterByQuiz($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collQuizQuestionsPartial && count($collQuizQuestions)) {
                      $this->initQuizQuestions(false);

                      foreach($collQuizQuestions as $obj) {
                        if (false == $this->collQuizQuestions->contains($obj)) {
                          $this->collQuizQuestions->append($obj);
                        }
                      }

                      $this->collQuizQuestionsPartial = true;
                    }

                    return $collQuizQuestions;
                }

                if($partial && $this->collQuizQuestions) {
                    foreach($this->collQuizQuestions as $obj) {
                        if($obj->isNew()) {
                            $collQuizQuestions[] = $obj;
                        }
                    }
                }

                $this->collQuizQuestions = $collQuizQuestions;
                $this->collQuizQuestionsPartial = false;
            }
        }

        return $this->collQuizQuestions;
    }

    /**
     * Sets a collection of QuizQuestion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $quizQuestions A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setQuizQuestions(PropelCollection $quizQuestions, PropelPDO $con = null)
    {
        $this->quizQuestionsScheduledForDeletion = $this->getQuizQuestions(new Criteria(), $con)->diff($quizQuestions);

        foreach ($this->quizQuestionsScheduledForDeletion as $quizQuestionRemoved) {
            $quizQuestionRemoved->setQuiz(null);
        }

        $this->collQuizQuestions = null;
        foreach ($quizQuestions as $quizQuestion) {
            $this->addQuizQuestion($quizQuestion);
        }

        $this->collQuizQuestions = $quizQuestions;
        $this->collQuizQuestionsPartial = false;
    }

    /**
     * Returns the number of related QuizQuestion objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related QuizQuestion objects.
     * @throws PropelException
     */
    public function countQuizQuestions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collQuizQuestionsPartial && !$this->isNew();
        if (null === $this->collQuizQuestions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuizQuestions) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getQuizQuestions());
                }
                $query = QuizQuestionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByQuiz($this)
                    ->count($con);
            }
        } else {
            return count($this->collQuizQuestions);
        }
    }

    /**
     * Method called to associate a QuizQuestion object to this object
     * through the QuizQuestion foreign key attribute.
     *
     * @param    QuizQuestion $l QuizQuestion
     * @return Quiz The current object (for fluent API support)
     */
    public function addQuizQuestion(QuizQuestion $l)
    {
        if ($this->collQuizQuestions === null) {
            $this->initQuizQuestions();
            $this->collQuizQuestionsPartial = true;
        }
        if (!in_array($l, $this->collQuizQuestions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddQuizQuestion($l);
        }

        return $this;
    }

    /**
     * @param	QuizQuestion $quizQuestion The quizQuestion object to add.
     */
    protected function doAddQuizQuestion($quizQuestion)
    {
        $this->collQuizQuestions[]= $quizQuestion;
        $quizQuestion->setQuiz($this);
    }

    /**
     * @param	QuizQuestion $quizQuestion The quizQuestion object to remove.
     */
    public function removeQuizQuestion($quizQuestion)
    {
        if ($this->getQuizQuestions()->contains($quizQuestion)) {
            $this->collQuizQuestions->remove($this->collQuizQuestions->search($quizQuestion));
            if (null === $this->quizQuestionsScheduledForDeletion) {
                $this->quizQuestionsScheduledForDeletion = clone $this->collQuizQuestions;
                $this->quizQuestionsScheduledForDeletion->clear();
            }
            $this->quizQuestionsScheduledForDeletion[]= $quizQuestion;
            $quizQuestion->setQuiz(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related QuizQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|QuizQuestion[] List of QuizQuestion objects
     */
    public function getQuizQuestionsJoinQuestion($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QuizQuestionQuery::create(null, $criteria);
        $query->joinWith('Question', $join_behavior);

        return $this->getQuizQuestions($query, $con);
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
     * If this Quiz is new, it will return
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
                    ->filterByQuiz($this)
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
            $userQuestionRemoved->setQuiz(null);
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
                    ->filterByQuiz($this)
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
     * @return Quiz The current object (for fluent API support)
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
        $userQuestion->setQuiz($this);
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
            $userQuestion->setQuiz(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
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
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
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
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
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
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related UserQuestions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuestion[] List of UserQuestion objects
     */
    public function getUserQuestionsJoinUserQuiz($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuestionQuery::create(null, $criteria);
        $query->joinWith('UserQuiz', $join_behavior);

        return $this->getUserQuestions($query, $con);
    }

    /**
     * Clears out the collUserquizzes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUserquizzes()
     */
    public function clearUserquizzes()
    {
        $this->collUserquizzes = null; // important to set this to null since that means it is uninitialized
        $this->collUserquizzesPartial = null;
    }

    /**
     * reset is the collUserquizzes collection loaded partially
     *
     * @return void
     */
    public function resetPartialUserquizzes($v = true)
    {
        $this->collUserquizzesPartial = $v;
    }

    /**
     * Initializes the collUserquizzes collection.
     *
     * By default this just sets the collUserquizzes collection to an empty array (like clearcollUserquizzes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUserquizzes($overrideExisting = true)
    {
        if (null !== $this->collUserquizzes && !$overrideExisting) {
            return;
        }
        $this->collUserquizzes = new PropelObjectCollection();
        $this->collUserquizzes->setModel('UserQuiz');
    }

    /**
     * Gets an array of UserQuiz objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Quiz is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|UserQuiz[] List of UserQuiz objects
     * @throws PropelException
     */
    public function getUserquizzes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collUserquizzesPartial && !$this->isNew();
        if (null === $this->collUserquizzes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collUserquizzes) {
                // return empty collection
                $this->initUserquizzes();
            } else {
                $collUserquizzes = UserQuizQuery::create(null, $criteria)
                    ->filterByQuiz($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collUserquizzesPartial && count($collUserquizzes)) {
                      $this->initUserquizzes(false);

                      foreach($collUserquizzes as $obj) {
                        if (false == $this->collUserquizzes->contains($obj)) {
                          $this->collUserquizzes->append($obj);
                        }
                      }

                      $this->collUserquizzesPartial = true;
                    }

                    return $collUserquizzes;
                }

                if($partial && $this->collUserquizzes) {
                    foreach($this->collUserquizzes as $obj) {
                        if($obj->isNew()) {
                            $collUserquizzes[] = $obj;
                        }
                    }
                }

                $this->collUserquizzes = $collUserquizzes;
                $this->collUserquizzesPartial = false;
            }
        }

        return $this->collUserquizzes;
    }

    /**
     * Sets a collection of UserQuiz objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $userquizzes A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setUserquizzes(PropelCollection $userquizzes, PropelPDO $con = null)
    {
        $this->userquizzesScheduledForDeletion = $this->getUserquizzes(new Criteria(), $con)->diff($userquizzes);

        foreach ($this->userquizzesScheduledForDeletion as $userQuizRemoved) {
            $userQuizRemoved->setQuiz(null);
        }

        $this->collUserquizzes = null;
        foreach ($userquizzes as $userQuiz) {
            $this->addUserQuiz($userQuiz);
        }

        $this->collUserquizzes = $userquizzes;
        $this->collUserquizzesPartial = false;
    }

    /**
     * Returns the number of related UserQuiz objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related UserQuiz objects.
     * @throws PropelException
     */
    public function countUserquizzes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collUserquizzesPartial && !$this->isNew();
        if (null === $this->collUserquizzes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUserquizzes) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getUserquizzes());
                }
                $query = UserQuizQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByQuiz($this)
                    ->count($con);
            }
        } else {
            return count($this->collUserquizzes);
        }
    }

    /**
     * Method called to associate a UserQuiz object to this object
     * through the UserQuiz foreign key attribute.
     *
     * @param    UserQuiz $l UserQuiz
     * @return Quiz The current object (for fluent API support)
     */
    public function addUserQuiz(UserQuiz $l)
    {
        if ($this->collUserquizzes === null) {
            $this->initUserquizzes();
            $this->collUserquizzesPartial = true;
        }
        if (!in_array($l, $this->collUserquizzes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddUserQuiz($l);
        }

        return $this;
    }

    /**
     * @param	UserQuiz $userQuiz The userQuiz object to add.
     */
    protected function doAddUserQuiz($userQuiz)
    {
        $this->collUserquizzes[]= $userQuiz;
        $userQuiz->setQuiz($this);
    }

    /**
     * @param	UserQuiz $userQuiz The userQuiz object to remove.
     */
    public function removeUserQuiz($userQuiz)
    {
        if ($this->getUserquizzes()->contains($userQuiz)) {
            $this->collUserquizzes->remove($this->collUserquizzes->search($userQuiz));
            if (null === $this->userquizzesScheduledForDeletion) {
                $this->userquizzesScheduledForDeletion = clone $this->collUserquizzes;
                $this->userquizzesScheduledForDeletion->clear();
            }
            $this->userquizzesScheduledForDeletion[]= $userQuiz;
            $userQuiz->setQuiz(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Quiz is new, it will return
     * an empty collection; or if this Quiz has previously
     * been saved, it will retrieve related Userquizzes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Quiz.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|UserQuiz[] List of UserQuiz objects
     */
    public function getUserquizzesJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = UserQuizQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getUserquizzes($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->description = null;
        $this->time = null;
        $this->num_questions = null;
        $this->is_active = null;
        $this->is_opened = null;
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
            if ($this->collLessonquizzes) {
                foreach ($this->collLessonquizzes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collQuizQuestions) {
                foreach ($this->collQuizQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserQuestions) {
                foreach ($this->collUserQuestions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUserquizzes) {
                foreach ($this->collUserquizzes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collLessonquizzes instanceof PropelCollection) {
            $this->collLessonquizzes->clearIterator();
        }
        $this->collLessonquizzes = null;
        if ($this->collQuizQuestions instanceof PropelCollection) {
            $this->collQuizQuestions->clearIterator();
        }
        $this->collQuizQuestions = null;
        if ($this->collUserQuestions instanceof PropelCollection) {
            $this->collUserQuestions->clearIterator();
        }
        $this->collUserQuestions = null;
        if ($this->collUserquizzes instanceof PropelCollection) {
            $this->collUserquizzes->clearIterator();
        }
        $this->collUserquizzes = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(QuizPeer::DEFAULT_STRING_FORMAT);
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
     * @return     Quiz The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = QuizPeer::UPDATED_AT;

        return $this;
    }

}
