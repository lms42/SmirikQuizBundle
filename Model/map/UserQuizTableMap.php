<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users_quiz' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Smirik.QuizBundle.Model.map
 */
class UserQuizTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.UserQuizTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('users_quiz');
        $this->setPhpName('UserQuiz');
        $this->setClassname('Smirik\\QuizBundle\\Model\\UserQuiz');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'fos_user', 'ID', true, null, null);
        $this->addForeignKey('QUIZ_ID', 'QuizId', 'INTEGER', 'quiz', 'ID', false, null, null);
        $this->addColumn('QUESTIONS', 'Questions', 'VARCHAR', true, 255, null);
        $this->addColumn('CURRENT', 'Current', 'INTEGER', true, null, null);
        $this->addColumn('NUM_RIGHT_ANSWERS', 'NumRightAnswers', 'INTEGER', false, null, null);
        $this->addColumn('STARTED_AT', 'StartedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('STOPPED_AT', 'StoppedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('IS_ACTIVE', 'IsActive', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_CLOSED', 'IsClosed', 'BOOLEAN', false, 1, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Quiz', 'Smirik\\QuizBundle\\Model\\Quiz', RelationMap::MANY_TO_ONE, array('quiz_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('UserQuestion', 'Smirik\\QuizBundle\\Model\\UserQuestion', RelationMap::ONE_TO_MANY, array('id' => 'user_quiz_id', ), 'CASCADE', null, 'UserQuestions');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

} // UserQuizTableMap
