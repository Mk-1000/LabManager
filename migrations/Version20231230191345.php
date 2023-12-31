<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230191345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chercheur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, mot_de_passe VARCHAR(50) NOT NULL, role VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, project_de_recherche_id INT DEFAULT NULL, chercheur_id INT NOT NULL, nom VARCHAR(50) NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_D338D583649BD9FF (project_de_recherche_id), INDEX IDX_D338D583F0950B34 (chercheur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_de_recherche (id INT AUTO_INCREMENT NOT NULL, chercheur_id INT NOT NULL, titre VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, etat_avancement SMALLINT DEFAULT NULL, INDEX IDX_41949D2CF0950B34 (chercheur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, project_de_recherche_id INT NOT NULL, titre VARCHAR(50) NOT NULL, auteurs VARCHAR(50) NOT NULL, mots_cles VARCHAR(50) NOT NULL, INDEX IDX_AF3C6779649BD9FF (project_de_recherche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D583649BD9FF FOREIGN KEY (project_de_recherche_id) REFERENCES project_de_recherche (id)');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D583F0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id)');
        $this->addSql('ALTER TABLE project_de_recherche ADD CONSTRAINT FK_41949D2CF0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779649BD9FF FOREIGN KEY (project_de_recherche_id) REFERENCES project_de_recherche (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D583649BD9FF');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D583F0950B34');
        $this->addSql('ALTER TABLE project_de_recherche DROP FOREIGN KEY FK_41949D2CF0950B34');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779649BD9FF');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE chercheur');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE project_de_recherche');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
