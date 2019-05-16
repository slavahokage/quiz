<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516151846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin_quiz_table_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_table_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE result_of_quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE admin_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, title VARCHAR(191) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE admin_quiz_table (id INT NOT NULL, title VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, current_question INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C26450432B36786B ON admin_quiz_table (title)');
        $this->addSql('CREATE TABLE admin_quiz_table_admin_question (admin_quiz_table_id INT NOT NULL, admin_question_id INT NOT NULL, PRIMARY KEY(admin_quiz_table_id, admin_question_id))');
        $this->addSql('CREATE INDEX IDX_70763F19E4F90E7E ON admin_quiz_table_admin_question (admin_quiz_table_id)');
        $this->addSql('CREATE INDEX IDX_70763F19833DBFB6 ON admin_quiz_table_admin_question (admin_question_id)');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, username VARCHAR(191) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(191) NOT NULL, is_active BOOLEAN NOT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F85E0677 ON client (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('COMMENT ON COLUMN client.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE admin_question (id INT NOT NULL, title VARCHAR(191) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quiz_table (id INT NOT NULL, title VARCHAR(191) NOT NULL, is_active BOOLEAN NOT NULL, can_look BOOLEAN NOT NULL, description VARCHAR(191) NOT NULL, date_of_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE quiz_table_question (quiz_table_id INT NOT NULL, question_id INT NOT NULL, PRIMARY KEY(quiz_table_id, question_id))');
        $this->addSql('CREATE INDEX IDX_4CD0FEBE3A3BD119 ON quiz_table_question (quiz_table_id)');
        $this->addSql('CREATE INDEX IDX_4CD0FEBE1E27F6BF ON quiz_table_question (question_id)');
        $this->addSql('CREATE TABLE result_of_quiz (id INT NOT NULL, quiz_id INT NOT NULL, user_id INT NOT NULL, score INT NOT NULL, time INT NOT NULL, current_question INT NOT NULL, is_over BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6313F045853CD175 ON result_of_quiz (quiz_id)');
        $this->addSql('CREATE INDEX IDX_6313F045A76ED395 ON result_of_quiz (user_id)');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, question_id INT NOT NULL, title VARCHAR(191) NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');
        $this->addSql('CREATE TABLE admin_answer (id INT NOT NULL, question_id INT NOT NULL, title VARCHAR(191) NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_87157BDC1E27F6BF ON admin_answer (question_id)');
        $this->addSql('CREATE TABLE article (id INT NOT NULL, title VARCHAR(191) NOT NULL, source VARCHAR(191) NOT NULL, real_name VARCHAR(191) NOT NULL, extension VARCHAR(191) NOT NULL, mime VARCHAR(191) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question ADD CONSTRAINT FK_70763F19E4F90E7E FOREIGN KEY (admin_quiz_table_id) REFERENCES admin_quiz_table (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question ADD CONSTRAINT FK_70763F19833DBFB6 FOREIGN KEY (admin_question_id) REFERENCES admin_question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_table_question ADD CONSTRAINT FK_4CD0FEBE3A3BD119 FOREIGN KEY (quiz_table_id) REFERENCES quiz_table (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_table_question ADD CONSTRAINT FK_4CD0FEBE1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result_of_quiz ADD CONSTRAINT FK_6313F045853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz_table (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result_of_quiz ADD CONSTRAINT FK_6313F045A76ED395 FOREIGN KEY (user_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE admin_answer ADD CONSTRAINT FK_87157BDC1E27F6BF FOREIGN KEY (question_id) REFERENCES admin_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quiz_table_question DROP CONSTRAINT FK_4CD0FEBE1E27F6BF');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question DROP CONSTRAINT FK_70763F19E4F90E7E');
        $this->addSql('ALTER TABLE result_of_quiz DROP CONSTRAINT FK_6313F045A76ED395');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question DROP CONSTRAINT FK_70763F19833DBFB6');
        $this->addSql('ALTER TABLE admin_answer DROP CONSTRAINT FK_87157BDC1E27F6BF');
        $this->addSql('ALTER TABLE quiz_table_question DROP CONSTRAINT FK_4CD0FEBE3A3BD119');
        $this->addSql('ALTER TABLE result_of_quiz DROP CONSTRAINT FK_6313F045853CD175');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin_quiz_table_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_table_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE result_of_quiz_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE admin_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE article_id_seq CASCADE');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE admin_quiz_table');
        $this->addSql('DROP TABLE admin_quiz_table_admin_question');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE admin_question');
        $this->addSql('DROP TABLE quiz_table');
        $this->addSql('DROP TABLE quiz_table_question');
        $this->addSql('DROP TABLE result_of_quiz');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE admin_answer');
        $this->addSql('DROP TABLE article');
    }
}
