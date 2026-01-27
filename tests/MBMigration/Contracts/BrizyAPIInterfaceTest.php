<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;
use MBMigration\Layer\Brizy\BrizyAPI;
use ReflectionClass;

/**
 * Тест для проверки, что класс BrizyAPI реализует интерфейс BrizyAPIInterface
 * 
 * Этот тест проверяет, что:
 * - Класс BrizyAPI реализует интерфейс BrizyAPIInterface
 * - Все методы интерфейса реализованы в классе
 * - Сигнатуры методов совпадают
 * 
 * Задача: task-1.3-create-brizy-api-interface
 * Принцип: Сразу тестировать - после создания интерфейса написать тест
 */
class BrizyAPIInterfaceTest extends TestCase
{
    /**
     * Тест: класс BrizyAPI должен реализовывать интерфейс BrizyAPIInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testBrizyAPIImplementsInterface(): void
    {
        $reflection = new ReflectionClass(BrizyAPI::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            BrizyAPIInterface::class,
            $interfaces,
            'Класс BrizyAPI должен реализовывать интерфейс BrizyAPIInterface'
        );
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в классе
     * 
     * Проверяет, что каждый метод интерфейса существует в классе
     */
    public function testAllInterfaceMethodsAreImplemented(): void
    {
        $interfaceReflection = new ReflectionClass(BrizyAPIInterface::class);
        $classReflection = new ReflectionClass(BrizyAPI::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе BrizyAPI"
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
        $interfaceReflection = new ReflectionClass(BrizyAPIInterface::class);
        $classReflection = new ReflectionClass(BrizyAPI::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе BrizyAPI");
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
        $interfaceReflection = new ReflectionClass(BrizyAPIInterface::class);
        $classReflection = new ReflectionClass(BrizyAPI::class);
        
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
     * Тест: интерфейс должен содержать все публичные методы класса
     * 
     * Проверяет, что все публичные методы класса (кроме конструктора) есть в интерфейсе
     */
    public function testInterfaceContainsAllPublicMethods(): void
    {
        $interfaceReflection = new ReflectionClass(BrizyAPIInterface::class);
        $classReflection = new ReflectionClass(BrizyAPI::class);
        
        $interfaceMethods = array_map(function($method) {
            return $method->getName();
        }, $interfaceReflection->getMethods());
        
        $classMethods = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы, унаследованные от родительского класса и конструктор
        $brizyApiMethods = array_filter($classMethods, function($method) use ($classReflection) {
            return $method->getDeclaringClass()->getName() === $classReflection->getName()
                && $method->getName() !== '__construct';
        });
        
        foreach ($brizyApiMethods as $classMethod) {
            $methodName = $classMethod->getName();
            
            $this->assertContains(
                $methodName,
                $interfaceMethods,
                "Публичный метод {$methodName} должен быть в интерфейсе BrizyAPIInterface"
            );
        }
    }
}
