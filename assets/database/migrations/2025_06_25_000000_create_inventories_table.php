<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Hanafalah\ModuleItem\Models\{
    Brand,
    Inventory,
    SupplyCategory
};

return new class extends Migration
{
    use Hanafalah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct()
    {
        $this->__table = app(config('database.models.Inventory', Inventory::class));
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
                $brand = app(config('database.models.Brand',Brand::class));
                $supply_category = app(config('database.models.SupplyCategory', SupplyCategory::class));

                $table->ulid('id')->primary();
                $table->string('inventory_code', 50)->nullable(true);
                $table->string('name', 255)->nullable(false);
                $table->string('reference_type');
                $table->string('reference_id');

                $table->foreignIdFor($brand::class)
                        ->nullable()->index()->constrained()
                        ->cascadeOnUpdate()->restrictOnDelete();

                $table->foreignIdFor($supply_category::class)
                      ->nullable()->index()->constrained()
                      ->cascadeOnUpdate()->nullOnDelete();

                $table->string('model_name', 255)->default('')->nullable(false);
                $table->text('description')->nullable();
                $table->json('props')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->fullText(['name', 'reference_type', 'reference_id'], 'inv_name_ref');
                $table->index(['reference_type', 'reference_id'],'inv_ref');
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
