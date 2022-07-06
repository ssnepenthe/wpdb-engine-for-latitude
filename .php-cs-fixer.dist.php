<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
	->setRiskyAllowed(true)
	->setRules([
		'@PSR12' => true,
		'no_unused_imports' => true,
		'ordered_imports' => [
			'imports_order' => ['const', 'class', 'function'],
		],
		'declare_strict_types' => true,
	])
	->setFinder(
		Finder::create()->in(__DIR__)
	);
