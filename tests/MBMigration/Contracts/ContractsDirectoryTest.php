<?php

namespace MBMigration\Contracts;

use PHPUnit\Framework\TestCase;

/**
 * Тест для проверки существования директории Contracts
 * 
 * Этот тест проверяет, что директория lib/MBMigration/Contracts/ создана
 * и готова для размещения интерфейсов (контрактов).
 * 
 * Задача: task-1.1-create-directory
 * Принцип: Сразу тестировать - после создания директории написать тест
 */
class ContractsDirectoryTest extends TestCase
{
    /**
     * Путь к директории Contracts относительно корня проекта
     */
    private const CONTRACTS_DIR = __DIR__ . '/../../../lib/MBMigration/Contracts';

    /**
     * Тест: директория Contracts должна существовать
     * 
     * Проверяет, что директория создана и доступна для чтения
     */
    public function testContractsDirectoryExists(): void
    {
        $this->assertDirectoryExists(
            self::CONTRACTS_DIR,
            'Директория lib/MBMigration/Contracts должна существовать'
        );
    }

    /**
     * Тест: директория Contracts должна быть доступна для чтения
     * 
     * Проверяет, что директория доступна для чтения
     */
    public function testContractsDirectoryIsReadable(): void
    {
        $this->assertDirectoryIsReadable(
            self::CONTRACTS_DIR,
            'Директория lib/MBMigration/Contracts должна быть доступна для чтения'
        );
    }

    /**
     * Тест: директория Contracts должна быть доступна для записи
     * 
     * Проверяет, что в директорию можно создавать файлы (интерфейсы)
     */
    public function testContractsDirectoryIsWritable(): void
    {
        $this->assertDirectoryIsWritable(
            self::CONTRACTS_DIR,
            'Директория lib/MBMigration/Contracts должна быть доступна для записи'
        );
    }

    /**
     * Тест: файл .gitkeep должен существовать
     * 
     * Проверяет, что файл .gitkeep создан для сохранения пустой директории в git
     */
    public function testGitkeepFileExists(): void
    {
        $gitkeepPath = self::CONTRACTS_DIR . '/.gitkeep';
        $this->assertFileExists(
            $gitkeepPath,
            'Файл .gitkeep должен существовать в директории Contracts'
        );
    }

    /**
     * Тест: файл README.md должен существовать
     * 
     * Проверяет, что файл README.md создан с описанием назначения директории
     */
    public function testReadmeFileExists(): void
    {
        $readmePath = self::CONTRACTS_DIR . '/README.md';
        $this->assertFileExists(
            $readmePath,
            'Файл README.md должен существовать в директории Contracts'
        );
    }
}
