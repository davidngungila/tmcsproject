<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash in Hand', 'type' => 'Asset'],
            ['code' => '1100', 'name' => 'Bank Account', 'type' => 'Asset'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'Asset'],
            
            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'Liability'],
            ['code' => '2100', 'name' => 'Accrued Expenses', 'type' => 'Liability'],
            
            // Equity
            ['code' => '3000', 'name' => 'Retained Earnings', 'type' => 'Equity'],
            ['code' => '3100', 'name' => 'Church Fund', 'type' => 'Equity'],
            
            // Revenue
            ['code' => '4000', 'name' => 'Tithing Income', 'type' => 'Revenue'],
            ['code' => '4100', 'name' => 'Offering Income', 'type' => 'Revenue'],
            ['code' => '4200', 'name' => 'Special Contribution Income', 'type' => 'Revenue'],
            ['code' => '4300', 'name' => 'Harvest Income', 'type' => 'Revenue'],
            ['code' => '4900', 'name' => 'Other Income', 'type' => 'Revenue'],
            
            // Expenses
            ['code' => '5000', 'name' => 'Salaries & Wages', 'type' => 'Expense'],
            ['code' => '5100', 'name' => 'Utilities (Water/Electricity)', 'type' => 'Expense'],
            ['code' => '5200', 'name' => 'Church Maintenance', 'type' => 'Expense'],
            ['code' => '5300', 'name' => 'Charity & Outreach', 'type' => 'Expense'],
            ['code' => '5400', 'name' => 'Administrative Expenses', 'type' => 'Expense'],
            ['code' => '5900', 'name' => 'Other Expenses', 'type' => 'Expense'],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(['code' => $account['code']], $account);
        }
    }
}
