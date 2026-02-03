<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to create recurring_transactions table
 */
final class Version20260203051420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create recurring_transactions table';
    }

    public function up(Schema $schema) : void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();

        if ($platform === 'sqlite') {
            $this->addSql('CREATE TABLE recurring_transactions (id CHAR(36) NOT NULL --(DC2Type:uuid)
            , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
            , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
            , category_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
            , payee_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
            , tag_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
            , description VARCHAR(255) NOT NULL, next_run_date DATETIME NOT NULL, is_active BOOLEAN DEFAULT \'1\' NOT NULL, updated_at DATETIME NOT NULL, type SMALLINT NOT NULL, amount NUMERIC(19, 8) NOT NULL, interval VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , PRIMARY KEY(id), CONSTRAINT FK_2468994CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2468994C9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2468994C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2468994CCB4B68F FOREIGN KEY (payee_id) REFERENCES payees (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2468994CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
            $this->addSql('CREATE INDEX is_active_idx_recurring_transactions ON recurring_transactions (is_active)');
            $this->addSql('CREATE INDEX next_run_date_idx_recurring_transactions ON recurring_transactions (next_run_date)');
            $this->addSql('CREATE INDEX IDX_2468994CA76ED395 ON recurring_transactions (user_id)');
            $this->addSql('CREATE INDEX IDX_2468994C9B6B5FBA ON recurring_transactions (account_id)');
            $this->addSql('CREATE INDEX IDX_2468994C12469DE2 ON recurring_transactions (category_id)');
            $this->addSql('CREATE INDEX IDX_2468994CCB4B68F ON recurring_transactions (payee_id)');
            $this->addSql('CREATE INDEX IDX_2468994CBAD26311 ON recurring_transactions (tag_id)');
        } else {
            $this->addSql('CREATE TABLE recurring_transactions (id UUID NOT NULL, user_id UUID NOT NULL, account_id UUID NOT NULL, category_id UUID DEFAULT NULL, payee_id UUID DEFAULT NULL, tag_id UUID DEFAULT NULL, description VARCHAR(255) NOT NULL, next_run_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN DEFAULT \'true\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type SMALLINT NOT NULL, amount NUMERIC(19, 8) NOT NULL, interval VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX is_active_idx_recurring_transactions ON recurring_transactions (is_active)');
            $this->addSql('CREATE INDEX next_run_date_idx_recurring_transactions ON recurring_transactions (next_run_date)');
            $this->addSql('CREATE INDEX IDX_2468994CA76ED395 ON recurring_transactions (user_id)');
            $this->addSql('CREATE INDEX IDX_2468994C9B6B5FBA ON recurring_transactions (account_id)');
            $this->addSql('CREATE INDEX IDX_2468994C12469DE2 ON recurring_transactions (category_id)');
            $this->addSql('CREATE INDEX IDX_2468994CCB4B68F ON recurring_transactions (payee_id)');
            $this->addSql('CREATE INDEX IDX_2468994CBAD26311 ON recurring_transactions (tag_id)');
            $this->addSql('ALTER TABLE recurring_transactions ADD CONSTRAINT FK_2468994CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE recurring_transactions ADD CONSTRAINT FK_2468994C9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE recurring_transactions ADD CONSTRAINT FK_2468994C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE recurring_transactions ADD CONSTRAINT FK_2468994CCB4B68F FOREIGN KEY (payee_id) REFERENCES payees (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE recurring_transactions ADD CONSTRAINT FK_2468994CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        }
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE recurring_transactions');
    }
}
