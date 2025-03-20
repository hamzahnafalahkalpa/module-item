<?php

use Gii\ModuleItem\Models\CardStock;
use Gii\ModuleItem\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zahzah\ModuleTransaction\Models\Transaction\Transaction;

return new class extends Migration
{
    use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.CardStock', CardStock::class));
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()) {
            Schema::create($table_name, function (Blueprint $table) {
                $transaction = app(config('database.models.Transaction', Transaction::class));
                $item        = app(config('database.models.Item', Item::class));

                $table->ulid('id')->primary();
                $table->foreignIdFor($item::class)->nullable(false)
                      ->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();

                $table->foreignIdFor($transaction::class)->nullable(false)
                      ->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
                      
                $table->timestamp('reported_at')->nullable();
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['item_id'], 'item_ref');
                $table->index(['item_id', 'transaction_id'], 'cs_trx_item');
            });

            Schema::table($table_name, function (Blueprint $table) {
                $table->foreignIdFor($this->__table,'parent_id')
                        ->nullable()->after($this->__table->getKeyName())
                        ->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->__table->getTable());
    }
};
