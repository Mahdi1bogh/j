<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218222407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id_equipe INT AUTO_INCREMENT NOT NULL, nom_equipe VARCHAR(30) NOT NULL, jeu_equipe VARCHAR(30) NOT NULL, logo_equipe VARCHAR(100) NOT NULL, id_J1 INT DEFAULT NULL, id_J2 INT DEFAULT NULL, id_J3 INT DEFAULT NULL, id_J4 INT DEFAULT NULL, id_J5 INT DEFAULT NULL, INDEX IDX_2449BA15DAEC4C4D (id_J1), INDEX IDX_2449BA1543E51DF7 (id_J2), INDEX IDX_2449BA1534E22D61 (id_J3), INDEX IDX_2449BA15AA86B8C2 (id_J4), INDEX IDX_2449BA15DD818854 (id_J5), PRIMARY KEY(id_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id_joueur INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, nom_joueur VARCHAR(30) NOT NULL, prenom_joueur VARCHAR(30) NOT NULL, age_joueur INT NOT NULL, email_joueur VARCHAR(30) NOT NULL, INDEX IDX_FD71A9C56B3CA4B (id_user), PRIMARY KEY(id_joueur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match1 (id_match1 INT AUTO_INCREMENT NOT NULL, nom_equipe1 VARCHAR(255) NOT NULL, nom_equipe2 VARCHAR(255) NOT NULL, date_match1 VARCHAR(255) NOT NULL, resultat_match1 VARCHAR(255) NOT NULL, lieu_match1 VARCHAR(255) NOT NULL, email_lieu VARCHAR(255) NOT NULL, PRIMARY KEY(id_match1)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id_reclam INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, type_reclam VARCHAR(50) NOT NULL, motif_reclam VARCHAR(50) NOT NULL, etat_reclam VARCHAR(50) NOT NULL, message_reclam VARCHAR(255) NOT NULL, date_reclam DATETIME DEFAULT NULL, INDEX id_user (id_user), PRIMARY KEY(id_reclam)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournois (id_tournois INT AUTO_INCREMENT NOT NULL, nom_tournois VARCHAR(255) NOT NULL, rank_tournois VARCHAR(255) NOT NULL, date_tournois VARCHAR(255) NOT NULL, PRIMARY KEY(id_tournois)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15DAEC4C4D FOREIGN KEY (id_J1) REFERENCES joueur (id_joueur)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1543E51DF7 FOREIGN KEY (id_J2) REFERENCES joueur (id_joueur)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1534E22D61 FOREIGN KEY (id_J3) REFERENCES joueur (id_joueur)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15AA86B8C2 FOREIGN KEY (id_J4) REFERENCES joueur (id_joueur)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15DD818854 FOREIGN KEY (id_J5) REFERENCES joueur (id_joueur)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C56B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE avis CHANGE text_avis text_avis VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F7384557 FOREIGN KEY (id_produit) REFERENCES produits (id_produit)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF06B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('DROP INDEX nom_categorie ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE nom_categorie nom_categorie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC9486A13');
        $this->addSql('ALTER TABLE produits CHANGE nom_produit nom_produit VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX fk_prod_categ ON produits');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CC9486A13 ON produits (id_categorie)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15DAEC4C4D');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1543E51DF7');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1534E22D61');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15AA86B8C2');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15DD818854');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE match1');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE tournois');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F7384557');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF06B3CA4B');
        $this->addSql('ALTER TABLE avis CHANGE text_avis text_avis VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE nom_categorie nom_categorie VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX nom_categorie ON categorie (nom_categorie)');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC9486A13');
        $this->addSql('ALTER TABLE produits CHANGE nom_produit nom_produit VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX idx_be2ddf8cc9486a13 ON produits');
        $this->addSql('CREATE INDEX fk_prod_categ ON produits (id_categorie)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
    }
}
