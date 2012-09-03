<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'quiz' table.
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
class QuizTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.QuizTableMap';

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
        $this->setName('quiz');
        $this->setPhpName('Quiz');
        $this->setClassname('Smirik\\QuizBundle\\Model\\Quiz');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 100, null);
        $this->addColumn('DESCRIPTION', 'Description', 'CLOB', true, null, null);
        $this->addColumn('TIME', 'Time', 'INTEGER', true, null, null);
        $this->addColumn('NUM_QUESTIONS', 'NumQuestions', 'INTEGER', true, null, null);
        $this->addColumn('IS_ACTIVE', 'IsActive', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_OPENED', 'IsOpened', 'BOOLEAN', false, 1, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('LessonQuiz', 'Smirik\\CourseBundle\\Model\\LessonQuiz', RelationMap::ONE_TO_MANY, array('id' => 'quiz_id', ), 'CASCADE', null, 'Lessonquizzes');
        $this->addRelation('QuizQuestion', 'Smirik\\QuizBundle\\Model\\QuizQuestion', RelationMap::ONE_TO_MANY, array('id' => 'quiz_id', ), null, null, 'QuizQuestions');
        $this->addRelation('UserQuestion', 'Smirik\\QuizBundle\\Model\\UserQuestion', RelationMap::ONE_TO_MANY, array('id' => 'quiz_id', ), 'CASCADE', null, 'UserQuestions');
        $this->addRelation('UserQuiz', 'Smirik\\QuizBundle\\Model\\UserQuiz', RelationMap::ONE_TO_MANY, array('id' => 'quiz_id', ), 'CASCADE', null, 'Userquizzes');
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

} // QuizTableMap
