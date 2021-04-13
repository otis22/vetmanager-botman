<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;
use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayKeysAndInArrayToArrayKeyExistsRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use Rector\CodeQuality\Rector\Array_\ArrayThisCallToThisMethodCallRector;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\CodeQuality\Rector\For_\ForToForeachRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/app']);

    // Define what rule sets will be applied
    $parameters->set(Option::SETS, [
        SetList::DEAD_CODE,
    ]);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    // register a single rule
    $services->set(TypedPropertyRector::class);

    //CodeQuality section from https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#arguments
    $services->set(AbsolutizeRequireAndIncludePathRector::class);
    $services->set(AndAssignsToSeparateLinesRector::class);
    $services->set(ArrayKeyExistsTernaryThenValueToCoalescingRector::class);
    $services->set(ArrayKeysAndInArrayToArrayKeyExistsRector::class);
    $services->set(ArrayMergeOfNonArraysToSimpleArrayRector::class);
    $services->set(ArrayThisCallToThisMethodCallRector::class);
    $services->set(BooleanNotIdenticalToNotIdenticalRector::class);
    $services->set(CallableThisArrayToAnonymousFunctionRector::class);
    $services->set(ChangeArrayPushToArrayAssignRector::class);
    $services->set(CombineIfRector::class);
    $services->set(CombinedAssignRector::class);
    $services->set(CommonNotEqualRector::class);
    $services->set(CompactToVariablesRector::class);
    $services->set(CompleteDynamicPropertiesRector::class);
    $services->set(ConsecutiveNullCompareReturnsToNullCoalesceQueueRector::class);
    $services->set(DateTimeToDateTimeInterfaceRector::class);
    $services->set(ExplicitBoolCompareRector::class);
    $services->set(ForRepeatedCountToOwnVariableRector::class);
    $services->set(ForToForeachRector::class);
    $services->set(ForeachItemsAssignToEmptyArrayToAssignRector::class);
    $services->set(ForeachToInArrayRector::class);
    $services->set(GetClassToInstanceOfRector::class);
    $services->set(InArrayAndArrayKeysToArrayKeyExistsRector::class);
    $services->set(InlineIfToExplicitIfRector::class);
    $services->set(IntvalToTypeCastRector::class);
};
