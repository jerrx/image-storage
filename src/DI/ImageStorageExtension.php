<?php declare(strict_types = 1);

namespace Contributte\ImageStorage\DI;

use Contributte\ImageStorage\ImageStorage;
use Contributte\ImageStorage\Latte\LatteExtension;
use Contributte\ImageStorage\Latte\Macros;
use Latte\Engine;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class ImageStorageExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'data_path' => Expect::string()->required(),
			'data_dir' => Expect::string()->required(),
			'orig_path' => Expect::string()->default(null),
			'algorithm_file' => Expect::string('sha1_file'),
			'algorithm_content' => Expect::string('sha1'),
			'quality' => Expect::int(85),
			'default_transform' => Expect::string('fit'),
			'noimage_identifier' => Expect::string('noimage/03/no-image.png'),
			'friendly_url' => Expect::bool(false),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$this->config->orig_path =  $this->config->orig_path ?? $this->config->data_path;
		$config = (array) $this->config;
		$builder->addDefinition($this->prefix('storage'))
			->setType(ImageStorage::class)
			->setFactory(ImageStorage::class)
			->setArguments($config);
	}


	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$latteFactoryName = $builder->getByType(Nette\Bridges\ApplicationLatte\ILatteFactory::class);

		if ($latteFactoryName !== null) {
			/** @var \Nette\DI\Definitions\FactoryDefinition $latteFactory */
			$latteFactory = $builder->getDefinition($latteFactoryName);

			/** @phpstan-ignore-next-line */
			if (version_compare(Engine::VERSION, '3', '<')) {
				$latteFactory->getResultDefinition()
					->addSetup('Contributte\ImageStorage\Macros\Macros::install(?->getCompiler())', ['@self']);
			} else {
				$latteExtension = $builder->addDefinition($this->prefix('latte.extension'))
					->setFactory(LatteExtension::class);
				$latteFactory->getResultDefinition()
					->addSetup('addExtension', [$latteExtension]);
			}
		}
	}

}
