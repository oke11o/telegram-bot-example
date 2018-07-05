<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180705102618 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE user (
  id                INT AUTO_INCREMENT NOT NULL,
  username          VARCHAR(255)       NOT NULL,
  telegram_id       INT DEFAULT NULL,
  telegram_username VARCHAR(255)       NOT NULL,
  first_name        VARCHAR(255)       NOT NULL,
  last_name         VARCHAR(255)       NOT NULL,
  telegram_chat_ids LONGTEXT           NOT NULL
  COMMENT \'(DC2Type:simple_array)\',
  locale            VARCHAR(255)       NOT NULL,
  is_telegram_bot   TINYINT(1)         NOT NULL,
  created_at        DATETIME           NOT NULL,
  updated_at        DATETIME           NOT NULL,
  INDEX idx_user_username (username),
  INDEX idx_user_telegram_id (telegram_id),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE user');
    }
}
