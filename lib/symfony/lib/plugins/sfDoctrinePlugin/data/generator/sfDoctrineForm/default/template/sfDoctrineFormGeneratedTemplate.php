[?php

/**
 * <?php echo $this->modelName ?> form base class.
 *
 * @method <?php echo $this->modelName ?> getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class Base<?php echo $this->modelName ?>Form extends <?php echo $this->getFormClassToExtend().PHP_EOL ?>
{
  public function setup()
  {
    $this->setWidgets(array(
<?php foreach ($this->getColumns() as $column) : ?>
<?php switch ($column->getForeignClassName()) : ?>
<?php case 'User': ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new tfWidgetFormExtUserComboBox(),
<?php break; ?>
<?php case 'msBrBranch': ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new tfWidgetFormExtBranchComboBox(),
<?php break; ?>
<?php default: ?>
<?php   switch ($column->getDoctrineType()) : ?>
<?php     case 'date': ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new sfWidgetFormI18nDateRu(),
<?php     break; ?>
<?php     case 'timestamp': ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new sfWidgetFormI18nDateTimeRu(),
<?php     break; ?>
<?php     default: ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>),
<?php   endswitch ?>
<?php endswitch ?>
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
<?php if ($relation['table']->getOption('name') == 'User') : ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new msWidgetTreeChoice(array('tree' => GroupTable::getInstance()->getUserGroupTreeForTfWidgetTreeChoice())),
<?php else : ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>')),
<?php endif ?>
<?php endforeach; ?>
    ));

    $this->setValidators(array(
<?php foreach ($this->getColumns() as $column): ?>
<?php if (in_array($column->getFieldName(), array('created_at', 'updated_at'))) : ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new sfValidatorDateTime(array('required' => false)),
<?php else : ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getValidatorClassForColumn($column) ?>(<?php echo $this->getValidatorOptionsForColumn($column) ?>),
<?php endif ?>
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
<?php if ($relation['table']->getOption('name') == 'User') : ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new tfValidatorDoctrineUniqueChoice(array('multiple' => true, 'model' => 'User', 'required' => false), array('required' => 'user.users_needed')),
<?php else : ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false)),
<?php endif ?>
<?php endforeach; ?>
    ));

<?php if ($uniqueColumns = $this->getUniqueColumnNames()): ?>
    $this->validatorSchema->setPostValidator(
<?php if (count($uniqueColumns) > 1): ?>
      new sfValidatorAnd(array(
<?php foreach ($uniqueColumns as $uniqueColumn): ?>
        new sfValidatorDoctrineUnique(array('model' => '<?php echo $this->table->getOption('name') ?>', 'column' => array('<?php echo implode("', '", $uniqueColumn) ?>'))),
<?php endforeach; ?>
      ))
<?php else: ?>
      new sfValidatorDoctrineUnique(array('model' => '<?php echo $this->table->getOption('name') ?>', 'column' => array('<?php echo implode("', '", $uniqueColumns[0]) ?>')))
<?php endif; ?>
    );

<?php endif; ?>
    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return '<?php echo $this->modelName ?>';
  }

<?php if ($this->getManyToManyRelations()): ?>
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    if (isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      $this->setDefault('<?php echo $this->underscore($relation['alias']) ?>_list', $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys());
    }

<?php endforeach; ?>
  }

  protected function doSave($con = null)
  {
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    $this->save<?php echo $relation['alias'] ?>List($con);
<?php endforeach; ?>

    parent::doSave($con);
  }

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
  public function save<?php echo $relation['alias'] ?>List($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys();
    $values = $this->getValue('<?php echo $this->underscore($relation['alias']) ?>_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('<?php echo $relation['alias'] ?>', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('<?php echo $relation['alias'] ?>', array_values($link));
    }
  }

<?php endforeach; ?>
<?php endif; ?>
}
