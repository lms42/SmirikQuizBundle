<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'quiz_questions' table.
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
class QuizQuestionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.QuizQuestionTableMap';

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
        $this->setName('quiz_questions');
        $this->setPhpName('QuizQuestion');
        $this->setClassname('Smirik\\QuizBundle\\Model\\QuizQuestion');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('QUESTION_ID', 'QuestionId', 'INTEGER' , 'questions', 'ID', true, null, null);
        $this->addForeignPrimaryKey('QUIZ_ID', 'QuizId', 'INTEGER' , 'quiz', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Question', 'Smirik\\QuizBundle\\Model\\Question', RelationMap::MANY_TO_ONE, array('question_id' => 'id', ), null, null);
        $this->addRelation('Quiz', 'Smirik\\QuizBundle\\Model\\Quiz', RelationMap::MANY_TO_ONE, array('quiz_id' => 'id', ), null, null);
    } // buildRelations()

} // QuizQuestionTableMap
