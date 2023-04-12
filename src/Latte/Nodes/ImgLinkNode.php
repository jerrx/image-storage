<?php

declare(strict_types=1);

namespace Contributte\ImageStorage\Latte\Nodes;


use Latte\Compiler\Nodes\Php\Expression\ArrayNode;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;

class ImgLinkNode extends StatementNode
{
    public ExpressionNode $subject;
    public ArrayNode $args;

    public static function create(Tag $tag): self
    {
        $node = new self;

        // parsování obsahu značky
        $node->subject = $tag->parser->parseUnquotedStringOrExpression();
        $tag->parser->stream->tryConsume(',');
        $node->args = $tag->parser->parseArguments();

        return $node;
    }

    public function print(PrintContext $context): string
    {
        return $context->format(
            '$_img = $imageStorage->fromIdentifier(%node?, %node?); echo $basePath . "/" . $_img->createLink();',
            $this->subject,
            $this->args,
        );
    }

    public function &getIterator(): \Generator
    {
        yield $this->subject;
        yield $this->args;
    }
}
