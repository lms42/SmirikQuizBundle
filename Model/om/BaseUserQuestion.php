<?php

namespace Smirik\QuizBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Smirik\QuizBundle\Model\Answer;
use Smirik\QuizBundle\Model\AnswerQuery;
use Smirik\QuizBundle\Model\Question;
use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\Quiz;
use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\UserQuestion;
use Smirik\QuizBundle\Model\UserQuestionPeer;
use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuiz;
use Smirik\QuizBundle\Model\UserQuizQuery;

abstract class BaseUserQuestion extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Smirik\\QuizBundle\\Model\\UserQuestionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserQuestionPeer
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
     * The value for the question_id field.
     * @var        int
     */
    protected $question_id;

    /**
     * The value for the user_quiz_id field.
     * @var        int
     */
    protected $user_quiz_id;

    /**
     * The value for the answer_id field.
     * @var        int
     */
    protected $answer_id;

    /**
     * The value for the answer_text field.
     * @var        string
     */
    protected $answer_text;

    /**
     * The value for the is_right field.
     * @var        boolean
     */
    protected $is_right;

    /**
     * The value for the is_closed field.
     * @var        boolean
     */
    protected $is_closed;

    /**
     * @var        Question
     */
    protected $aQuestion;

    /**
     * @var        Quiz
     */
    protected $aQuiz;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        Answer
     */
    protected $aAnswer;

    /**
     * @var        UserQuiz
     */
    protected $aUserQuiz;

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
     * Get the [question_id] column value.
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * Get the [user_quiz_id] column value.
     *
     * @return int
     */
    public function getUserQuizId()
    {
        return $this->user_quiz_id;
    }

    /**
     * Get the [answer_id] column value.
     *
     * @return int
     */
    public function getAnswerId()
    {
        return $this->answer_id;
    }

    /**
     * Get the [answer_text] column value.
     *
     * @return string
     */
    public function getAnswerText()
    {
        return $this->answer_text;
    }

    /**
     * Get the [is_right] column value.
     *
     * @return boolean
     */
    public function getIsRight()
    {
        return $this->is_right;
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::USER_ID;
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
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setQuizId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->quiz_id !== $v) {
            $this->quiz_id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::QUIZ_ID;
        }

        if ($this->aQuiz !== null && $this->aQuiz->getId() !== $v) {
            $this->aQuiz = null;
        }


        return $this;
    } // setQuizId()

    /**
     * Set the value of [question_id] column.
     *
     * @param int $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setQuestionId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->question_id !== $v) {
            $this->question_id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::QUESTION_ID;
        }

        if ($this->aQuestion !== null && $this->aQuestion->getId() !== $v) {
            $this->aQuestion = null;
        }


        return $this;
    } // setQuestionId()

    /**
     * Set the value of [user_quiz_id] column.
     *
     * @param int $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setUserQuizId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_quiz_id !== $v) {
            $this->user_quiz_id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::USER_QUIZ_ID;
        }

        if ($this->aUserQuiz !== null && $this->aUserQuiz->getId() !== $v) {
            $this->aUserQuiz = null;
        }


        return $this;
    } // setUserQuizId()

    /**
     * Set the value of [answer_id] column.
     *
     * @param int $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setAnswerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->answer_id !== $v) {
            $this->answer_id = $v;
            $this->modifiedColumns[] = UserQuestionPeer::ANSWER_ID;
        }

        if ($this->aAnswer !== null && $this->aAnswer->getId() !== $v) {
            $this->aAnswer = null;
        }


        return $this;
    } // setAnswerId()

    /**
     * Set the value of [answer_text] column.
     *
     * @param string $v new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setAnswerText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->answer_text !== $v) {
            $this->answer_text = $v;
            $this->modifiedColumns[] = UserQuestionPeer::ANSWER_TEXT;
        }


        return $this;
    } // setAnswerText()

    /**
     * Sets the value of the [is_right] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserQuestion The current object (for fluent API support)
     */
    public function setIsRight($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_right !== $v) {
            $this->is_right = $v;
            $this->modifiedColumns[] = UserQuestionPeer::IS_RIGHT;
        }


        return $this;
    } // setIsRight()

    /**
     * Sets the value of the [is_closed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return UserQuestion The current object (for fluent API support)
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
            $this->modifiedColumns[] = UserQuestionPeer::IS_CLOSED;
        }


        return $this;
    } // setIsClosed()

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
            $this->question_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->user_quiz_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->answer_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->answer_text = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->is_right = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->is_closed = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = UserQuestionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating UserQuestion object", $e);
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
        if ($this->aQuestion !== null && $this->question_id !== $this->aQuestion->getId()) {
            $this->aQuestion = null;
        }
        if ($this->aUserQuiz !== null && $this->user_quiz_id !== $this->aUserQuiz->getId()) {
            $this->aUserQuiz = null;
        }
        if ($this->aAnswer !== null && $this->answer_id !== $this->aAnswer->getId()) {
            $this->aAnswer = null;
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
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserQuestionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aQuestion = null;
            $this->aQuiz = null;
            $this->aUser = null;
            $this->aAnswer = null;
            $this->aUserQuiz = null;
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
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuestionQuery::create()
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
            $con = Propel::getConnection(UserQuestionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserQuestionPeer::addInstanceToPool($this);
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

            if ($this->aQuestion !== null) {
                if ($this->aQuestion->isModified() || $this->aQuestion->isNew()) {
                    $affectedRows += $this->aQuestion->save($con);
                }
                $this->setQuestion($this->aQuestion);
            }

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

            if ($this->aAnswer !== null) {
                if ($this->aAnswer->isModified() || $this->aAnswer->isNew()) {
                    $affectedRows += $this->aAnswer->save($con);
                }
                $this->setAnswer($this->aAnswer);
            }

            if ($this->aUserQuiz !== null) {
                if ($this->aUserQuiz->isModified() || $this->aUserQuiz->isNew()) {
                    $affectedRows += $this->aUserQuiz->save($con);
                }
                $this->setUserQuiz($this->aUserQuiz);
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

        $this->modifiedColumns[] = UserQuestionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserQuestionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserQuestionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::QUIZ_ID)) {
            $modifiedColumns[':p' . $index++]  = '`QUIZ_ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::QUESTION_ID)) {
            $modifiedColumns[':p' . $index++]  = '`QUESTION_ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::USER_QUIZ_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_QUIZ_ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::ANSWER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`ANSWER_ID`';
        }
        if ($this->isColumnModified(UserQuestionPeer::ANSWER_TEXT)) {
            $modifiedColumns[':p' . $index++]  = '`ANSWER_TEXT`';
        }
        if ($this->isColumnModified(UserQuestionPeer::IS_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`IS_RIGHT`';
        }
        if ($this->isColumnModified(UserQuestionPeer::IS_CLOSED)) {
            $modifiedColumns[':p' . $index++]  = '`IS_CLOSED`';
        }

        $sql = sprintf(
            'INSERT INTO `users_questions` (%s) VALUES (%s)',
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
                    case '`QUESTION_ID`':
                        $stmt->bindValue($identifier, $this->question_id, PDO::PARAM_INT);
                        break;
                    case '`USER_QUIZ_ID`':
                        $stmt->bindValue($identifier, $this->user_quiz_id, PDO::PARAM_INT);
                        break;
                    case '`ANSWER_ID`':
                        $stmt->bindValue($identifier, $this->answer_id, PDO::PARAM_INT);
                        break;
                    case '`ANSWER_TEXT`':
                        $stmt->bindValue($identifier, $this->answer_text, PDO::PARAM_STR);
                        break;
                    case '`IS_RIGHT`':
                        $stmt->bindValue($identifier, (int) $this->is_right, PDO::PARAM_INT);
                        break;
                    case '`IS_CLOSED`':
                        $stmt->bindValue($identifier, (int) $this->is_closed, PDO::PARAM_INT);
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

            if ($this->aQuestion !== null) {
                if (!$this->aQuestion->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aQuestion->getValidationFailures());
                }
            }

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

            if ($this->aAnswer !== null) {
                if (!$this->aAnswer->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aAnswer->getValidationFailures());
                }
            }

            if ($this->aUserQuiz !== null) {
                if (!$this->aUserQuiz->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserQuiz->getValidationFailures());
                }
            }


            if (($retval = UserQuestionPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = UserQuestionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getQuestionId();
                break;
            case 4:
                return $this->getUserQuizId();
                break;
            case 5:
                return $this->getAnswerId();
                break;
            case 6:
                return $this->getAnswerText();
                break;
            case 7:
                return $this->getIsRight();
                break;
            case 8:
                return $this->getIsClosed();
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
        if (isset($alreadyDumpedObjects['UserQuestion'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['UserQuestion'][$this->getPrimaryKey()] = true;
        $keys = UserQuestionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getQuizId(),
            $keys[3] => $this->getQuestionId(),
            $keys[4] => $this->getUserQuizId(),
            $keys[5] => $this->getAnswerId(),
            $keys[6] => $this->getAnswerText(),
            $keys[7] => $this->getIsRight(),
            $keys[8] => $this->getIsClosed(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aQuestion) {
                $result['Question'] = $this->aQuestion->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aQuiz) {
                $result['Quiz'] = $this->aQuiz->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAnswer) {
                $result['Answer'] = $this->aAnswer->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserQuiz) {
                $result['UserQuiz'] = $this->aUserQuiz->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = UserQuestionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setQuestionId($value);
                break;
            case 4:
                $this->setUserQuizId($value);
                break;
            case 5:
                $this->setAnswerId($value);
                break;
            case 6:
                $this->setAnswerText($value);
                break;
            case 7:
                $this->setIsRight($value);
                break;
            case 8:
                $this->setIsClosed($value);
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
        $keys = UserQuestionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setQuizId($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setQuestionId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUserQuizId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAnswerId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAnswerText($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsRight($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setIsClosed($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserQuestionPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserQuestionPeer::ID)) $criteria->add(UserQuestionPeer::ID, $this->id);
        if ($this->isColumnModified(UserQuestionPeer::USER_ID)) $criteria->add(UserQuestionPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(UserQuestionPeer::QUIZ_ID)) $criteria->add(UserQuestionPeer::QUIZ_ID, $this->quiz_id);
        if ($this->isColumnModified(UserQuestionPeer::QUESTION_ID)) $criteria->add(UserQuestionPeer::QUESTION_ID, $this->question_id);
        if ($this->isColumnModified(UserQuestionPeer::USER_QUIZ_ID)) $criteria->add(UserQuestionPeer::USER_QUIZ_ID, $this->user_quiz_id);
        if ($this->isColumnModified(UserQuestionPeer::ANSWER_ID)) $criteria->add(UserQuestionPeer::ANSWER_ID, $this->answer_id);
        if ($this->isColumnModified(UserQuestionPeer::ANSWER_TEXT)) $criteria->add(UserQuestionPeer::ANSWER_TEXT, $this->answer_text);
        if ($this->isColumnModified(UserQuestionPeer::IS_RIGHT)) $criteria->add(UserQuestionPeer::IS_RIGHT, $this->is_right);
        if ($this->isColumnModified(UserQuestionPeer::IS_CLOSED)) $criteria->add(UserQuestionPeer::IS_CLOSED, $this->is_closed);

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
        $criteria = new Criteria(UserQuestionPeer::DATABASE_NAME);
        $criteria->add(UserQuestionPeer::ID, $this->id);

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
     * @param object $copyObj An object of UserQuestion (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setQuizId($this->getQuizId());
        $copyObj->setQuestionId($this->getQuestionId());
        $copyObj->setUserQuizId($this->getUserQuizId());
        $copyObj->setAnswerId($this->getAnswerId());
        $copyObj->setAnswerText($this->getAnswerText());
        $copyObj->setIsRight($this->getIsRight());
        $copyObj->setIsClosed($this->getIsClosed());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return UserQuestion Clone of current object.
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
     * @return UserQuestionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserQuestionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Question object.
     *
     * @param             Question $v
     * @return UserQuestion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setQuestion(Question $v = null)
    {
        if ($v === null) {
            $this->setQuestionId(NULL);
        } else {
            $this->setQuestionId($v->getId());
        }

        $this->aQuestion = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Question object, it will not be re-added.
        if ($v !== null) {
            $v->addUserQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated Question object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Question The associated Question object.
     * @throws PropelException
     */
    public function getQuestion(PropelPDO $con = null)
    {
        if ($this->aQuestion === null && ($this->question_id !== null)) {
            $this->aQuestion = QuestionQuery::create()->findPk($this->question_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aQuestion->addUserQuestions($this);
             */
        }

        return $this->aQuestion;
    }

    /**
     * Declares an association between this object and a Quiz object.
     *
     * @param             Quiz $v
     * @return UserQuestion The current object (for fluent API support)
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
            $v->addUserQuestion($this);
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
                $this->aQuiz->addUserQuestions($this);
             */
        }

        return $this->aQuiz;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return UserQuestion The current object (for fluent API support)
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
            $v->addUserQuestion($this);
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
                $this->aUser->addUserQuestions($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a Answer object.
     *
     * @param             Answer $v
     * @return UserQuestion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAnswer(Answer $v = null)
    {
        if ($v === null) {
            $this->setAnswerId(NULL);
        } else {
            $this->setAnswerId($v->getId());
        }

        $this->aAnswer = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Answer object, it will not be re-added.
        if ($v !== null) {
            $v->addUserQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated Answer object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Answer The associated Answer object.
     * @throws PropelException
     */
    public function getAnswer(PropelPDO $con = null)
    {
        if ($this->aAnswer === null && ($this->answer_id !== null)) {
            $this->aAnswer = AnswerQuery::create()->findPk($this->answer_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAnswer->addUserQuestions($this);
             */
        }

        return $this->aAnswer;
    }

    /**
     * Declares an association between this object and a UserQuiz object.
     *
     * @param             UserQuiz $v
     * @return UserQuestion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserQuiz(UserQuiz $v = null)
    {
        if ($v === null) {
            $this->setUserQuizId(NULL);
        } else {
            $this->setUserQuizId($v->getId());
        }

        $this->aUserQuiz = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the UserQuiz object, it will not be re-added.
        if ($v !== null) {
            $v->addUserQuestion($this);
        }


        return $this;
    }


    /**
     * Get the associated UserQuiz object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return UserQuiz The associated UserQuiz object.
     * @throws PropelException
     */
    public function getUserQuiz(PropelPDO $con = null)
    {
        if ($this->aUserQuiz === null && ($this->user_quiz_id !== null)) {
            $this->aUserQuiz = UserQuizQuery::create()->findPk($this->user_quiz_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserQuiz->addUserQuestions($this);
             */
        }

        return $this->aUserQuiz;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->user_id = null;
        $this->quiz_id = null;
        $this->question_id = null;
        $this->user_quiz_id = null;
        $this->answer_id = null;
        $this->answer_text = null;
        $this->is_right = null;
        $this->is_closed = null;
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
        } // if ($deep)

        $this->aQuestion = null;
        $this->aQuiz = null;
        $this->aUser = null;
        $this->aAnswer = null;
        $this->aUserQuiz = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserQuestionPeer::DEFAULT_STRING_FORMAT);
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

}
