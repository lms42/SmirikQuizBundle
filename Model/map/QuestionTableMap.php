<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'questions' table.
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
class QuestionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.QuestionTableMap';

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
        $this->setName('questions');
        $this->setPhpName('Question');
        $this->setClassname('Smirik\\QuizBundle\\Model\\Question');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('TEXT', 'Text', 'CLOB', false, null, null);
        $this->addColumn('TYPE', 'Type', 'VARCHAR', false, 50, null);
        $this->addColumn('FILE', 'File', 'VARCHAR', false, 255, null);
        $this->addColumn('NUM_ANSWERS', 'NumAnswers', 'INTEGER', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('QuizQuestion', 'Smirik\\QuizBundle\\Model\\QuizQuestion', RelationMap::ONE_TO_MANY, array('id' => 'question_id', ), null, null, 'QuizQuestions');
        $this->addRelation('Answer', 'Smirik\\QuizBundle\\Model\\Answer', RelationMap::ONE_TO_MANY, array('id' => 'question_id', ), 'CASCADE', null, 'Answers');
        $this->addRelation('UserQuestion', 'Smirik\\QuizBundle\\Model\\UserQuestion', RelationMap::ONE_TO_MANY, array('id' => 'question_id', ), 'CASCADE', null, 'UserQuestions');
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

} // QuestionTableMap
