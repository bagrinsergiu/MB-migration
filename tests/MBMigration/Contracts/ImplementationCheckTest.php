<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\Layer\DataSource\driver\MySQL;
use MBMigration\Layer\DataSource\driver\PostgresSQL;
use MBMigration\Core\S3Uploader;
use MBMigration\Browser\BrowserPHP;
use MBMigration\Browser\Browser;

/**
 * Тест для проверки, что все классы реализуют соответствующие интерфейсы
 * 
 * Этот тест проверяет, что:
 * - Все классы явно реализуют соответствующие интерфейсы
 * - Все use statements добавлены (где необходимо)
 * - Все классы компилируются без ошибок
 * 
 * Задача: task-1.11-check-implementation
 * Принцип: Сразу тестировать - после проверки реализации написать тест
 */
class ImplementationCheckTest extends TestCase
{
    /**
     * Тест: BrizyAPI должен реализовывать BrizyAPIInterface
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
     * Тест: MBProjectDataCollector должен реализовывать MBProjectDataCollectorInterface
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
     * Тест: MySQL должен реализовывать DatabaseInterface
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
     * Тест: PostgresSQL должен реализовывать DatabaseInterface
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
     * Тест: S3Uploader должен реализовывать S3UploaderInterface
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
     * Тест: BrowserPHP должен реализовывать BrowserInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testBrowserPHPImplementsInterface(): void
    {
        $reflection = new ReflectionClass(BrowserPHP::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            \MBMigration\Browser\BrowserInterface::class,
            $interfaces,
            'Класс BrowserPHP должен реализовывать интерфейс BrowserInterface'
        );
    }

    /**
     * Тест: Browser должен реализовывать BrowserInterface
     * 
     * Проверяет, что класс объявлен как реализующий интерфейс
     */
    public function testBrowserImplementsInterface(): void
    {
        $reflection = new ReflectionClass(Browser::class);
        $interfaces = $reflection->getInterfaceNames();
        
        $this->assertContains(
            \MBMigration\Browser\BrowserInterface::class,
            $interfaces,
            'Класс Browser должен реализовывать интерфейс BrowserInterface'
        );
    }

    /**
     * Тест: все классы должны компилироваться без ошибок
     * 
     * Проверяет, что все классы могут быть загружены без ошибок
     */
    public function testAllClassesCanBeLoaded(): void
    {
        $classes = [
            BrizyAPI::class,
            MBProjectDataCollector::class,
            MySQL::class,
            PostgresSQL::class,
            S3Uploader::class,
            BrowserPHP::class,
            Browser::class,
        ];

        foreach ($classes as $className) {
            $this->assertTrue(
                class_exists($className),
                "Класс {$className} должен существовать и быть загружаемым"
            );
        }
    }

    /**
     * Тест: все интерфейсы должны существовать
     * 
     * Проверяет, что все интерфейсы могут быть загружены без ошибок
     */
    public function testAllInterfacesExist(): void
    {
        $interfaces = [
            BrizyAPIInterface::class,
            MBProjectDataCollectorInterface::class,
            DatabaseInterface::class,
            S3UploaderInterface::class,
            \MBMigration\Browser\BrowserInterface::class,
        ];

        foreach ($interfaces as $interfaceName) {
            $this->assertTrue(
                interface_exists($interfaceName),
                "Интерфейс {$interfaceName} должен существовать и быть загружаемым"
            );
        }
    }

    /**
     * Тест: все классы должны реализовывать все методы интерфейсов
     * 
     * Проверяет, что каждый класс реализует все методы соответствующего интерфейса
     */
    public function testAllInterfaceMethodsAreImplemented(): void
    {
        $classInterfacePairs = [
            [BrizyAPI::class, BrizyAPIInterface::class],
            [MBProjectDataCollector::class, MBProjectDataCollectorInterface::class],
            [MySQL::class, DatabaseInterface::class],
            [PostgresSQL::class, DatabaseInterface::class],
            [S3Uploader::class, S3UploaderInterface::class],
            [BrowserPHP::class, \MBMigration\Browser\BrowserInterface::class],
            [Browser::class, \MBMigration\Browser\BrowserInterface::class],
        ];

        foreach ($classInterfacePairs as [$className, $interfaceName]) {
            $classReflection = new ReflectionClass($className);
            $interfaceReflection = new ReflectionClass($interfaceName);
            
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
                    "Метод {$methodName} из интерфейса {$interfaceName} должен быть реализован в классе {$className}"
                );
            }
        }
    }
}
