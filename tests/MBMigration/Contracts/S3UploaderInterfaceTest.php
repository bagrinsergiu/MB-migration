<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;
use MBMigration\Core\S3Uploader;
use ReflectionClass;

/**
 * Тест для проверки, что класс S3Uploader реализует интерфейс S3UploaderInterface
 * 
 * Этот тест проверяет, что:
 * - Класс S3Uploader реализует интерфейс S3UploaderInterface
 * - Все методы интерфейса реализованы в классе
 * - Сигнатуры методов совпадают
 * 
 * Задача: task-1.9-create-s3-uploader-interface
 * Принцип: Сразу тестировать - после создания интерфейса написать тест
 */
class S3UploaderInterfaceTest extends TestCase
{
    /**
     * Тест: класс S3Uploader должен реализовывать интерфейс S3UploaderInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testS3UploaderImplementsInterface(): void
    {
        $reflection = new ReflectionClass(S3Uploader::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            S3UploaderInterface::class,
            $interfaces,
            'Класс S3Uploader должен реализовывать интерфейс S3UploaderInterface'
        );
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в классе
     * 
     * Проверяет, что каждый метод интерфейса существует в классе
     */
    public function testAllInterfaceMethodsAreImplemented(): void
    {
        $interfaceReflection = new ReflectionClass(S3UploaderInterface::class);
        $classReflection = new ReflectionClass(S3Uploader::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе S3Uploader"
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
        $interfaceReflection = new ReflectionClass(S3UploaderInterface::class);
        $classReflection = new ReflectionClass(S3Uploader::class);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе S3Uploader");
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
        $interfaceReflection = new ReflectionClass(S3UploaderInterface::class);
        $classReflection = new ReflectionClass(S3Uploader::class);
        
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
     * Тест: интерфейс должен содержать все публичные методы класса (кроме конструктора)
     * 
     * Проверяет, что все публичные методы класса (кроме конструктора) есть в интерфейсе
     */
    public function testInterfaceContainsAllPublicMethods(): void
    {
        $interfaceReflection = new ReflectionClass(S3UploaderInterface::class);
        $classReflection = new ReflectionClass(S3Uploader::class);
        
        $interfaceMethods = array_map(function($method) {
            return $method->getName();
        }, $interfaceReflection->getMethods());
        
        $classMethods = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор
        $s3UploaderMethods = array_filter($classMethods, function($method) {
            return $method->getName() !== '__construct';
        });
        
        foreach ($s3UploaderMethods as $classMethod) {
            $methodName = $classMethod->getName();
            
            $this->assertContains(
                $methodName,
                $interfaceMethods,
                "Публичный метод {$methodName} должен быть в интерфейсе S3UploaderInterface"
            );
        }
    }

    /**
     * Тест: количество методов в интерфейсе должно соответствовать ожидаемому
     * 
     * Проверяет, что в интерфейсе есть один метод (uploadLogFile)
     */
    public function testInterfaceMethodCount(): void
    {
        $interfaceReflection = new ReflectionClass(S3UploaderInterface::class);
        $interfaceMethods = $interfaceReflection->getMethods();
        
        // Ожидаем 1 метод (uploadLogFile)
        $expectedMethodCount = 1;
        
        $this->assertEquals(
            $expectedMethodCount,
            count($interfaceMethods),
            "Интерфейс должен содержать {$expectedMethodCount} метод"
        );
        
        // Проверяем, что метод называется uploadLogFile
        $this->assertContains(
            'uploadLogFile',
            array_map(function($method) {
                return $method->getName();
            }, $interfaceMethods),
            "Интерфейс должен содержать метод uploadLogFile"
        );
    }
}
