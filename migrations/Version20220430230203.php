<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220430230203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEE551F0F81 ON orders (order_number)');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('DROP INDEX UNIQ_E52FFDEE551F0F81');
    }
}
