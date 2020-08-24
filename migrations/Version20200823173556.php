<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823173556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX user_email_uindex ON user');
        $this->addSql('DROP INDEX user_id_uindex ON user');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX user_username_uindex TO UNIQ_8D93D649F85E0677');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE roles roles JSON DEFAULT NULL, CHANGE password password VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE email email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, CHANGE is_verified is_verified TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX user_email_uindex ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX user_id_uindex ON user (id)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO user_username_uindex');
    }
}
