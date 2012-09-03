<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'answers' table.
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
class AnswerTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.AnswerTableMap';

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
        $this->setName('answers');
        $this->setPhpName('Answer');
        $this->setClassname('Smirik\\QuizBundle\\Model\\Answer');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('QUESTION_ID', 'QuestionId', 'INTEGER', 'questions', 'ID', true, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('FILE', 'File', 'VARCHAR', false, 255, null);
        $this->addColumn('IS_RIGHT', 'IsRight', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Question', 'Smirik\\QuizBundle\\Model\\Question', RelationMap::MANY_TO_ONE, array('question_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('UserQuestion', 'Smirik\\QuizBundle\\Model\\UserQuestion', RelationMap::ONE_TO_MANY, array('id' => 'answer_id', ), 'CASCADE', null, 'UserQuestions');
    } // buildRelations()

} // AnswerTableMap
