# PLAN_01_DATABASE.md

**Application Name:** MyWallets
**Database Engine:** MySQL or MariaDB
**Framework:** Laravel
**Scope:** Database migrations, triggers, and Eloquent models

---

## 0. General Conventions

* All primary keys use `BIGINT UNSIGNED AUTO_INCREMENT`.
* All foreign keys use `BIGINT UNSIGNED`.
* All `VARCHAR` columns must use length **255**.
* Do not use `ENUM`. Use `VARCHAR(255)` for flexible domain values.
* Monetary values use `DECIMAL(65,4)` to allow very large amounts with 4 decimal precision.
* Always define proper indexes for foreign keys and frequently queried columns.
* Use `softDeletes()` where specified.
* Use `timestamps()` for `created_at` and `updated_at`.
* Triggers are used only for **denormalized snapshot fields**, not for business validation.
* Models are generated using `php artisan code:models` and then manually reviewed.

---

## 1. Users Table

### Notes

* Use Laravel default users table.
* Do **not** create a migration or model.
* Only modify the existing `User` model if relationships are needed.

---

## 2. Accounts Table

### Migration Command

```bash
php artisan make:migration create_accounts_table
```

### Columns

| Column     | Type            | Description                                                    |
| ---------- | --------------- | -------------------------------------------------------------- |
| id         | BIGINT UNSIGNED | Primary key                                                    |
| owner_id   | BIGINT UNSIGNED | Foreign key to users.id. Defines the main owner of the account |
| name       | VARCHAR(255)    | Account name visible to users                                  |
| created_at | TIMESTAMP       | Record creation time                                           |
| updated_at | TIMESTAMP       | Record last update time                                        |
| deleted_at | TIMESTAMP NULL  | Soft delete timestamp                                          |

### Indexes

* Primary key on `id`
* Index on `owner_id`
* Unique composite index on `(owner_id, name)`

### Model Command

```bash
php artisan code:models --table=accounts
```

---

## 3. Account Members Table

### Migration Command

```bash
php artisan make:migration create_account_members_table
```

### Columns

| Column       | Type            | Description                                  |
| ------------ | --------------- | -------------------------------------------- |
| id           | BIGINT UNSIGNED | Primary key                                  |
| account_id   | BIGINT UNSIGNED | Foreign key to accounts.id                   |
| user_id      | BIGINT UNSIGNED | Foreign key to users.id                      |
| role         | VARCHAR(255)    | Member role such as owner, member, or viewer |
| invited_at   | TIMESTAMP NULL  | Time when invitation was sent                |
| confirmed_at | TIMESTAMP NULL  | Time when invitation was accepted            |
| created_at   | TIMESTAMP       | Record creation time                         |
| updated_at   | TIMESTAMP       | Record last update time                      |
| deleted_at   | TIMESTAMP NULL  | Soft delete timestamp                        |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `user_id`
* Unique composite index on `(account_id, user_id)`

### Model Command

```bash
php artisan code:models --table=account_members
```

---

## 4. Categories Table

### Migration Command

```bash
php artisan make:migration create_categories_table
```

### Columns

| Column     | Type              | Description                                 |
| ---------- | ----------------- | ------------------------------------------- |
| id         | BIGINT UNSIGNED   | Primary key                                 |
| account_id | BIGINT UNSIGNED   | Foreign key to accounts.id                  |
| name       | VARCHAR(255)      | Category display name                       |
| type       | VARCHAR(255)      | Category type such as income or expense     |
| slug       | VARCHAR(255)      | URL friendly identifier, unique per account |
| icon       | VARCHAR(255) NULL | Icon reference for UI                       |
| color      | VARCHAR(255) NULL | Color code for UI                           |
| `order`    | BIGINT            | Sorting order within account                |
| status     | VARCHAR(255)      | Status such as active or inactive           |
| created_at | TIMESTAMP         | Record creation time                        |
| updated_at | TIMESTAMP         | Record last update time                     |
| deleted_at | TIMESTAMP NULL    | Soft delete timestamp                       |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `slug`
* Unique composite index on `(account_id, slug)`

### Model Command

```bash
php artisan code:models --table=categories
```

---

## 5. Budgets Table

### Migration Command

```bash
php artisan make:migration create_budgets_table
```

### Columns

| Column       | Type            | Description                                 |
| ------------ | --------------- | ------------------------------------------- |
| id           | BIGINT UNSIGNED | Primary key                                 |
| account_id   | BIGINT UNSIGNED | Foreign key to accounts.id                  |
| category_id  | BIGINT UNSIGNED | Foreign key to categories.id                |
| amount       | DECIMAL(65,4)   | Budget limit for the period                 |
| period_type  | VARCHAR(255)    | Period type such as weekly, monthly, yearly |
| period_start | DATE            | Start date of budget period                 |
| period_end   | DATE            | End date of budget period                   |
| created_at   | TIMESTAMP       | Record creation time                        |
| updated_at   | TIMESTAMP       | Record last update time                     |
| deleted_at   | TIMESTAMP NULL  | Soft delete timestamp                       |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `category_id`
* Composite index on `(account_id, category_id)`

### Model Command

```bash
php artisan code:models --table=budgets
```

---

## 6. Wallets Table

### Migration Command

```bash
php artisan make:migration create_wallets_table
```

### Columns

| Column         | Type              | Description                                 |
| -------------- | ----------------- | ------------------------------------------- |
| id             | BIGINT UNSIGNED   | Primary key                                 |
| account_id     | BIGINT UNSIGNED   | Foreign key to accounts.id                  |
| name           | VARCHAR(255)      | Wallet display name                         |
| slug           | VARCHAR(255)      | URL friendly identifier, unique per account |
| currency_code  | VARCHAR(255)      | ISO currency code such as IDR or USD        |
| icon           | VARCHAR(255) NULL | Icon reference for UI                       |
| color          | VARCHAR(255) NULL | Color code for UI                           |
| decimal_places | UNSIGNED SMALLINT | Number of decimal places used by currency   |
| balance        | DECIMAL(65,4)     | Current wallet balance                      |
| created_at     | TIMESTAMP         | Record creation time                        |
| updated_at     | TIMESTAMP         | Record last update time                     |
| deleted_at     | TIMESTAMP NULL    | Soft delete timestamp                       |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Unique composite index on `(account_id, slug)`

### Model Command

```bash
php artisan code:models --table=wallets
```

---

## 7. Transactions Table

### Migration Command

```bash
php artisan make:migration create_transactions_table
```

### Columns

| Column                | Type                 | Description                                      |
| --------------------- | -------------------- | ------------------------------------------------ |
| id                    | BIGINT UNSIGNED      | Primary key                                      |
| happened_at           | DATETIME             | Actual transaction time                          |
| account_id            | BIGINT UNSIGNED      | Foreign key to accounts.id                       |
| category_id           | BIGINT UNSIGNED NULL | Foreign key to categories.id                     |
| wallet_id             | BIGINT UNSIGNED      | Foreign key to wallets.id                        |
| category_name         | VARCHAR(255)         | Snapshot of category name, filled by trigger     |
| wallet_name           | VARCHAR(255)         | Snapshot of wallet name, filled by trigger       |
| wallet_balance_before | DECIMAL(65,4)        | Wallet balance before transaction                |
| amount                | DECIMAL(65,4)        | Absolute transaction amount                      |
| wallet_balance_after  | DECIMAL(65,4)        | Wallet balance after transaction                 |
| direction_amount      | DECIMAL(65,4)        | Signed amount, negative for expense              |
| type                  | VARCHAR(255)         | Transaction type such as transaction or transfer |
| description           | VARCHAR(255) NULL    | Optional description                             |
| created_at            | TIMESTAMP            | Record creation time                             |
| updated_at            | TIMESTAMP            | Record last update time                          |
| deleted_at            | TIMESTAMP NULL       | Soft delete timestamp                            |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `wallet_id`
* Index on `category_id`
* Index on `happened_at`

### Model Command

```bash
php artisan code:models --table=transactions
```

---

## 8. Transactions Snapshot Trigger

### Purpose

Populate snapshot fields to preserve historical data integrity.

### Migration Command

```bash
php artisan make:migration create_transactions_snapshot_trigger
```

### Trigger Responsibilities

* Fill `category_name` from categories.name
* Fill `wallet_name` from wallets.name
* Fill `wallet_balance_before` from wallets.balance before update
* Fill `wallet_balance_after` after balance calculation
* Fill `direction_amount` based on category type

---

## 9. Transfers Table

### Migration Command

```bash
php artisan make:migration create_transfers_table
```

### Columns

| Column                  | Type                 | Description                    |
| ----------------------- | -------------------- | ------------------------------ |
| id                      | BIGINT UNSIGNED      | Primary key                    |
| happened_at             | DATETIME             | Transfer execution time        |
| account_id              | BIGINT UNSIGNED      | Foreign key to accounts.id     |
| from_wallet_id          | BIGINT UNSIGNED      | Source wallet                  |
| to_wallet_id            | BIGINT UNSIGNED      | Destination wallet             |
| withdraw_transaction_id | BIGINT UNSIGNED      | Related withdrawal transaction |
| deposit_transaction_id  | BIGINT UNSIGNED      | Related deposit transaction    |
| fee_transaction_id      | BIGINT UNSIGNED NULL | Optional fee transaction       |
| amount                  | DECIMAL(65,4)        | Transfer amount                |
| fee                     | DECIMAL(65,4)        | Transfer fee                   |
| description             | VARCHAR(255) NULL    | Optional description           |
| created_at              | TIMESTAMP            | Record creation time           |
| updated_at              | TIMESTAMP            | Record last update time        |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `from_wallet_id`
* Index on `to_wallet_id`

### Model Command

```bash
php artisan code:models --table=transfers
```

---

## 10. Debts Table

### Migration Command

```bash
php artisan make:migration create_debts_table
```

### Columns

| Column            | Type              | Description                |
| ----------------- | ----------------- | -------------------------- |
| id                | BIGINT UNSIGNED   | Primary key                |
| account_id        | BIGINT UNSIGNED   | Foreign key to accounts.id |
| wallet_id         | BIGINT UNSIGNED   | Wallet used for settlement |
| counterparty_name | VARCHAR(255)      | Person or entity involved  |
| type              | VARCHAR(255)      | payable or receivable      |
| total_amount      | DECIMAL(65,4)     | Total debt amount          |
| remaining_amount  | DECIMAL(65,4)     | Remaining unpaid amount    |
| due_date          | DATE NULL         | Due date                   |
| description       | VARCHAR(255) NULL | Additional notes           |
| created_at        | TIMESTAMP         | Record creation time       |
| updated_at        | TIMESTAMP         | Record last update time    |
| deleted_at        | TIMESTAMP NULL    | Soft delete timestamp      |

### Indexes

* Primary key on `id`
* Index on `account_id`
* Index on `wallet_id`
* Index on `type`

### Model Command

```bash
php artisan code:models --table=debts
```

---

## 11. Debt Payments Table

### Migration Command

```bash
php artisan make:migration create_debt_payments_table
```

### Columns

| Column         | Type            | Description                    |
| -------------- | --------------- | ------------------------------ |
| id             | BIGINT UNSIGNED | Primary key                    |
| debt_id        | BIGINT UNSIGNED | Foreign key to debts.id        |
| transaction_id | BIGINT UNSIGNED | Foreign key to transactions.id |
| amount         | DECIMAL(65,4)   | Payment amount                 |
| paid_at        | DATETIME        | Payment date                   |
| created_at     | TIMESTAMP       | Record creation time           |
| updated_at     | TIMESTAMP       | Record last update time        |

### Indexes

* Primary key on `id`
* Index on `debt_id`
* Index on `transaction_id`

### Model Command

```bash
php artisan code:models --table=debt_payments
```

---

## 12. Attachments Table

### Migration Command

```bash
php artisan make:migration create_attachments_table
```

### Columns

| Column          | Type            | Description                  |
| --------------- | --------------- | ---------------------------- |
| id              | BIGINT UNSIGNED | Primary key                  |
| attachable_type | VARCHAR(255)    | Polymorphic model class name |
| attachable_id   | BIGINT UNSIGNED | Polymorphic model ID         |
| file_path       | VARCHAR(255)    | Storage path                 |
| mime_type       | VARCHAR(255)    | File MIME type               |
| size            | BIGINT UNSIGNED | File size in bytes           |
| created_at      | TIMESTAMP       | Record creation time         |
| updated_at      | TIMESTAMP       | Record last update time      |

### Indexes

* Primary key on `id`
* Composite index on `(attachable_type, attachable_id)`

### Model Command

```bash
php artisan code:models --table=attachments
```

---

**End of PLAN_01_DATABASE.md**
