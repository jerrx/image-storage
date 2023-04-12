<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Contributte\ImageStorage\Latte;

use Latte;

final class LatteExtension extends Latte\Extension
{
	public function getTags(): array
	{
		return [
			'img' => [Nodes\ImgNode::class, 'create'],
			'imgLink' => [Nodes\ImgLinkNode::class, 'create'],
			//'imgAbs' => [Nodes\FormNode::class, 'create'],
			//'imgLinkAbs' => [Nodes\LabelNode::class, 'create'],
		];
	}
}
