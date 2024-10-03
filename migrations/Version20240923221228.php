<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923221228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD introduction LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e669f6c9f4 TO IDX_23A0E6612469DE2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP introduction');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e6612469de2 TO IDX_23A0E669F6C9F4');
    }
}
