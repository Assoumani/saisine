<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714044341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department CHANGE number number INT DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE ticket_id ticket_id INT DEFAULT NULL, CHANGE file file JSON DEFAULT NULL, CHANGE author_role author_role VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE department_id department_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE surname surname VARCHAR(255) DEFAULT NULL, CHANGE field_activity field_activity VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE ticket_number ticket_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE department_id department_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department CHANGE number number INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE message CHANGE ticket_id ticket_id INT DEFAULT NULL, CHANGE file file VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE author_role author_role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ticket CHANGE department_id department_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE surname surname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE field_activity field_activity VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE ticket_number ticket_number VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE department_id department_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
