<?php
// tests/Core/BaseModelTest.php
use PHPUnit\Framework\TestCase;
use StacGate\Core\BaseModel;
use PDO;

final class BaseModelTest extends TestCase
{
    /**
     * @covers \StacGate\Core\BaseModel::db
     */
    public function testPdoConnection(): void
    {
        $pdo   = BaseModel::db();
        $value = (int) $pdo->query('SELECT 1')->fetchColumn();

        $this->assertInstanceOf(PDO::class, $pdo);
        $this->assertSame(1, $value);
    }
}
