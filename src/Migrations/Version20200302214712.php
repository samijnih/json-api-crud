<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302214712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the user table.';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
CREATE TABLE public."user" (
    id UUID PRIMARY KEY,
    first_name VARCHAR NOT NULL,
    last_name VARCHAR NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NULL
)
SQL;

        $this->addSql($sql);
        $this->addSql('COMMENT ON COLUMN public."user".created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN public."user".updated_at IS \'(DC2Type:datetimetz)\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE public.user');
    }
}
