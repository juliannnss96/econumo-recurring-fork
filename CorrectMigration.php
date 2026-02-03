<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260203035450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create recurring_transactions table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE recurring_transactions (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , type VARCHAR(255) NOT NULL --(DC2Type:transaction_type)
        , amount VARCHAR(255) NOT NULL --(DC2Type:decimal_number_type)
        , description VARCHAR(255) NOT NULL
        , interval VARCHAR(255) NOT NULL --(DC2Type:recurring_interval_type)
        , next_run_date DATETIME NOT NULL
        , is_active BOOLEAN DEFAULT 1 NOT NULL
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , account_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , category_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , payee_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , tag_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , PRIMARY KEY(id)
        , CONSTRAINT FK_RECURRING_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        , CONSTRAINT FK_RECURRING_ACCOUNT FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE
        , CONSTRAINT FK_RECURRING_CATEGORY FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
        , CONSTRAINT FK_RECURRING_PAYEE FOREIGN KEY (payee_id) REFERENCES payees (id) ON DELETE SET NULL
        , CONSTRAINT FK_RECURRING_TAG FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE SET NULL)');
        
        $this->addSql('CREATE INDEX next_run_date_idx_recurring_transactions ON recurring_transactions (next_run_date)');
        $this->addSql('CREATE INDEX is_active_idx_recurring_transactions ON recurring_transactions (is_active)');
        $this->addSql('CREATE INDEX IDX_RECURRING_USER ON recurring_transactions (user_id)');
        $this->addSql('CREATE INDEX IDX_RECURRING_ACCOUNT ON recurring_transactions (account_id)');
        $this->addSql('CREATE INDEX IDX_RECURRING_CATEGORY ON recurring_transactions (category_id)');
        $this->addSql('CREATE INDEX IDX_RECURRING_PAYEE ON recurring_transactions (payee_id)');
        $this->addSql('CREATE INDEX IDX_RECURRING_TAG ON recurring_transactions (tag_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE recurring_transactions');
    }
}
