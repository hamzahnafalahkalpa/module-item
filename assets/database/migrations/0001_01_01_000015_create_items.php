<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Gii\ModuleItem\Models\{
    Item, ItemStuff
};

return new class extends Migration
{
    use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.Item', Item::class));
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
                $itemStuff = app(config('database.models.ItemStuff',ItemStuff::class));

                $table->id();
                $table->string('barcode',50)->nullable()->unique();
                $table->string('item_code',50)->nullable();
                $table->string('reference_type', 50);
                $table->string('reference_id', 36);
                $table->string('name');
                $table->unsignedSmallInteger('net_qty')->nullable();

                $table->foreignIdFor($itemStuff::class,'net_unit_id')
                      ->nullable()->index()->constrained($itemStuff->getTable(),$itemStuff->getKeyName())
                      ->cascadeOnUpdate()->nullOnDelete();

                $table->string('netto',100)->nullable();
                $table->unsignedBigInteger('selling_price')->default(0);
                $table->unsignedBigInteger('cogs')->default(0);
                $table->unsignedBigInteger('last_selling_price')->default(0);
                $table->unsignedBigInteger('last_cogs')->default(0);
                $table->unsignedTinyInteger('margin')->default(20);
                $table->unsignedTinyInteger('tax')->default(11);
                $table->unsignedBigInteger('min_stock')->default(150);
                $table->boolean('is_using_batch')->default(false)->nullable(false);

                $table->foreignIdFor($itemStuff::class,'unit_id')
                      ->nullable()->index()->constrained($itemStuff->getTable(),$itemStuff->getKeyName())
                      ->cascadeOnUpdate()->nullOnDelete();

                $table->string('status', 60)->default(0);
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();


                $table->index(['reference_id', 'reference_type'], 'item_ref');
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
