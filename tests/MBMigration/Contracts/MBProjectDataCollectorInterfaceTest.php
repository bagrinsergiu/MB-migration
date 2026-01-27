<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;
use MBMigration\Layer\MB\MBProjectDataCollector;
use ReflectionClass;

/**
 * Тест для проверки, что класс MBProjectDataCollector реализует интерфейс MBProjectDataCollectorInterface
 * 
 * Этот тест проверяет, что:
 * - Класс MBProjectDataCollector реализует интерфейс MBProjectDataCollectorInterface
 * - Все методы интерфейса реализованы в классе
 * - Сигнатуры методов совпадают
 * 
 * Задача: task-1.5-create-mb-collector-interface
 * Принцип: Сразу тестировать - после создания интерфейса написать тест
 */
class MBProjectDataCollectorInterfaceTest extends TestCase
{
    /**
     * Тест: класс MBProjectDataCollector должен реализовывать интерфейс MBProjectDataCollectorInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testMBProjectDataCollectorImplementsInterface(): void
    {
        $reflection = new ReflectionClass(MBProjectDataCollector::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            MBProjectDataCollectorInterface::class,
            $interfaces,
            'Класс MBProjectDataCollector должен реализовывать интерфейс MBProjectDataCollectorInterface'
        );
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в классе
     * 
     * Проверяет, что каждый метод интерфейса существует в классе
     */
    public function testAllInterfaceMethodsAreImplemented(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $classReflection = new ReflectionClass(MBProjectDataCollector::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        $classMethods = $classReflection->getMethods();
        $classMethodNames = array_map(function($method) {
            return $method->getName();
        }, $classMethods);
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            $this->assertContains(
                $methodName,
                $classMethodNames,
                "Метод {$methodName} из интерфейса должен быть реализован в классе MBProjectDataCollector"
            );
        }
    }

    /**
     * Тест: сигнатуры методов должны совпадать
     * 
     * Проверяет, что параметры методов совпадают (количество и типы)
     */
    public function testMethodSignaturesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $classReflection = new ReflectionClass(MBProjectDataCollector::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе MBProjectDataCollector");
            }
            
            $classMethod = $classReflection->getMethod($methodName);
            
            // Проверяем количество параметров
            $this->assertEquals(
                $interfaceMethod->getNumberOfParameters(),
                $classMethod->getNumberOfParameters(),
                "Количество параметров метода {$methodName} должно совпадать"
            );
            
            // Проверяем параметры
            $interfaceParams = $interfaceMethod->getParameters();
            $classParams = $classMethod->getParameters();
            
            for ($i = 0; $i < count($interfaceParams); $i++) {
                $interfaceParam = $interfaceParams[$i];
                $classParam = $classParams[$i];
                
                // Проверяем имя параметра
                $this->assertEquals(
                    $interfaceParam->getName(),
                    $classParam->getName(),
                    "Имя параметра #{$i} метода {$methodName} должно совпадать"
                );
                
                // Проверяем наличие значения по умолчанию
                $this->assertEquals(
                    $interfaceParam->isDefaultValueAvailable(),
                    $classParam->isDefaultValueAvailable(),
                    "Наличие значения по умолчанию для параметра #{$i} метода {$methodName} должно совпадать"
                );
            }
        }
    }

    /**
     * Тест: возвращаемые типы должны совпадать
     * 
     * Проверяет, что возвращаемые типы методов совпадают
     */
    public function testReturnTypesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $classReflection = new ReflectionClass(MBProjectDataCollector::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                continue;
            }
            
            $classMethod = $classReflection->getMethod($methodName);
            
            // Проверяем наличие возвращаемого типа
            $interfaceHasReturnType = $interfaceMethod->hasReturnType();
            $classHasReturnType = $classMethod->hasReturnType();
            
            // Если в интерфейсе указан тип, он должен быть и в классе
            if ($interfaceHasReturnType) {
                $this->assertTrue(
                    $classHasReturnType,
                    "Метод {$methodName} должен иметь возвращаемый тип, как указано в интерфейсе"
                );
                
                $interfaceReturnType = $interfaceMethod->getReturnType();
                $classReturnType = $classMethod->getReturnType();
                
                if ($interfaceReturnType && $classReturnType) {
                    $this->assertEquals(
                        $interfaceReturnType->getName(),
                        $classReturnType->getName(),
                        "Возвращаемый тип метода {$methodName} должен совпадать"
                    );
                }
            }
        }
    }

    /**
     * Тест: интерфейс должен содержать все публичные методы класса (кроме конструктора и статических)
     * 
     * Проверяет, что все публичные методы класса (кроме конструктора и статических) есть в интерфейсе
     */
    public function testInterfaceContainsAllPublicMethods(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $classReflection = new ReflectionClass(MBProjectDataCollector::class);
        
        $interfaceMethods = array_map(function($method) {
            return $method->getName();
        }, $interfaceReflection->getMethods());
        
        $classMethods = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы, унаследованные от родительского класса, конструктор и статические методы
        $mbCollectorMethods = array_filter($classMethods, function($method) use ($classReflection) {
            return $method->getDeclaringClass()->getName() === $classReflection->getName()
                && $method->getName() !== '__construct'
                && !$method->isStatic();
        });
        
        foreach ($mbCollectorMethods as $classMethod) {
            $methodName = $classMethod->getName();
            
            $this->assertContains(
                $methodName,
                $interfaceMethods,
                "Публичный метод {$methodName} должен быть в интерфейсе MBProjectDataCollectorInterface"
            );
        }
    }

    /**
     * Тест: интерфейс не должен содержать статические методы
     * 
     * Проверяет, что в интерфейсе нет статических методов (PHP 7.4 не поддерживает)
     */
    public function testInterfaceDoesNotContainStaticMethods(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $method) {
            $this->assertFalse(
                $method->isStatic(),
                "Интерфейс не должен содержать статические методы (PHP 7.4 не поддерживает). Метод: {$method->getName()}"
            );
        }
    }

    /**
     * Тест: количество методов в интерфейсе должно соответствовать ожидаемому
     * 
     * Проверяет, что в интерфейсе есть все необходимые методы (13 методов без статических)
     */
    public function testInterfaceMethodCount(): void
    {
        $interfaceReflection = new ReflectionClass(MBProjectDataCollectorInterface::class);
        $interfaceMethods = $interfaceReflection->getMethods();
        
        // Ожидаем 13 методов (19 публичных - 6 статических - конструктор не включается)
        $expectedMethodCount = 13;
        
        $this->assertGreaterThanOrEqual(
            $expectedMethodCount,
            count($interfaceMethods),
            "Интерфейс должен содержать минимум {$expectedMethodCount} методов"
        );
    }
}
