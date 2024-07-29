<?php

declare(strict_types = 1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\DowngradeLevelSetList;

/**
 * usage:
 * ./vendor/bin/rector process "./src/" --config "./vendor/themehybrid/hybrid-dev-tools/bin/rector-downgrade.php" --dry-run
 */
return RectorConfig::configure()
    // uncomment to reach your current PHP version
    // ->withPhpSets(php80: true)
    ->withPhpVersion( Rector\ValueObject\PhpVersion::PHP_80 )
    ->withSets( [
        DowngradeLevelSetList::DOWN_TO_PHP_81,
        DowngradeLevelSetList::DOWN_TO_PHP_80,
        // DowngradeLevelSetList::DOWN_TO_PHP_74,
    ] )
    ->withSkip( [
        // PHP 8.1 rules.
        DowngradeArrayIsListRector::class,

        // PHP 8.0 rules.
        // DowngradeStrContainsRector::class,
        // DowngradeArbitraryExpressionsSupportRector::class,
        // // DowngradeNullsafeToTernaryOperatorRector::class,
        // // DowngradeTrailingCommasInParamUseRector::class,
    ] )
    // @see https://github.com/rectorphp/rector/issues/7056
    ->withoutParallel()
    ->withConfiguredRule( RenameFunctionRector::class, [
        // No need to replace it,
        // as I am using symfony polyfills.
        // @see https://github.com/symfony/polyfill/blob/c0fee7a4f19e41174152cb36ad95da8aaf5f5d8b/src/Php81/bootstrap.php#L26
        // @see https://github.com/rectorphp/rector/issues/8073#issuecomment-1642283745
        // @see https://php.watch/versions/8.1/enums#enum-exists
        // 'enum_exists' => 'class_exists',
        'enum_exists' => 'enum_exists',
    ] );
