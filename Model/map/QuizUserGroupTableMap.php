<?php

namespace Smirik\QuizBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'users_groups' table.
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
class QuizUserGroupTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Smirik.QuizBundle.Model.map.QuizUserGroupTableMap';

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
        $this->setName('users_groups');
        $this->setPhpName('QuizUserGroup');
        $this->setClassname('Smirik\\QuizBundle\\Model\\QuizUserGroup');
        $this->setPackage('src.Smirik.QuizBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('GROUP_ID', 'GroupId', 'INTEGER' , 'fos_group', 'ID', true, null, null);
        $this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'fos_user', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('Group', 'FOS\\UserBundle\\Propel\\Group', RelationMap::MANY_TO_ONE, array('group_id' => 'id', ), null, null);
    } // buildRelations()

} // QuizUserGroupTableMap
