# PLAN_02_SEEDER.md

- **Application Name:** MyWallets
- **Scope:** Database Seeders
- **Database:** MySQL or MariaDB
- **Framework:** Laravel

---

## 1. General Rules

* This seeder plan must strictly follow the database structure, relationships, and constraints defined in PLAN_01_DATABASE.md.
* Before writing seeders, scan all generated Eloquent models to understand fillable fields, casts, relationships, and soft deletes.
* Do not use Laravel Factory.
* Insert data using `DB::table()->insert()`. Don't forget timestamps columns.
* Seeder execution order must follow the defined dependency chain.
* All enum like fields must use PHP Enums, not raw strings.
* Passwords must be hashed using Laravel Hash.
* Monetary values must use DECIMAL(65,4).
* Seeder files must be placed in `database/seeders`.

---

## 2. PHP Enum Definitions

Create the following PHP enums before implementing seeders.
Location: `app/Enums`

### AccountMemberRoleEnum

* owner
* member
* viewer

### CategoryTypeEnum

* income
* expense

### CategoryStatusEnum

* active
* inactive

### BudgetPeriodTypeEnum

* weekly
* monthly
* yearly

### TransactionTypeEnum

* transaction
* transfer

### DebtTypeEnum

* payable
* receivable

All seeders must reference enums instead of hard coded string values.

---

## 3. Seeder Execution Order

1. UserSeeder
2. AccountSeeder
3. AccountMemberSeeder
4. CategorySeeder
5. WalletSeeder
6. BudgetSeeder
7. TransactionSeeder
8. DebtSeeder

---

## 4. UserSeeder

### Purpose

Seed the default application user.

### Default User Data

* Name: Rafy Aulia Akbar
* Email: [rafy.social@gmail.com](mailto:rafy.social@gmail.com)
* Password: password

### Rules

* Use Laravel default User model.
* Hash password using `Hash::make`.
* Ensure email uniqueness.
* No additional profile fields are required.

---

## 5. AccountSeeder

### Purpose

Create the initial account container.

### Default Account

* Name: Test
* Owner: Rafy Aulia Akbar

### Rules

* `owner_id` references the default user.
* This account will be used by all subsequent seeders.

---

## 6. AccountMemberSeeder

### Purpose

Explicitly define account membership.

### Default Record

* account_id: Test account
* user_id: default user
* role: AccountMemberRoleEnum::owner
* invited_at: null
* confirmed_at: current timestamp

### Rules

* One record only.
* Ensures permission logic consistency.

---

## 7. CategorySeeder

### Purpose

Seed default income and expense categories.

### Income Categories (Bahasa Indonesia, ordered)

| Order | Name                 | Slug                 |
| ----- | -------------------- | -------------------- |
| 1     | Saldo Awal           | saldo-awal           |
| 2     | Transfer             | transfer-income      |
| 3     | Gaji                 | gaji                 |
| 4     | Usaha                | usaha                |
| 5     | Penghasilan Tambahan | penghasilan-tambahan |
| 6     | Investasi            | investasi-income     |
| 7     | Bunga/Riba           | bunga-riba-income    |

### Expense Categories (Bahasa Indonesia, ordered)

| Order | Name         | Slug               |
| ----- | ------------ | ------------------ |
| 1     | Transfer     | transfer-expense   |
| 2     | Makanan      | makanan            |
| 3     | Transportasi | transportasi       |
| 4     | Belanja      | belanja            |
| 5     | Hiburan      | hiburan            |
| 6     | Kesehatan    | kesehatan          |
| 7     | Pendidikan   | pendidikan         |
| 8     | Hadiah       | hadiah             |
| 9     | Investasi    | investasi-expense  |
| 10    | Bunga/Riba   | bunga-riba-expense |
| 11    | Zakat        | zakat              |

### Rules

* All categories belong to the Test account.
* Use CategoryTypeEnum for type.
* Use CategoryStatusEnum::active.
* Slug must be unique per account.
* Order must be sequential.
* icon and color can be null.

---

## 8. WalletSeeder

### Purpose

Seed commonly used wallets.

### Default Wallets

| Name         | Slug         | Type       |
| ------------ | ------------ | ---------- |
| Bank UOB     | bank-uob     | Bank       |
| Bank Jago    | bank-jago    | Bank       |
| Bank JATIM   | bank-jatim   | Bank       |
| Bank SeaBank | bank-seabank | Bank       |
| Bibit Goals  | bibit-goals  | Investment |
| GoPay        | gopay        | E-Wallet   |
| ShopeePay    | shopeepay    | E-Wallet   |
| Dompet       | cash         | Cash       |

### Rules

* All wallets belong to the Test account.
* currency_code: IDR
* decimal_places: 2
* initial balance: 0.0000
* icon and color may be null.
* balance will be adjusted by transactions later.

---

## 9. BudgetSeeder

### Purpose

Seed realistic monthly budgets based on common Indonesian expenses.

### Monthly Budgets Example

| Category     | Amount (IDR) |
| ------------ | ------------ |
| Makanan      | 2000000      |
| Transportasi | 750000       |
| Belanja      | 1000000      |
| Hiburan      | 500000       |
| Kesehatan    | 500000       |
| Pendidikan   | 1000000      |
| Hadiah       | 300000       |
| Zakat        | 250000       |

### Rules

* period_type: BudgetPeriodTypeEnum::monthly
* period_start: first day of current month
* period_end: last day of current month
* amount uses DECIMAL(65,4)

---

## 10. TransactionSeeder

### Purpose

Create initial wallet activity.

### Initial Balance Transaction

* Category: Saldo Awal
* Wallet: Dompet
* Amount: 5000000.0000
* Type: TransactionTypeEnum::transaction
* Description: Initial wallet balance

### Example Expense Transactions

| Category     | Amount | Description            |
| ------------ | ------ | ---------------------- |
| Makanan      | 45000  | Lunch                  |
| Transportasi | 25000  | Online ride            |
| Hiburan      | 120000 | Streaming subscription |
| Belanja      | 200000 | Daily shopping         |

### Rules

* happened_at should be current datetime minus random minutes.
* Wallet balance snapshot fields are handled by triggers.
* direction_amount must not be manually calculated.

---

## 11. DebtSeeder

### Purpose

Seed example debt data.

### Example Debt

* Counterparty: Friend
* Type: DebtTypeEnum::payable
* Wallet: Dompet
* Total amount: 1000000
* Remaining amount: 1000000
* Due date: current date plus 30 days
* Description: Personal loan

### Rules

* No debt payments are created at this stage.
* remaining_amount equals total_amount initially.

---

## 12. Seeder Registration

Ensure all seeders are registered in `DatabaseSeeder.php` and executed in the correct order.

Seeder execution command:

```bash
php artisan db:seed
```

---

**End of PLAN_02_SEEDER.md**
