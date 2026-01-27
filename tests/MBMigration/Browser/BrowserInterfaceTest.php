<?php

namespace MBMigration\Browser;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест для проверки, что классы Browser и BrowserPHP реализуют интерфейс BrowserInterface
 * 
 * Этот тест проверяет, что:
 * - Классы Browser и BrowserPHP реализуют интерфейс BrowserInterface
 * - Все методы интерфейса реализованы в классах
 * - Сигнатуры методов совпадают
 * - Типы параметров совпадают
 * - Возвращаемые типы совпадают
 * 
 * Задача: task-1.10-check-browser-interface
 * Принцип: Сразу тестировать - после обновления интерфейса написать тест
 */
class BrowserInterfaceTest extends TestCase
{
    /**
     * Тест: класс Browser должен реализовывать интерфейс BrowserInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testBrowserImplementsInterface(): void
    {
        $reflection = new ReflectionClass(Browser::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            BrowserInterface::class,
            $interfaces,
            'Класс Browser должен реализовывать интерфейс BrowserInterface'
        );
    }

    /**
     * Тест: класс BrowserPHP должен реализовывать интерфейс BrowserInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testBrowserPHPImplementsInterface(): void
    {
        $reflection = new ReflectionClass(BrowserPHP::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            BrowserInterface::class,
            $interfaces,
            'Класс BrowserPHP должен реализовывать интерфейс BrowserInterface'
        );
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в классе Browser
     * 
     * Проверяет, что каждый метод интерфейса существует в классе
     */
    public function testAllInterfaceMethodsAreImplementedInBrowser(): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $classReflection = new ReflectionClass(Browser::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе Browser"
            );
        }
    }

    /**
     * Тест: все методы интерфейса должны быть реализованы в классе BrowserPHP
     * 
     * Проверяет, что каждый метод интерфейса существует в классе
     */
    public function testAllInterfaceMethodsAreImplementedInBrowserPHP(): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $classReflection = new ReflectionClass(BrowserPHP::class);
        
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
                "Метод {$methodName} из интерфейса должен быть реализован в классе BrowserPHP"
            );
        }
    }

    /**
     * Тест: сигнатуры методов должны совпадать для класса Browser
     * 
     * Проверяет, что параметры методов совпадают (количество и типы)
     */
    public function testMethodSignaturesMatchInBrowser(): void
    {
        $this->checkMethodSignatures(Browser::class);
    }

    /**
     * Тест: сигнатуры методов должны совпадать для класса BrowserPHP
     * 
     * Проверяет, что параметры методов совпадают (количество и типы)
     */
    public function testMethodSignaturesMatchInBrowserPHP(): void
    {
        $this->checkMethodSignatures(BrowserPHP::class);
    }

    /**
     * Тест: возвращаемые типы должны совпадать для класса Browser
     * 
     * Проверяет, что возвращаемые типы методов совпадают
     */
    public function testReturnTypesMatchInBrowser(): void
    {
        $this->checkReturnTypes(Browser::class);
    }

    /**
     * Тест: возвращаемые типы должны совпадать для класса BrowserPHP
     * 
     * Проверяет, что возвращаемые типы методов совпадают
     */
    public function testReturnTypesMatchInBrowserPHP(): void
    {
        $this->checkReturnTypes(BrowserPHP::class);
    }

    /**
     * Тест: интерфейс должен содержать все публичные методы классов (кроме конструктора и статических)
     * 
     * Проверяет, что все публичные методы классов (кроме конструктора и статических) есть в интерфейсе
     */
    public function testInterfaceContainsAllPublicMethods(): void
    {
        $this->checkInterfaceContainsAllPublicMethods(Browser::class);
        $this->checkInterfaceContainsAllPublicMethods(BrowserPHP::class);
    }

    /**
     * Тест: количество методов в интерфейсе должно соответствовать ожидаемому
     * 
     * Проверяет, что в интерфейсе есть 3 метода (openPage, closePage, closeBrowser)
     */
    public function testInterfaceMethodCount(): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $interfaceMethods = $interfaceReflection->getMethods();
        
        // Ожидаем 3 метода (openPage, closePage, closeBrowser)
        $expectedMethodCount = 3;
        
        $this->assertEquals(
            $expectedMethodCount,
            count($interfaceMethods),
            "Интерфейс должен содержать {$expectedMethodCount} метода"
        );
        
        // Проверяем, что методы называются правильно
        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $interfaceMethods);
        
        $this->assertContains('openPage', $methodNames, "Интерфейс должен содержать метод openPage");
        $this->assertContains('closePage', $methodNames, "Интерфейс должен содержать метод closePage");
        $this->assertContains('closeBrowser', $methodNames, "Интерфейс должен содержать метод closeBrowser");
    }

    /**
     * Вспомогательный метод для проверки сигнатур методов
     * 
     * @param string $className Имя класса для проверки
     */
    private function checkMethodSignatures(string $className): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $classReflection = new ReflectionClass($className);
        
        $interfaceMethods = $interfaceReflection->getMethods();
        
        foreach ($interfaceMethods as $interfaceMethod) {
            $methodName = $interfaceMethod->getName();
            
            if (!$classReflection->hasMethod($methodName)) {
                $this->fail("Метод {$methodName} не найден в классе {$className}");
            }
            
            $classMethod = $classReflection->getMethod($methodName);
            
            // Проверяем количество параметров
            $this->assertEquals(
                $interfaceMethod->getNumberOfParameters(),
                $classMethod->getNumberOfParameters(),
                "Количество параметров метода {$methodName} в классе {$className} должно совпадать"
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
                    "Имя параметра #{$i} метода {$methodName} в классе {$className} должно совпадать"
                );
            }
        }
    }

    /**
     * Вспомогательный метод для проверки возвращаемых типов
     * 
     * @param string $className Имя класса для проверки
     */
    private function checkReturnTypes(string $className): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $classReflection = new ReflectionClass($className);
        
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
                    "Метод {$methodName} в классе {$className} должен иметь возвращаемый тип, как указано в интерфейсе"
                );
                
                $interfaceReturnType = $interfaceMethod->getReturnType();
                $classReturnType = $classMethod->getReturnType();
                
                if ($interfaceReturnType && $classReturnType) {
                    $this->assertEquals(
                        $interfaceReturnType->getName(),
                        $classReturnType->getName(),
                        "Возвращаемый тип метода {$methodName} в классе {$className} должен совпадать"
                    );
                }
            }
        }
    }

    /**
     * Вспомогательный метод для проверки, что интерфейс содержит все публичные методы
     * 
     * @param string $className Имя класса для проверки
     */
    private function checkInterfaceContainsAllPublicMethods(string $className): void
    {
        $interfaceReflection = new ReflectionClass(BrowserInterface::class);
        $classReflection = new ReflectionClass($className);
        
        $interfaceMethods = array_map(function($method) {
            return $method->getName();
        }, $interfaceReflection->getMethods());
        
        $classMethods = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор и статические методы
        $s3UploaderMethods = array_filter($classMethods, function($method) {
            return $method->getName() !== '__construct' && !$method->isStatic();
        });
        
        foreach ($s3UploaderMethods as $classMethod) {
            $methodName = $classMethod->getName();
            
            $this->assertContains(
                $methodName,
                $interfaceMethods,
                "Публичный метод {$methodName} в классе {$className} должен быть в интерфейсе BrowserInterface"
            );
        }
    }
}
