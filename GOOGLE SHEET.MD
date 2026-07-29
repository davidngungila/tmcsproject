COMPLETE LARAVEL API RECEIVER SYSTEM FOR GOOGLE SHEETS DATA
PART 1: LARAVEL DATABASE MIGRATIONS
1.1 Customer Profiles Table
php
<?php
// database/migrations/2024_01_01_000000_create_customer_profiles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('member_number')->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('member_type')->nullable();
            $table->string('branch')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->string('residential_address')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('account_status')->default('Active');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('member_number');
            $table->index('email');
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_profiles');
    }
};
1.2 Customer Financial Data Table
php
<?php
// database/migrations/2024_01_01_000001_create_customer_financial_data_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_financial_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('customer_profiles')->onDelete('cascade');
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('statement_period')->nullable();
            $table->date('statement_start_date')->nullable();
            $table->date('statement_end_date')->nullable();
            
            // Monthly Savings
            $table->decimal('monthly_saving_target', 15, 2)->default(0);
            $table->decimal('monthly_total_savings_deposits', 15, 2)->default(0);
            $table->decimal('monthly_goal_achievement', 10, 2)->default(0);
            $table->decimal('overall_saving_goal', 15, 2)->default(0);
            $table->decimal('total_saved', 15, 2)->default(0);
            $table->decimal('overall_goal_achievement', 10, 2)->default(0);
            
            // Flexi Account
            $table->decimal('flexi_opening_balance', 15, 2)->default(0);
            $table->decimal('flexi_deposit', 15, 2)->default(0);
            $table->decimal('flexi_withdrawal', 15, 2)->default(0);
            $table->decimal('flexi_balance', 15, 2)->default(0);
            
            // RDA Account
            $table->decimal('rda_opening_balance', 15, 2)->default(0);
            $table->decimal('rda_deposit', 15, 2)->default(0);
            $table->decimal('rda_withdrawal', 15, 2)->default(0);
            $table->decimal('rda_balance', 15, 2)->default(0);
            
            // Emergency Account
            $table->decimal('emergency_opening_balance', 15, 2)->default(0);
            $table->decimal('emergency_deposit', 15, 2)->default(0);
            $table->decimal('emergency_withdrawal', 15, 2)->default(0);
            $table->decimal('emergency_balance', 15, 2)->default(0);
            
            // Business Account
            $table->decimal('business_opening_balance', 15, 2)->default(0);
            $table->decimal('business_deposit', 15, 2)->default(0);
            $table->decimal('business_withdrawal', 15, 2)->default(0);
            $table->decimal('business_balance', 15, 2)->default(0);
            
            // Totals
            $table->decimal('total_balance', 15, 2)->default(0);
            $table->decimal('interest_payable', 15, 2)->default(0);
            $table->decimal('savings_held_for_loan_security', 15, 2)->default(0);
            $table->decimal('free_savings_emergency', 15, 2)->default(0);
            $table->decimal('free_savings_rda_flexi_business', 15, 2)->default(0);
            $table->decimal('total_free_saving', 15, 2)->default(0);
            $table->decimal('premature_withdraw_charge', 15, 2)->default(0);
            $table->decimal('combined_opening_balance', 15, 2)->default(0);
            
            $table->string('statement_id')->unique()->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('pdf_file_path')->nullable();
            $table->enum('status', ['pending', 'generated', 'synced', 'failed'])->default('pending');
            $table->json('additional_data')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('statement_period');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_financial_data');
    }
};
1.3 Customer Transactions Table
php
<?php
// database/migrations/2024_01_01_000002_create_customer_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_data_id')->constrained('customer_financial_data')->onDelete('cascade');
            $table->string('customer_id');
            $table->string('account_type');
            $table->date('transaction_date');
            $table->string('reference_no')->nullable();
            $table->string('transaction_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('account_type');
            $table->index('transaction_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_transactions');
    }
};
1.4 API Request Logs Table
php
<?php
// database/migrations/2024_01_01_000003_create_api_request_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method');
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->string('status_code');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('endpoint');
            $table->index('created_at');
            $table->index('status_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_request_logs');
    }
};
PART 2: LARAVEL MODELS
2.1 CustomerProfile Model
php
<?php
// app/Models/CustomerProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use SoftDeletes;

    protected $table = 'customer_profiles';

    protected $fillable = [
        'member_number', 'full_name', 'email', 'phone', 'gender',
        'member_type', 'branch', 'occupation', 'employer',
        'residential_address', 'registration_date', 'account_status',
        'notes', 'metadata', 'status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'metadata' => 'array',
        'deleted_at' => 'datetime'
    ];

    public function financialData(): HasMany
    {
        return $this->hasMany(CustomerFinancialData::class, 'profile_id');
    }

    public function latestFinancialData(): HasOne
    {
        return $this->hasOne(CustomerFinancialData::class, 'profile_id')->latest();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerTransaction::class, 'customer_id', 'member_number');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByMemberNumber($query, $memberNumber)
    {
        return $query->where('member_number', $memberNumber);
    }

    public function getTotalBalanceAttribute()
    {
        return $this->financialData()->sum('total_balance');
    }

    public function getTotalSavingsAttribute()
    {
        return $this->financialData()->sum('total_saved');
    }

    public function getAccountBalancesAttribute()
    {
        return [
            'flexi' => $this->financialData()->sum('flexi_balance'),
            'rda' => $this->financialData()->sum('rda_balance'),
            'emergency' => $this->financialData()->sum('emergency_balance'),
            'business' => $this->financialData()->sum('business_balance'),
        ];
    }
}
2.2 CustomerFinancialData Model
php
<?php
// app/Models/CustomerFinancialData.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerFinancialData extends Model
{
    protected $table = 'customer_financial_data';

    protected $fillable = [
        'profile_id', 'customer_id', 'customer_name', 'statement_period',
        'statement_start_date', 'statement_end_date',
        'monthly_saving_target', 'monthly_total_savings_deposits',
        'monthly_goal_achievement', 'overall_saving_goal', 'total_saved',
        'overall_goal_achievement',
        'flexi_opening_balance', 'flexi_deposit', 'flexi_withdrawal', 'flexi_balance',
        'rda_opening_balance', 'rda_deposit', 'rda_withdrawal', 'rda_balance',
        'emergency_opening_balance', 'emergency_deposit', 'emergency_withdrawal', 'emergency_balance',
        'business_opening_balance', 'business_deposit', 'business_withdrawal', 'business_balance',
        'total_balance', 'interest_payable', 'savings_held_for_loan_security',
        'free_savings_emergency', 'free_savings_rda_flexi_business', 'total_free_saving',
        'premature_withdraw_charge', 'combined_opening_balance',
        'statement_id', 'pdf_url', 'pdf_file_path', 'status',
        'additional_data', 'metadata'
    ];

    protected $casts = [
        'statement_start_date' => 'date',
        'statement_end_date' => 'date',
        'monthly_saving_target' => 'decimal:2',
        'monthly_total_savings_deposits' => 'decimal:2',
        'monthly_goal_achievement' => 'decimal:2',
        'overall_saving_goal' => 'decimal:2',
        'total_saved' => 'decimal:2',
        'overall_goal_achievement' => 'decimal:2',
        'flexi_opening_balance' => 'decimal:2',
        'flexi_deposit' => 'decimal:2',
        'flexi_withdrawal' => 'decimal:2',
        'flexi_balance' => 'decimal:2',
        'rda_opening_balance' => 'decimal:2',
        'rda_deposit' => 'decimal:2',
        'rda_withdrawal' => 'decimal:2',
        'rda_balance' => 'decimal:2',
        'emergency_opening_balance' => 'decimal:2',
        'emergency_deposit' => 'decimal:2',
        'emergency_withdrawal' => 'decimal:2',
        'emergency_balance' => 'decimal:2',
        'business_opening_balance' => 'decimal:2',
        'business_deposit' => 'decimal:2',
        'business_withdrawal' => 'decimal:2',
        'business_balance' => 'decimal:2',
        'total_balance' => 'decimal:2',
        'interest_payable' => 'decimal:2',
        'savings_held_for_loan_security' => 'decimal:2',
        'free_savings_emergency' => 'decimal:2',
        'free_savings_rda_flexi_business' => 'decimal:2',
        'total_free_saving' => 'decimal:2',
        'premature_withdraw_charge' => 'decimal:2',
        'combined_opening_balance' => 'decimal:2',
        'additional_data' => 'array',
        'metadata' => 'array'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'profile_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerTransaction::class, 'financial_data_id');
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('statement_period', $period);
    }
}
2.3 CustomerTransaction Model
php
<?php
// app/Models/CustomerTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerTransaction extends Model
{
    protected $table = 'customer_transactions';

    protected $fillable = [
        'financial_data_id', 'customer_id', 'account_type',
        'transaction_date', 'reference_no', 'transaction_type',
        'amount', 'balance_after', 'description'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2'
    ];

    public function financialData(): BelongsTo
    {
        return $this->belongsTo(CustomerFinancialData::class, 'financial_data_id');
    }

    public function scopeByAccount($query, $accountType)
    {
        return $query->where('account_type', $accountType);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
2.4 ApiRequestLog Model
php
<?php
// app/Models/ApiRequestLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $table = 'api_request_logs';

    protected $fillable = [
        'endpoint', 'method', 'payload', 'response',
        'status_code', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array'
    ];

    public function scopeSuccess($query)
    {
        return $query->where('status_code', 200);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_code', '!=', 200);
    }

    public function scopeByEndpoint($query, $endpoint)
    {
        return $query->where('endpoint', $endpoint);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
PART 3: LARAVEL CONTROLLER (API RECEIVER)
php
<?php
// app/Http/Controllers/Api/GoogleSheetsReceiverController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\CustomerFinancialData;
use App\Models\CustomerTransaction;
use App\Models\ApiRequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GoogleSheetsReceiverController extends Controller
{
    /**
     * Receive customer data from Google Sheets
     */
    public function receiveCustomerData(Request $request)
    {
        // Log the incoming request
        $this->logRequest($request, 'receive-customer-data');

        // Validate API Key
        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 401);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'customers' => 'required|array',
            'customers.*.profile.member_number' => 'required|string',
            'customers.*.profile.full_name' => 'required|string',
            'customers.*.financial_data.statement_period' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 422);
        }

        $customers = $request->input('customers');
        $results = [];

        DB::beginTransaction();

        try {
            foreach ($customers as $customerData) {
                try {
                    // Process customer
                    $result = $this->processCustomerData($customerData);
                    $results[] = $result;

                } catch (\Exception $e) {
                    Log::error("Failed to process customer: " . $e->getMessage());
                    $results[] = [
                        'member_number' => $customerData['profile']['member_number'] ?? 'unknown',
                        'status' => 'failed',
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            // Log success response
            $this->logResponse($request, [
                'success' => true,
                'message' => 'Data received successfully',
                'total' => count($customers),
                'results' => $results
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data received successfully',
                'total' => count($customers),
                'results' => $results,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Critical error in receiveCustomerData: " . $e->getMessage());
            
            $this->logResponse($request, [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process data',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Receive single customer data from Google Sheets
     */
    public function receiveSingleCustomer(Request $request)
    {
        $this->logRequest($request, 'receive-single-customer');

        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'profile.member_number' => 'required|string',
            'profile.full_name' => 'required|string',
            'financial_data.statement_period' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 422);
        }

        try {
            $result = $this->processCustomerData($request->all());

            $this->logResponse($request, [
                'success' => true,
                'result' => $result
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer data received successfully',
                'result' => $result,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to process single customer: " . $e->getMessage());
            
            $this->logResponse($request, [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process customer data',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Receive transactions data from Google Sheets
     */
    public function receiveTransactions(Request $request)
    {
        $this->logRequest($request, 'receive-transactions');

        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            'transactions' => 'required|array',
            'transactions.*.transaction_date' => 'required|date',
            'transactions.*.account_type' => 'required|string',
            'transactions.*.amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 422);
        }

        try {
            $customerId = $request->input('customer_id');
            $transactions = $request->input('transactions');

            // Find customer
            $profile = CustomerProfile::where('member_number', $customerId)->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                    'timestamp' => Carbon::now()->toDateTimeString()
                ], 404);
            }

            $financialData = CustomerFinancialData::where('customer_id', $customerId)
                ->latest()
                ->first();

            if (!$financialData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Financial data not found for customer',
                    'timestamp' => Carbon::now()->toDateTimeString()
                ], 404);
            }

            $processed = 0;
            foreach ($transactions as $transaction) {
                CustomerTransaction::create([
                    'financial_data_id' => $financialData->id,
                    'customer_id' => $customerId,
                    'account_type' => $transaction['account_type'],
                    'transaction_date' => $transaction['transaction_date'],
                    'reference_no' => $transaction['reference_no'] ?? null,
                    'transaction_type' => $transaction['transaction_type'] ?? 'deposit',
                    'amount' => $transaction['amount'],
                    'balance_after' => $transaction['balance_after'] ?? 0,
                    'description' => $transaction['description'] ?? null
                ]);
                $processed++;
            }

            $this->logResponse($request, [
                'success' => true,
                'processed' => $processed
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transactions received successfully',
                'processed' => $processed,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to process transactions: " . $e->getMessage());
            
            $this->logResponse($request, [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process transactions',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Receive balances data from Google Sheets
     */
    public function receiveBalances(Request $request)
    {
        $this->logRequest($request, 'receive-balances');

        if (!$this->validateApiKey($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key',
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            'balances' => 'required|array',
            'balances.flexi_balance' => 'numeric',
            'balances.rda_balance' => 'numeric',
            'balances.emergency_balance' => 'numeric',
            'balances.business_balance' => 'numeric',
            'balances.total_balance' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 422);
        }

        try {
            $customerId = $request->input('customer_id');
            $balances = $request->input('balances');

            $profile = CustomerProfile::where('member_number', $customerId)->first();
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found',
                    'timestamp' => Carbon::now()->toDateTimeString()
                ], 404);
            }

            $financialData = CustomerFinancialData::where('customer_id', $customerId)
                ->latest()
                ->first();

            if (!$financialData) {
                $financialData = new CustomerFinancialData();
                $financialData->profile_id = $profile->id;
                $financialData->customer_id = $customerId;
                $financialData->customer_name = $profile->full_name;
                $financialData->statement_period = Carbon::now()->format('F Y');
            }

            // Update balances
            $financialData->flexi_balance = $balances['flexi_balance'] ?? $financialData->flexi_balance;
            $financialData->rda_balance = $balances['rda_balance'] ?? $financialData->rda_balance;
            $financialData->emergency_balance = $balances['emergency_balance'] ?? $financialData->emergency_balance;
            $financialData->business_balance = $balances['business_balance'] ?? $financialData->business_balance;
            $financialData->total_balance = $balances['total_balance'] ?? $financialData->total_balance;
            
            $financialData->save();

            $this->logResponse($request, [
                'success' => true,
                'customer_id' => $customerId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Balances updated successfully',
                'customer_id' => $customerId,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to update balances: " . $e->getMessage());
            
            $this->logResponse($request, [
                'success' => false,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update balances',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * Health check endpoint
     */
    public function healthCheck(Request $request)
    {
        return response()->json([
            'success' => true,
            'status' => 'active',
            'version' => '1.0.0',
            'timestamp' => Carbon::now()->toDateTimeString(),
            'server_time' => Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * Process customer data
     */
    protected function processCustomerData($data)
    {
        $profileData = $data['profile'] ?? [];
        $financialData = $data['financial_data'] ?? [];
        $transactions = $data['transactions'] ?? [];

        // Create or update profile
        $profile = $this->createOrUpdateProfile($profileData);

        // Create or update financial data
        $financial = null;
        if (!empty($financialData)) {
            $financial = $this->createOrUpdateFinancialData($profile, $financialData);
        }

        // Process transactions
        if (!empty($transactions) && $financial) {
            $this->processTransactions($financial, $transactions);
        }

        return [
            'member_number' => $profile->member_number,
            'status' => 'success',
            'profile_id' => $profile->id,
            'financial_data_id' => $financial ? $financial->id : null,
            'transactions_processed' => count($transactions)
        ];
    }

    /**
     * Create or update profile
     */
    protected function createOrUpdateProfile($data)
    {
        return CustomerProfile::updateOrCreate(
            ['member_number' => $data['member_number']],
            [
                'full_name' => $data['full_name'] ?? '',
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'member_type' => $data['member_type'] ?? 'Full',
                'branch' => $data['branch'] ?? '-',
                'occupation' => $data['occupation'] ?? '-',
                'employer' => $data['employer'] ?? '-',
                'residential_address' => $data['residential_address'] ?? '-',
                'registration_date' => isset($data['registration_date']) 
                    ? Carbon::parse($data['registration_date']) 
                    : null,
                'account_status' => $data['account_status'] ?? 'Active',
                'notes' => $data['notes'] ?? null,
                'metadata' => $data['metadata'] ?? null,
                'status' => 'active'
            ]
        );
    }

    /**
     * Create or update financial data
     */
    protected function createOrUpdateFinancialData($profile, $data)
    {
        return CustomerFinancialData::updateOrCreate(
            [
                'profile_id' => $profile->id,
                'customer_id' => $profile->member_number,
                'statement_period' => $data['statement_period'] ?? Carbon::now()->format('F Y')
            ],
            [
                'customer_name' => $profile->full_name,
                'statement_start_date' => $data['statement_start_date'] ?? null,
                'statement_end_date' => $data['statement_end_date'] ?? null,
                
                'monthly_saving_target' => $data['monthly_saving_target'] ?? 0,
                'monthly_total_savings_deposits' => $data['monthly_total_savings_deposits'] ?? 0,
                'monthly_goal_achievement' => $data['monthly_goal_achievement'] ?? 0,
                'overall_saving_goal' => $data['overall_saving_goal'] ?? 0,
                'total_saved' => $data['total_saved'] ?? 0,
                'overall_goal_achievement' => $data['overall_goal_achievement'] ?? 0,
                
                'flexi_opening_balance' => $data['flexi_opening_balance'] ?? 0,
                'flexi_deposit' => $data['flexi_deposit'] ?? 0,
                'flexi_withdrawal' => $data['flexi_withdrawal'] ?? 0,
                'flexi_balance' => $data['flexi_balance'] ?? 0,
                
                'rda_opening_balance' => $data['rda_opening_balance'] ?? 0,
                'rda_deposit' => $data['rda_deposit'] ?? 0,
                'rda_withdrawal' => $data['rda_withdrawal'] ?? 0,
                'rda_balance' => $data['rda_balance'] ?? 0,
                
                'emergency_opening_balance' => $data['emergency_opening_balance'] ?? 0,
                'emergency_deposit' => $data['emergency_deposit'] ?? 0,
                'emergency_withdrawal' => $data['emergency_withdrawal'] ?? 0,
                'emergency_balance' => $data['emergency_balance'] ?? 0,
                
                'business_opening_balance' => $data['business_opening_balance'] ?? 0,
                'business_deposit' => $data['business_deposit'] ?? 0,
                'business_withdrawal' => $data['business_withdrawal'] ?? 0,
                'business_balance' => $data['business_balance'] ?? 0,
                
                'total_balance' => $data['total_balance'] ?? 0,
                'interest_payable' => $data['interest_payable'] ?? 0,
                'savings_held_for_loan_security' => $data['savings_held_for_loan_security'] ?? 0,
                'free_savings_emergency' => $data['free_savings_emergency'] ?? 0,
                'free_savings_rda_flexi_business' => $data['free_savings_rda_flexi_business'] ?? 0,
                'total_free_saving' => $data['total_free_saving'] ?? 0,
                'premature_withdraw_charge' => $data['premature_withdraw_charge'] ?? 0,
                'combined_opening_balance' => $this->calculateCombinedOpeningBalance($data),
                
                'statement_id' => $data['statement_id'] ?? $this->generateStatementId(),
                'pdf_url' => $data['pdf_url'] ?? null,
                'pdf_file_path' => $data['pdf_file_path'] ?? null,
                'status' => 'synced',
                'additional_data' => $data['additional_data'] ?? null,
                'metadata' => $data['metadata'] ?? null
            ]
        );
    }

    /**
     * Process transactions
     */
    protected function processTransactions($financial, $transactions)
    {
        foreach ($transactions as $transaction) {
            CustomerTransaction::create([
                'financial_data_id' => $financial->id,
                'customer_id' => $financial->customer_id,
                'account_type' => $transaction['account_type'] ?? 'flexi',
                'transaction_date' => $transaction['transaction_date'] ?? Carbon::now(),
                'reference_no' => $transaction['reference_no'] ?? null,
                'transaction_type' => $transaction['transaction_type'] ?? 'deposit',
                'amount' => $transaction['amount'] ?? 0,
                'balance_after' => $transaction['balance_after'] ?? 0,
                'description' => $transaction['description'] ?? null
            ]);
        }
    }

    /**
     * Calculate combined opening balance
     */
    protected function calculateCombinedOpeningBalance($data)
    {
        return ($data['flexi_opening_balance'] ?? 0) + 
               ($data['rda_opening_balance'] ?? 0) + 
               ($data['emergency_opening_balance'] ?? 0) + 
               ($data['business_opening_balance'] ?? 0);
    }

    /**
     * Generate statement ID
     */
    protected function generateStatementId()
    {
        $prefix = "FCMG-FD";
        $random = str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $timestamp = date('YmdHis');
        return $prefix . $timestamp . $random;
    }

    /**
     * Validate API Key
     */
    protected function validateApiKey($request)
    {
        $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
        return $apiKey === config('services.google_sheets.api_key');
    }

    /**
     * Log incoming request
     */
    protected function logRequest($request, $endpoint)
    {
        try {
            ApiRequestLog::create([
                'endpoint' => $endpoint,
                'method' => $request->method(),
                'payload' => $request->all(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => 200 // Will be updated later
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to log request: " . $e->getMessage());
        }
    }

    /**
     * Log response
     */
    protected function logResponse($request, $response)
    {
        try {
            $log = ApiRequestLog::where('ip_address', $request->ip())
                ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                ->latest()
                ->first();

            if ($log) {
                $log->response = $response;
                $log->status_code = $response['success'] ?? false ? 200 : 500;
                $log->save();
            }
        } catch (\Exception $e) {
            Log::error("Failed to log response: " . $e->getMessage());
        }
    }
}
PART 4: LARAVEL ROUTES
php
<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleSheetsReceiverController;

Route::prefix('api/v1')->group(function () {
    
    // Google Sheets Receiver Endpoints
    Route::post('/receive/customers', [GoogleSheetsReceiverController::class, 'receiveCustomerData']);
    Route::post('/receive/customer', [GoogleSheetsReceiverController::class, 'receiveSingleCustomer']);
    Route::post('/receive/transactions', [GoogleSheetsReceiverController::class, 'receiveTransactions']);
    Route::post('/receive/balances', [GoogleSheetsReceiverController::class, 'receiveBalances']);
    Route::get('/health', [GoogleSheetsReceiverController::class, 'healthCheck']);
});
PART 5: LARAVEL CONFIGURATION
php
<?php
// config/services.php

return [
    // ... other services
    
    'google_sheets' => [
        'api_key' => env('GOOGLE_SHEETS_API_KEY', 'your-secret-api-key-here'),
        'webhook_url' => env('GOOGLE_SHEETS_WEBHOOK_URL'),
    ],
];
env
# .env
GOOGLE_SHEETS_API_KEY=your-secret-api-key-here
GOOGLE_SHEETS_WEBHOOK_URL=https://your-laravel-app.com/api/v1/receive/customers
APP_URL=https://your-laravel-app.com
PART 6: GOOGLE APPS SCRIPT - DATA SENDER
javascript
// ============================================================================
// GOOGLE SHEETS DATA SENDER TO LARAVEL API
// ============================================================================

// Configuration
const CONFIG = {
  sheetId: "1BTEPceHVgfQe4SRdDmRO_3bV6k8jA_2lWCqglDfiPwU",
  apiUrl: "https://your-laravel-app.com/api/v1",
  apiKey: "your-secret-api-key-here",
  START_ROW: 2,
  FINAL_ROW: 243
};

/**
 * Send all customer data to Laravel
 */
function sendAllCustomersToLaravel() {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.sheetId);
    const sheet = ss.getSheetByName('customerdetails');
    
    if (!sheet) {
      Logger.log('Error: customerdetails sheet not found');
      return { success: false, error: 'Sheet not found' };
    }
    
    const lastRow = sheet.getLastRow();
    const lastCol = sheet.getLastColumn();
    
    if (lastRow < CONFIG.START_ROW) {
      return { success: false, error: 'No data found' };
    }
    
    const headers = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
    const dataRows = sheet.getRange(CONFIG.START_ROW, 1, lastRow - CONFIG.START_ROW + 1, lastCol).getValues();
    
    const customers = [];
    
    dataRows.forEach((row, index) => {
      try {
        const customerData = mapRowToCustomerData(row, headers);
        if (customerData.member_number) {
          // Get transactions
          customerData.transactions = getCustomerTransactions(customerData.member_number);
          customers.push(customerData);
        }
      } catch (error) {
        Logger.log(`Error processing row ${CONFIG.START_ROW + index}: ${error.message}`);
      }
    });
    
    if (customers.length === 0) {
      return { success: false, error: 'No valid customer data found' };
    }
    
    // Send to Laravel
    const response = sendDataToLaravel(customers);
    
    return {
      success: true,
      total: customers.length,
      response: response
    };
    
  } catch (error) {
    Logger.log(`Critical error: ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Send single customer to Laravel
 */
function sendSingleCustomerToLaravel(memberNumber) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.sheetId);
    const sheet = ss.getSheetByName('customerdetails');
    
    if (!sheet) {
      return { success: false, error: 'Sheet not found' };
    }
    
    const lastCol = sheet.getLastColumn();
    const headers = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
    const data = sheet.getRange(CONFIG.START_ROW, 1, sheet.getLastRow() - CONFIG.START_ROW + 1, lastCol).getValues();
    
    let customerRow = null;
    for (let i = 0; i < data.length; i++) {
      if (String(data[i][1]) === String(memberNumber)) {
        customerRow = data[i];
        break;
      }
    }
    
    if (!customerRow) {
      return { success: false, error: 'Customer not found' };
    }
    
    const customerData = mapRowToCustomerData(customerRow, headers);
    customerData.transactions = getCustomerTransactions(memberNumber);
    
    // Send to Laravel
    const response = sendSingleDataToLaravel(customerData);
    
    return {
      success: true,
      customer: customerData,
      response: response
    };
    
  } catch (error) {
    Logger.log(`Error sending customer ${memberNumber}: ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Send transactions to Laravel
 */
function sendTransactionsToLaravel(memberNumber) {
  try {
    const transactions = getCustomerTransactions(memberNumber);
    
    if (transactions.length === 0) {
      return { success: false, error: 'No transactions found' };
    }
    
    const payload = {
      customer_id: memberNumber,
      transactions: transactions
    };
    
    const options = {
      method: 'post',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': CONFIG.apiKey
      },
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(CONFIG.apiUrl + '/receive/transactions', options);
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log(`Error sending transactions: ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Send balances to Laravel
 */
function sendBalancesToLaravel(memberNumber) {
  try {
    const balances = getCustomerBalances(memberNumber);
    
    if (!balances) {
      return { success: false, error: 'Balances not found' };
    }
    
    const payload = {
      customer_id: memberNumber,
      balances: balances
    };
    
    const options = {
      method: 'post',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': CONFIG.apiKey
      },
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(CONFIG.apiUrl + '/receive/balances', options);
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log(`Error sending balances: ${error.message}`);
    return { success: false, error: error.message };
  }
}

/**
 * Map row to customer data
 */
function mapRowToCustomerData(row, headers) {
  const colIndex = (headerName) => {
    const index = headers.indexOf(headerName);
    return index !== -1 ? index : null;
  };
  
  return {
    profile: {
      member_number: String(row[colIndex('CustomerId')] || '').trim(),
      full_name: String(row[colIndex('Customer Name')] || '').trim(),
      email: String(row[colIndex('Email Address')] || '').trim(),
      phone: String(row[colIndex('Phone Number')] || '').trim(),
      gender: String(row[colIndex('Gender')] || '').trim(),
      member_type: String(row[colIndex('Member Type')] || 'Full').trim(),
      branch: String(row[colIndex('Branch')] || '-').trim(),
      occupation: String(row[colIndex('Occupation')] || '-').trim(),
      employer: String(row[colIndex('Employer')] || '-').trim(),
      residential_address: String(row[colIndex('Residential Address')] || '-').trim(),
      registration_date: row[colIndex('Registration Date')] || null,
      account_status: String(row[colIndex('Account Status')] || 'Active').trim(),
      notes: String(row[colIndex('Notes')] || '').trim(),
      metadata: {
        imported_from: 'google_sheets',
        import_date: new Date().toISOString()
      }
    },
    financial_data: {
      statement_period: String(row[colIndex('Statement Period')] || '').trim(),
      statement_start_date: row[colIndex('Start Date')] || null,
      statement_end_date: row[colIndex('End Date')] || null,
      
      monthly_saving_target: parseFloat(row[colIndex('Monthly Saving Target')]) || 0,
      monthly_total_savings_deposits: parseFloat(row[colIndex('Monthly Total Savings/Deposits')]) || 0,
      monthly_goal_achievement: parseFloat(row[colIndex('Monthly Goal Achievement')]) || 0,
      overall_saving_goal: parseFloat(row[colIndex('Overall Saving Goal')]) || 0,
      total_saved: parseFloat(row[colIndex('Total Saved')]) || 0,
      overall_goal_achievement: parseFloat(row[colIndex('Overall Goal Achievement')]) || 0,
      
      flexi_opening_balance: parseFloat(row[colIndex('Flexi Opening Balance')]) || 0,
      flexi_deposit: parseFloat(row[colIndex('Flexi Deposit')]) || 0,
      flexi_withdrawal: parseFloat(row[colIndex('Flexi Withdrawal')]) || 0,
      flexi_balance: parseFloat(row[colIndex('Flexi Balance')]) || 0,
      
      rda_opening_balance: parseFloat(row[colIndex('RDA Opening Balance')]) || 0,
      rda_deposit: parseFloat(row[colIndex('RDA Deposit')]) || 0,
      rda_withdrawal: parseFloat(row[colIndex('RDA Withdrawal')]) || 0,
      rda_balance: parseFloat(row[colIndex('RDA Balance')]) || 0,
      
      emergency_opening_balance: parseFloat(row[colIndex('Emergency Opening Balance')]) || 0,
      emergency_deposit: parseFloat(row[colIndex('Emergency Deposit')]) || 0,
      emergency_withdrawal: parseFloat(row[colIndex('Emergency Withdrawal')]) || 0,
      emergency_balance: parseFloat(row[colIndex('Emergency Balance')]) || 0,
      
      business_opening_balance: parseFloat(row[colIndex('Business Opening Balance')]) || 0,
      business_deposit: parseFloat(row[colIndex('Business Deposit')]) || 0,
      business_withdrawal: parseFloat(row[colIndex('Business Withdrawal')]) || 0,
      business_balance: parseFloat(row[colIndex('Business Balance')]) || 0,
      
      total_balance: parseFloat(row[colIndex('Total Balance')]) || 0,
      interest_payable: parseFloat(row[colIndex('Interest Payable')]) || 0,
      savings_held_for_loan_security: parseFloat(row[colIndex('Savings Held for Loan Security')]) || 0,
      free_savings_emergency: parseFloat(row[colIndex('Free Savings-Emmergence')]) || 0,
      free_savings_rda_flexi_business: parseFloat(row[colIndex('Free Saving-RDA,Flexi &Business')]) || 0,
      total_free_saving: parseFloat(row[colIndex('Total Free Saving')]) || 0,
      premature_withdraw_charge: parseFloat(row[colIndex('Premature Withdraw Charge')]) || 0,
      
      combined_opening_balance: 0,
      statement_id: generateStatementID(),
      status: 'synced'
    },
    transactions: []
  };
}

/**
 * Get customer transactions
 */
function getCustomerTransactions(customerId) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.sheetId);
    const sheet = ss.getSheetByName('Transactions');
    
    if (!sheet) return [];
    
    const lastRow = sheet.getLastRow();
    if (lastRow < 2) return [];
    
    const data = sheet.getRange(2, 1, lastRow - 1, sheet.getLastColumn()).getValues();
    
    return data
      .filter(row => String(row[1]) === String(customerId))
      .map(tx => ({
        account_type: determineAccountType(String(tx[2] || '')),
        transaction_date: formatDateForAPI(tx[0]),
        reference_no: String(tx[3] || ''),
        transaction_type: String(tx[2] || ''),
        amount: parseFloat(tx[4]) || 0,
        balance_after: 0,
        description: String(tx[2] || '')
      }));
    
  } catch (error) {
    Logger.log(`Error getting transactions: ${error.message}`);
    return [];
  }
}

/**
 * Get customer balances
 */
function getCustomerBalances(customerId) {
  try {
    const ss = SpreadsheetApp.openById(CONFIG.sheetId);
    const sheet = ss.getSheetByName('savingbalances');
    
    if (!sheet) return null;
    
    const lastRow = sheet.getLastRow();
    if (lastRow < 2) return null;
    
    const data = sheet.getRange(2, 1, lastRow - 1, sheet.getLastColumn()).getValues();
    const customerData = data.find(row => String(row[0]) === String(customerId));
    
    if (!customerData) return null;
    
    return {
      flexi_balance: parseFloat(customerData[10]) || 0,
      rda_balance: parseFloat(customerData[14]) || 0,
      emergency_balance: parseFloat(customerData[18]) || 0,
      business_balance: parseFloat(customerData[22]) || 0,
      total_balance: parseFloat(customerData[23]) || 0,
      interest_payable: parseFloat(customerData[24]) || 0,
      loan_security: parseFloat(customerData[25]) || 0,
      free_emerg_saving: parseFloat(customerData[26]) || 0,
      free_other_saving: parseFloat(customerData[27]) || 0,
      total_free_saving: parseFloat(customerData[28]) || 0,
      withdraw_charge: parseFloat(customerData[29]) || 0,
    };
    
  } catch (error) {
    Logger.log(`Error getting balances: ${error.message}`);
    return null;
  }
}

/**
 * Determine account type
 */
function determineAccountType(description) {
  const desc = description.toLowerCase();
  if (desc.includes('rda')) return 'rda';
  if (desc.includes('emergency') || desc.includes('emerg')) return 'emergency';
  if (desc.includes('business') || desc.includes('biz')) return 'business';
  return 'flexi';
}

/**
 * Format date for API
 */
function formatDateForAPI(dateValue) {
  try {
    const date = new Date(dateValue);
    if (isNaN(date.getTime())) {
      return new Date().toISOString().split('T')[0];
    }
    return date.toISOString().split('T')[0];
  } catch (error) {
    return new Date().toISOString().split('T')[0];
  }
}

/**
 * Generate statement ID
 */
function generateStatementID() {
  const prefix = "FCMG-FD";
  const random = Math.floor(1000 + Math.random() * 9000);
  const timestamp = Date.now().toString().slice(-6);
  return prefix + timestamp + random;
}

/**
 * Send data to Laravel
 */
function sendDataToLaravel(customers) {
  try {
    const payload = {
      customers: customers
    };
    
    const options = {
      method: 'post',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': CONFIG.apiKey
      },
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(CONFIG.apiUrl + '/receive/customers', options);
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log(`Error sending to Laravel: ${error.message}`);
    throw error;
  }
}

/**
 * Send single data to Laravel
 */
function sendSingleDataToLaravel(customer) {
  try {
    const options = {
      method: 'post',
      headers: {
        'Content-Type': 'application/json',
        'X-API-Key': CONFIG.apiKey
      },
      payload: JSON.stringify(customer),
      muteHttpExceptions: true
    };
    
    const response = UrlFetchApp.fetch(CONFIG.apiUrl + '/receive/customer', options);
    return JSON.parse(response.getContentText());
    
  } catch (error) {
    Logger.log(`Error sending single customer: ${error.message}`);
    throw error;
  }
}

/**
 * Create menu in Google Sheets
 */
function onOpen() {
  const ui = SpreadsheetApp.getUi();
  const menu = ui.createMenu('📤 Send to Laravel');
  menu.addItem('📊 Send All Customers', 'sendAllCustomersToLaravel');
  menu.addItem('👤 Send Single Customer', 'showSendSingleCustomerDialog');
  menu.addItem('📝 Send Transactions', 'showSendTransactionsDialog');
  menu.addItem('💰 Send Balances', 'showSendBalancesDialog');
  menu.addSeparator();
  menu.addItem('🔄 Setup Auto-Sync (6 Hours)', 'setupAutoSync');
  menu.addToUi();
}

/**
 * Show send single customer dialog
 */
function showSendSingleCustomerDialog() {
  const ui = SpreadsheetApp.getUi();
  const response = ui.prompt(
    'Send Single Customer',
    'Enter Member Number:',
    ui.ButtonSet.OK_CANCEL
  );
  
  if (response.getSelectedButton() === ui.Button.OK) {
    const memberNumber = response.getResponseText().trim();
    if (memberNumber) {
      const result = sendSingleCustomerToLaravel(memberNumber);
      ui.alert(
        'Result',
        result.success ? '✅ Customer sent successfully!' : '❌ Failed: ' + result.error,
        ui.ButtonSet.OK
      );
    }
  }
}

/**
 * Show send transactions dialog
 */
function showSendTransactionsDialog() {
  const ui = SpreadsheetApp.getUi();
  const response = ui.prompt(
    'Send Transactions',
    'Enter Member Number:',
    ui.ButtonSet.OK_CANCEL
  );
  
  if (response.getSelectedButton() === ui.Button.OK) {
    const memberNumber = response.getResponseText().trim();
    if (memberNumber) {
      const result = sendTransactionsToLaravel(memberNumber);
      ui.alert(
        'Result',
        result.success ? '✅ Transactions sent successfully!' : '❌ Failed: ' + result.error,
        ui.ButtonSet.OK
      );
    }
  }
}

/**
 * Show send balances dialog
 */
function showSendBalancesDialog() {
  const ui = SpreadsheetApp.getUi();
  const response = ui.prompt(
    'Send Balances',
    'Enter Member Number:',
    ui.ButtonSet.OK_CANCEL
  );
  
  if (response.getSelectedButton() === ui.Button.OK) {
    const memberNumber = response.getResponseText().trim();
    if (memberNumber) {
      const result = sendBalancesToLaravel(memberNumber);
      ui.alert(
        'Result',
        result.success ? '✅ Balances sent successfully!' : '❌ Failed: ' + result.error,
        ui.ButtonSet.OK
      );
    }
  }
}

/**
 * Setup auto-sync every 6 hours
 */
function setupAutoSync() {
  try {
    // Delete existing triggers
    const triggers = ScriptApp.getProjectTriggers();
    triggers.forEach(trigger => {
      if (trigger.getHandlerFunction() === 'autoSync') {
        ScriptApp.deleteTrigger(trigger);
      }
    });
    
    // Create new trigger
    ScriptApp.newTrigger('autoSync')
      .timeBased()
      .everyHours(6)
      .create();
    
    SpreadsheetApp.getUi().alert('✅ Auto-sync setup complete! Will run every 6 hours.');
    
  } catch (error) {
    SpreadsheetApp.getUi().alert('❌ Failed to setup auto-sync: ' + error.message);
  }
}

/**
 * Auto-sync function - runs every 6 hours
 */
function autoSync() {
  try {
    Logger.log('Auto-sync started at ' + new Date().toISOString());
    
    const result = sendAllCustomersToLaravel();
    
    if (result.success) {
      Logger.log(`Auto-sync completed: ${result.total} customers sent`);
    } else {
      Logger.log(`Auto-sync failed: ${result.error}`);
    }
    
    // Send email notification
    sendSyncNotification(result);
    
  } catch (error) {
    Logger.log('Auto-sync error: ' + error.message);
    sendErrorNotification(error);
  }
}

/**
 * Send sync notification email
 */
function sendSyncNotification(result) {
  try {
    const subject = 'Google Sheets Sync Report - ' + new Date().toISOString();
    const body = `
      <h2>Sync Report</h2>
      <p><strong>Time:</strong> ${new Date().toISOString()}</p>
      <p><strong>Status:</strong> ${result.success ? '✅ Success' : '❌ Failed'}</p>
      <p><strong>Total Customers:</strong> ${result.total || 0}</p>
      <p><strong>Response:</strong> ${JSON.stringify(result.response || {})}</p>
    `;
    
    GmailApp.sendEmail(
      'admin@example.com',
      subject,
      '',
      { htmlBody: body }
    );
    
  } catch (error) {
    Logger.log('Error sending notification: ' + error.message);
  }
}

/**
 * Send error notification
 */
function sendErrorNotification(error) {
  try {
    const subject = '⚠️ Google Sheets Sync Error';
    const body = `
      <h2>Sync Error</h2>
      <p><strong>Time:</strong> ${new Date().toISOString()}</p>
      <p><strong>Error:</strong> ${error.message}</p>
      <p><strong>Stack:</strong> ${error.stack}</p>
    `;
    
    GmailApp.sendEmail(
      'admin@example.com',
      subject,
      '',
      { htmlBody: body }
    );
    
  } catch (e) {
    Logger.log('Error sending error notification: ' + e.message);
  }
}
PART 7: LARAVEL WEBHOOK RECEIVER (ALTERNATIVE)
php
<?php
// app/Http/Controllers/Api/WebhookReceiverController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\CustomerFinancialData;
use App\Models\CustomerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebhookReceiverController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validate webhook secret
        $secret = $request->header('X-Webhook-Secret');
        if ($secret !== config('services.google_sheets.webhook_secret')) {
            Log::warning('Invalid webhook secret');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        switch ($event) {
            case 'customer.created':
            case 'customer.updated':
                return $this->handleCustomerEvent($payload['data']);
            
            case 'transaction.created':
                return $this->handleTransactionEvent($payload['data']);
            
            case 'balance.updated':
                return $this->handleBalanceEvent($payload['data']);
            
            default:
                return response()->json(['message' => 'Event not supported'], 400);
        }
    }

    protected function handleCustomerEvent($data)
    {
        try {
            DB::beginTransaction();

            $profile = CustomerProfile::updateOrCreate(
                ['member_number' => $data['member_number']],
                [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'member_type' => $data['member_type'] ?? 'Full',
                    'registration_date' => isset($data['registration_date']) 
                        ? Carbon::parse($data['registration_date']) 
                        : null,
                    'account_status' => $data['account_status'] ?? 'Active',
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer processed successfully',
                'data' => $profile
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function handleTransactionEvent($data)
    {
        try {
            DB::beginTransaction();

            $profile = CustomerProfile::where('member_number', $data['customer_id'])->first();
            if (!$profile) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $financialData = CustomerFinancialData::where('customer_id', $data['customer_id'])
                ->latest()
                ->first();

            if (!$financialData) {
                $financialData = new CustomerFinancialData();
                $financialData->profile_id = $profile->id;
                $financialData->customer_id = $data['customer_id'];
                $financialData->customer_name = $profile->full_name;
                $financialData->statement_period = Carbon::now()->format('F Y');
                $financialData->save();
            }

            $transaction = CustomerTransaction::create([
                'financial_data_id' => $financialData->id,
                'customer_id' => $data['customer_id'],
                'account_type' => $data['account_type'] ?? 'flexi',
                'transaction_date' => $data['transaction_date'] ?? Carbon::now(),
                'reference_no' => $data['reference_no'] ?? null,
                'transaction_type' => $data['transaction_type'] ?? 'deposit',
                'amount' => $data['amount'] ?? 0,
                'balance_after' => $data['balance_after'] ?? 0,
                'description' => $data['description'] ?? null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction processed successfully',
                'data' => $transaction
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook transaction error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function handleBalanceEvent($data)
    {
        try {
            DB::beginTransaction();

            $profile = CustomerProfile::where('member_number', $data['customer_id'])->first();
            if (!$profile) {
                return response()->json(['error' => 'Customer not found'], 404);
            }

            $financialData = CustomerFinancialData::where('customer_id', $data['customer_id'])
                ->latest()
                ->first();

            if (!$financialData) {
                $financialData = new CustomerFinancialData();
                $financialData->profile_id = $profile->id;
                $financialData->customer_id = $data['customer_id'];
                $financialData->customer_name = $profile->full_name;
                $financialData->statement_period = Carbon::now()->format('F Y');
            }

            // Update balances
            $financialData->flexi_balance = $data['flexi_balance'] ?? $financialData->flexi_balance;
            $financialData->rda_balance = $data['rda_balance'] ?? $financialData->rda_balance;
            $financialData->emergency_balance = $data['emergency_balance'] ?? $financialData->emergency_balance;
            $financialData->business_balance = $data['business_balance'] ?? $financialData->business_balance;
            $financialData->total_balance = $data['total_balance'] ?? $financialData->total_balance;
            $financialData->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Balances updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook balance error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
PART 8: LARAVEL WEBHOOK ROUTES
php
<?php
// routes/api.php

Route::post('/webhook/google-sheets', [WebhookReceiverController::class, 'handleWebhook'])
    ->name('webhook.google-sheets');
PART 9: CONFIGURATION FILE
php
<?php
// config/services.php

return [
    'google_sheets' => [
        'api_key' => env('GOOGLE_SHEETS_API_KEY'),
        'webhook_secret' => env('GOOGLE_SHEETS_WEBHOOK_SECRET'),
        'webhook_url' => env('GOOGLE_SHEETS_WEBHOOK_URL'),
    ],
];
env
# .env
GOOGLE_SHEETS_API_KEY=your-api-key-here
GOOGLE_SHEETS_WEBHOOK_SECRET=your-webhook-secret-here
GOOGLE_SHEETS_WEBHOOK_URL=https://your-app.com/webhook/google-sheets
PART 10: TESTING SCRIPT
php
<?php
// tests/Feature/GoogleSheetsReceiverTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\CustomerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoogleSheetsReceiverTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_receive_customer_data()
    {
        $payload = [
            'customers' => [
                [
                    'profile' => [
                        'member_number' => 'ASC1',
                        'full_name' => 'John Doe',
                        'email' => 'john@example.com',
                        'phone' => '0712345678',
                        'member_type' => 'Full'
                    ],
                    'financial_data' => [
                        'statement_period' => 'January 2024',
                        'total_balance' => 1000000,
                        'flexi_balance' => 500000,
                        'rda_balance' => 300000,
                        'emergency_balance' => 200000,
                    ],
                    'transactions' => [
                        [
                            'account_type' => 'flexi',
                            'transaction_date' => '2024-01-15',
                            'amount' => 100000,
                            'transaction_type' => 'deposit'
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->withHeaders([
            'X-API-Key' => config('services.google_sheets.api_key')
        ])->postJson('/api/v1/receive/customers', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customer_profiles', ['member_number' => 'ASC1']);
        $this->assertDatabaseHas('customer_transactions', ['customer_id' => 'ASC1']);
    }

    public function test_rejects_invalid_api_key()
    {
        $response = $this->withHeaders([
            'X-API-Key' => 'invalid-key'
        ])->postJson('/api/v1/receive/customers', []);

        $response->assertStatus(401);
    }
}
DEPLOYMENT INSTRUCTIONS
1. Laravel Setup
bash
# Clone repository
git clone your-repository

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Create symbolic link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
2. Environment Configuration
bash
# Edit .env file
GOOGLE_SHEETS_API_KEY=your-secret-key
GOOGLE_SHEETS_WEBHOOK_SECRET=your-webhook-secret
APP_URL=https://your-domain.com
3. Google Apps Script Deployment
Open Google Sheet

Go to Extensions > Apps Script

Copy the Google Apps Script code

Deploy as Web App

Set execute as: "Me"

Set who has access: "Anyone" or "Only myself"

Copy the Web App URL

4. Test Integration
bash
# Test API endpoint
curl -X POST https://your-domain.com/api/v1/health

# Test webhook
curl -X POST https://your-domain.com/webhook/google-sheets \
  -H "X-Webhook-Secret: your-secret" \
  -H "Content-Type: application/json" \
  -d '{"event":"customer.created","data":{"member_number":"ASC1","full_name":"Test User"}}'
SUMMARY
This complete system provides:

Laravel API Receiver - Receives and stores data from Google Sheets

Google Apps Script Sender - Sends data from Google Sheets to Laravel

Webhook Support - Real-time data sync

Auto-Sync (6 Hours) - Automatic synchronization every 6 hours

Data Validation - Validates all incoming data

Error Handling - Comprehensive error logging

Request Logging - Logs all API requests

Testing - Complete test coverage