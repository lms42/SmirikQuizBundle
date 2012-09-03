<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users_questions' table.
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
class UserQuestionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.UserQuestionTableMap';

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
        $this->setName('users_questions');
        $this->setPhpName('UserQuestion');
        $this->setClassname('Smirik\\QuizBundle\\Model\\UserQuestion');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'fos_user', 'ID', true, null, null);
        $this->addForeignKey('QUIZ_ID', 'QuizId', 'INTEGER', 'quiz', 'ID', true, null, null);
        $this->addForeignKey('QUESTION_ID', 'QuestionId', 'INTEGER', 'questions', 'ID', true, null, null);
        $this->addForeignKey('USER_QUIZ_ID', 'UserQuizId', 'INTEGER', 'users_quiz', 'ID', true, null, null);
        $this->addForeignKey('ANSWER_ID', 'AnswerId', 'INTEGER', 'answers', 'ID', false, null, null);
        $this->addColumn('ANSWER_TEXT', 'AnswerText', 'VARCHAR', false, 200, null);
        $this->addColumn('IS_RIGHT', 'IsRight', 'BOOLEAN', false, 1, null);
        $this->addColumn('IS_CLOSED', 'IsClosed', 'BOOLEAN', false, 1, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Question', 'Smirik\\QuizBundle\\Model\\Question', RelationMap::MANY_TO_ONE, array('question_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Quiz', 'Smirik\\QuizBundle\\Model\\Quiz', RelationMap::MANY_TO_ONE, array('quiz_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Answer', 'Smirik\\QuizBundle\\Model\\Answer', RelationMap::MANY_TO_ONE, array('answer_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('UserQuiz', 'Smirik\\QuizBundle\\Model\\UserQuiz', RelationMap::MANY_TO_ONE, array('user_quiz_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // UserQuestionTableMap
