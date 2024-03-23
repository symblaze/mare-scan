<?php

declare(strict_types=1);

namespace Symblaze\MareScan\Inspector\TypeCompatibility;

use PhpParser\Node\DeclareItem;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Declare_;
use Symblaze\MareScan\Exception\InspectionError;
use Symblaze\MareScan\Inspector\InspectorInterface;

final readonly class MissingDeclareStrictTypesInspector implements InspectorInterface
{
    private const string MESSAGE = 'Strict types declaration is missing';

    public function __construct(private ?Stmt $stmt = null)
    {
    }

    public function name(): string
    {
        return 'Missing declare strict types';
    }

    public function description(): string
    {
        return <<<README
Detects the missing declare(strict_types=1) directive in the file.

See Strict typing [(php.net)](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict) to learn more about why you may need use this directive.
README;
    }

    public function inspect(): void
    {
        if (! $this->stmt instanceof Declare_) {
            throw InspectionError::warning(self::MESSAGE);
        }

        $declares = $this->stmt->declares;
        if (! isset($declares[0]) || ! $declares[0] instanceof DeclareItem) {
            throw InspectionError::warning(self::MESSAGE);
        }

        $declaration = $declares[0];
        if ('strict_types' !== $declaration->key->name) {
            throw InspectionError::warning(self::MESSAGE);
        }

        if (! $declaration->value instanceof Int_) {
            throw InspectionError::warning(self::MESSAGE);
        }

        if (1 !== $declaration->value->value) {
            throw InspectionError::warning(self::MESSAGE);
        }
    }
}
