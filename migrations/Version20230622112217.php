<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622112217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dbarti_el_prefere ADD CONSTRAINT FK_29985E9759D8A214 FOREIGN KEY (recipe_id) REFERENCES dbaretelchef (id)');
        $this->addSql('CREATE INDEX IDX_29985E9759D8A214 ON dbarti_el_prefere (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dbarti_el_prefere DROP FOREIGN KEY FK_29985E9759D8A214');
        $this->addSql('DROP INDEX IDX_29985E9759D8A214 ON dbarti_el_prefere');
    }
}
