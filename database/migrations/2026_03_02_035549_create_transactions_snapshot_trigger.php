<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS transactions_snapshot_before_insert;

            CREATE TRIGGER transactions_snapshot_before_insert
            BEFORE INSERT ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE cat_name VARCHAR(255);
                DECLARE wal_name VARCHAR(255);
                DECLARE wal_balance DECIMAL(65,4);
                DECLARE cat_type VARCHAR(255);

                IF NEW.category_id IS NOT NULL THEN
                    SELECT name, type INTO cat_name, cat_type
                    FROM categories
                    WHERE id = NEW.category_id;
                    SET NEW.category_name = cat_name;
                ELSE
                    SET NEW.category_name = NULL;
                END IF;

                SELECT name, balance INTO wal_name, wal_balance
                FROM wallets
                WHERE id = NEW.wallet_id;
                SET NEW.wallet_name = wal_name;
                SET NEW.wallet_balance_before = wal_balance;

                IF cat_type = "expense" THEN
                    SET NEW.direction_amount = -NEW.amount;
                ELSE
                    SET NEW.direction_amount = NEW.amount;
                END IF;

                SET NEW.wallet_balance_after = wal_balance + NEW.direction_amount;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS transactions_snapshot_before_insert');
    }
};
