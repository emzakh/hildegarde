<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420150549 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE type_recettes');
        $this->addSql('DROP TABLE user_img_modify');
        $this->addSql('ALTER TABLE type ADD liaison_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE5729ED31185 FOREIGN KEY (liaison_id) REFERENCES recettes (id)');
        $this->addSql('CREATE INDEX IDX_8CDE5729ED31185 ON type (liaison_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_recettes (type_id INT NOT NULL, recettes_id INT NOT NULL, INDEX IDX_26B7C0D0C54C8C93 (type_id), INDEX IDX_26B7C0D03E2ED6D6 (recettes_id), PRIMARY KEY(type_id, recettes_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_img_modify (id INT AUTO_INCREMENT NOT NULL, new_picture VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE type_recettes ADD CONSTRAINT FK_26B7C0D03E2ED6D6 FOREIGN KEY (recettes_id) REFERENCES recettes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_recettes ADD CONSTRAINT FK_26B7C0D0C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE5729ED31185');
        $this->addSql('DROP INDEX IDX_8CDE5729ED31185 ON type');
        $this->addSql('ALTER TABLE type DROP liaison_id');
    }
}
