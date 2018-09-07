<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904115821 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, title VARCHAR(191) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_87157BDC1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(191) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_quiz_table (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, current_question INT NOT NULL, UNIQUE INDEX UNIQ_C26450432B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_quiz_table_admin_question (admin_quiz_table_id INT NOT NULL, admin_question_id INT NOT NULL, INDEX IDX_70763F19E4F90E7E (admin_quiz_table_id), INDEX IDX_70763F19833DBFB6 (admin_question_id), PRIMARY KEY(admin_quiz_table_id, admin_question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, title VARCHAR(191) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(191) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_table (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(191) NOT NULL, is_active TINYINT(1) NOT NULL, description VARCHAR(191) NOT NULL, date_of_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_A5AF6C62B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_table_question (quiz_table_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_4CD0FEBE3A3BD119 (quiz_table_id), INDEX IDX_4CD0FEBE1E27F6BF (question_id), PRIMARY KEY(quiz_table_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result_of_quiz (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, user_id INT NOT NULL, score INT NOT NULL, time INT NOT NULL, current_question INT NOT NULL, is_over TINYINT(1) NOT NULL, INDEX IDX_6313F045853CD175 (quiz_id), INDEX IDX_6313F045A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(191) NOT NULL, password VARCHAR(64) NOT NULL, email VARCHAR(191) NOT NULL, is_active TINYINT(1) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_answer ADD CONSTRAINT FK_87157BDC1E27F6BF FOREIGN KEY (question_id) REFERENCES admin_question (id)');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question ADD CONSTRAINT FK_70763F19E4F90E7E FOREIGN KEY (admin_quiz_table_id) REFERENCES admin_quiz_table (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question ADD CONSTRAINT FK_70763F19833DBFB6 FOREIGN KEY (admin_question_id) REFERENCES admin_question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz_table_question ADD CONSTRAINT FK_4CD0FEBE3A3BD119 FOREIGN KEY (quiz_table_id) REFERENCES quiz_table (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_table_question ADD CONSTRAINT FK_4CD0FEBE1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result_of_quiz ADD CONSTRAINT FK_6313F045853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz_table (id)');
        $this->addSql('ALTER TABLE result_of_quiz ADD CONSTRAINT FK_6313F045A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_answer DROP FOREIGN KEY FK_87157BDC1E27F6BF');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question DROP FOREIGN KEY FK_70763F19833DBFB6');
        $this->addSql('ALTER TABLE admin_quiz_table_admin_question DROP FOREIGN KEY FK_70763F19E4F90E7E');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE quiz_table_question DROP FOREIGN KEY FK_4CD0FEBE1E27F6BF');
        $this->addSql('ALTER TABLE quiz_table_question DROP FOREIGN KEY FK_4CD0FEBE3A3BD119');
        $this->addSql('ALTER TABLE result_of_quiz DROP FOREIGN KEY FK_6313F045853CD175');
        $this->addSql('ALTER TABLE result_of_quiz DROP FOREIGN KEY FK_6313F045A76ED395');
        $this->addSql('DROP TABLE admin_answer');
        $this->addSql('DROP TABLE admin_question');
        $this->addSql('DROP TABLE admin_quiz_table');
        $this->addSql('DROP TABLE admin_quiz_table_admin_question');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz_table');
        $this->addSql('DROP TABLE quiz_table_question');
        $this->addSql('DROP TABLE result_of_quiz');
        $this->addSql('DROP TABLE user');
    }
}
