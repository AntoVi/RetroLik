<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221132528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('CREATE INDEX IDX_9474526C1EBAF6CC ON comment (articles_id)');
        $this->addSql('ALTER TABLE jeux ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE jeux ADD CONSTRAINT FK_3755B50D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_3755B50D12469DE2 ON jeux (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1EBAF6CC');
        $this->addSql('DROP INDEX IDX_9474526C1EBAF6CC ON comment');
        $this->addSql('ALTER TABLE jeux DROP FOREIGN KEY FK_3755B50D12469DE2');
        $this->addSql('DROP INDEX IDX_3755B50D12469DE2 ON jeux');
        $this->addSql('ALTER TABLE jeux DROP category_id');
    }
}
