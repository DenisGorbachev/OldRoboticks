[?php

/**
 * <?php echo $this->table->getOption('name') ?> filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class Base<?php echo $this->table->getOption('name') ?>FormFilter extends <?php echo $this->getFormClassToExtend().PHP_EOL ?>
{
  public function setup()
  {
    $this->setWidgets(array(
<?php foreach ($this->getColumns() as $column): ?>
<?php if ($column->isPrimaryKey()) continue ?>
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
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormI18nDateRu(), 'to_date' => new sfWidgetFormI18nDateRu(), 'template' => 'от %from_date%<br />до %to_date%')),
<?php     break; ?>
<?php     case 'timestamp': ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormI18nDateTimeRu(), 'to_date' => new sfWidgetFormI18nDateTimeRu(), 'template' => 'от %from_date%<br />до %to_date%')),
<?php     break; ?>
<?php     default: ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>),
<?php   endswitch ?>
<?php endswitch ?>
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>')),
<?php endforeach; ?>
    ));

    $this->setValidators(array(
<?php foreach ($this->getColumns() as $column): ?>
<?php if ($column->isPrimaryKey()) continue ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => <?php echo $this->getValidatorForColumn($column) ?>,
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false)),
<?php endforeach; ?>
    ));

    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
  public function add<?php echo sfInflector::camelize($relation['alias']) ?>ListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.<?php echo $relation['refTable']->getOption('name') ?> <?php echo $relation['refTable']->getOption('name') ?>')
      ->andWhereIn('<?php echo $relation['refTable']->getOption('name') ?>.<?php echo $relation->getForeignFieldName() ?>', $values)
    ;
  }

<?php endforeach; ?>
  public function getModelName()
  {
    return '<?php echo $this->modelName ?>';
  }

  public function getFields()
  {
    return array(
<?php foreach ($this->getColumns() as $column): ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => '<?php echo $this->getType($column) ?>',
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => 'ManyKey',
<?php endforeach; ?>
    );
  }
}
