<?php

class tfAuthBuildGuardsTask extends sfDoctrineBaseTask {
	protected function configure() {
		$this->namespace = 'auth';
		$this->name = 'build-guards';
	}

	protected function execute($arguments = array(), $options = array()) {
		$config = $this->getCliConfig();
		$builderOptions = $this->configuration->getPluginConfiguration('sfDoctrinePlugin')->getModelBuilderOptions();
		$schema = $this->prepareSchemaFile($config['yaml_schema_path']);
		$import = new Doctrine_Import_Schema();
		$import->setOptions($builderOptions);
		$import->importSchema($schema, 'yml', $config['models_path']);

		foreach (sfYaml::load($schema) as $model => $definition) {
			$filename = sfConfig::get('sf_lib_dir').'/guard/'.$model.'Guard.class.php';
			if (file_exists($filename))
				continue;
			
			file_put_contents($filename, '<?php

class '.$model.'Guard extends BaseGuard {
	
}
');
		}

		$this->reloadAutoload();
	}
}
