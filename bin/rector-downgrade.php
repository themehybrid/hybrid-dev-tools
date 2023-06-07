<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\DowngradePhp80\Rector\FuncCall\DowngradeStrContainsRector;
use Rector\DowngradePhp80\Rector\New_\DowngradeArbitraryExpressionsSupportRector;
use Rector\DowngradePhp80\Rector\NullsafeMethodCall\DowngradeNullsafeToTernaryOperatorRector;
use Rector\DowngradePhp80\Rector\ClassMethod\DowngradeTrailingCommasInParamUseRector;
use Rector\DowngradePhp81\Rector\FuncCall\DowngradeArrayIsListRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
/**
 * usage:
 * ./vendor/bin/rector process "./src/" --config "./vendor/themehybrid/hybrid-dev-tools/bin/rector-downgrade.php"
 */
return static function (RectorConfig $rectorConfig): void {

    // @see https://github.com/rectorphp/rector/issues/7056
    $rectorConfig->disableParallel();

	/*
    $rectorConfig->paths([
        __DIR__ . '/../src/'
    ]);
	*/

    $rectorConfig->skip([
        //     '*/Tests/*',
        //     '*/tests/*',
        //     __DIR__ . '/../../tests',

        // PHP 8.1 rules.
        DowngradeArrayIsListRector::class,

        // PHP 8.0 rules.
        DowngradeStrContainsRector::class,
        DowngradeArbitraryExpressionsSupportRector::class,
        // DowngradeNullsafeToTernaryOperatorRector::class,
        // DowngradeTrailingCommasInParamUseRector::class,
    ]);

	$rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
		// No need to replace it,
		// as I am using symfony polyfills.
		// @see https://github.com/rectorphp/rector/issues/8073#issuecomment-1642283745
		// @see https://php.watch/versions/8.1/enums#enum-exists
		// 'enum_exists' => 'class_exists',
		'enum_exists' => 'enum_exists',
	]);

    // Define what rule sets will be applied.
    $rectorConfig->import(DowngradeLevelSetList::DOWN_TO_PHP_81);
    $rectorConfig->import(DowngradeLevelSetList::DOWN_TO_PHP_80);
    // $rectorConfig->import(DowngradeLevelSetList::DOWN_TO_PHP_74);
};
