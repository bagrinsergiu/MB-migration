<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;
use MBMigration\Layer\DataSource\driver\MySQL;
use MBMigration\Layer\DataSource\driver\PostgresSQL;
use ReflectionClass;

/**
 * Тест для проверки, что классы MySQL и PostgresSQL реализуют интерфейс DatabaseInterface
 * 
 * Этот тест проверяет, что:
 * - Класс MySQL реализует интерфейс DatabaseInterface
 * - Класс PostgresSQL реализует интерфейс DatabaseInterface
 * - Все методы интерфейса реализованы в обоих классах
 * - Сигнатуры методов совпадают
 * 
 * Задача: task-1.7-create-database-interface
 * Принцип: Сразу тестировать - после создания интерфейса написать тест
 */
class DatabaseInterfaceTest extends TestCase
{
    /**
     * Тест: класс MySQL должен реализовывать интерфейс DatabaseInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testMySQLImplementsInterface(): void
    {
        $reflection = new ReflectionClass(MySQL::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            DatabaseInterface::class,
            $interfaces,
            'Класс MySQL должен реализовывать интерфейс DatabaseInterface'
        );
    }

    /**
     * Тест: класс PostgresSQL должен реализовывать интерфейс DatabaseInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testPostgresSQLImplementsInterface(): void
    {
        $reflection = new ReflectionClass(PostgresSQL::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            DatabaseInterface::class,
            $interfaces,
            'Класс PostgresSQL должен реализовывать интерфейс DatabaseInterface'
        );
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в MySQL
     * 
     * Проверяет, что каждый метод интерфейса существует в классе MySQL
     */
    public function testAllInterfaceMethodsAreImplementedInMySQL(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(MySQL::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе MySQL"
            );
        }
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в PostgresSQL
     * 
     * Проверяет, что каждый метод интерфейса существует в классе PostgresSQL
     */
    public function testAllInterfaceMethodsAreImplementedInPostgresSQL(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(PostgresSQL::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе PostgresSQL"
            );
        }
    }

    /**
     * Тест: сигнатуры методов MySQL должны совпадать с интерфейсом
     * 
     * Проверяет, что параметры методов совпадают (количество и типы)
     */
    public function testMySQLMethodSignaturesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(MySQL::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе MySQL");
            }
            
            $classMethod = $classReflection->getMethod($methodName);
            
            // Проверяем количество параметров
            $this->assertEquals(
                $interfaceMethod->getNumberOfParameters(),
                $classMethod->getNumberOfParameters(),
                "Количество параметров метода {$methodName} должно совпадать в MySQL"
            );
        }
    }

    /**
     * Тест: сигнатуры методов PostgresSQL должны совпадать с интерфейсом
     * 
     * Проверяет, что параметры методов совпадают (количество и типы)
     */
    public function testPostgresSQLMethodSignaturesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(PostgresSQL::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе PostgresSQL");
            }
            
            $classMethod = $classReflection->getMethod($methodName);
            
            // Проверяем количество параметров
            $this->assertEquals(
                $interfaceMethod->getNumberOfParameters(),
                $classMethod->getNumberOfParameters(),
                "Количество параметров метода {$methodName} должно совпадать в PostgresSQL"
            );
        }
    }

    /**
     * Тест: возвращаемые типы методов MySQL должны совпадать
     * 
     * Проверяет, что возвращаемые типы методов совпадают
     */
    public function testMySQLReturnTypesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(MySQL::class);
        
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
                    "Метод {$methodName} в MySQL должен иметь возвращаемый тип, как указано в интерфейсе"
                );
                
                $interfaceReturnType = $interfaceMethod->getReturnType();
                $classReturnType = $classMethod->getReturnType();
                
                if ($interfaceReturnType && $classReturnType) {
                    $this->assertEquals(
                        $interfaceReturnType->getName(),
                        $classReturnType->getName(),
                        "Возвращаемый тип метода {$methodName} в MySQL должен совпадать"
                    );
                }
            }
        }
    }

    /**
     * Тест: возвращаемые типы методов PostgresSQL должны совпадать
     * 
     * Проверяет, что возвращаемые типы методов совпадают
     */
    public function testPostgresSQLReturnTypesMatch(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $classReflection = new ReflectionClass(PostgresSQL::class);
        
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
                    "Метод {$methodName} в PostgresSQL должен иметь возвращаемый тип, как указано в интерфейсе"
                );
                
                $interfaceReturnType = $interfaceMethod->getReturnType();
                $classReturnType = $classMethod->getReturnType();
                
                if ($interfaceReturnType && $classReturnType) {
                    $this->assertEquals(
                        $interfaceReturnType->getName(),
                        $classReturnType->getName(),
                        "Возвращаемый тип метода {$methodName} в PostgresSQL должен совпадать"
                    );
                }
            }
        }
    }

    /**
     * Тест: интерфейс должен содержать ожидаемые методы
     * 
     * Проверяет, что интерфейс содержит методы query и queryOne
     */
    public function testInterfaceContainsExpectedMethods(): void
    {
        $interfaceReflection = new ReflectionClass(DatabaseInterface::class);
        $interfaceMethods = array_map(function($method) {
            return $method->getName();
        }, $interfaceReflection->getMethods());
        
        $expectedMethods = ['query', 'queryOne'];
        
        foreach ($expectedMethods as $methodName) {
            $this->assertContains(
                $methodName,
                $interfaceMethods,
                "Интерфейс должен содержать метод {$methodName}"
            );
        }
    }
}
