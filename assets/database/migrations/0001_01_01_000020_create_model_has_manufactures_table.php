<?php

use Gii\ModuleItem\Models\{
    ModelHasManufacture,
    Manufacture
};

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   use Zahzah\LaravelSupport\Concerns\NowYouSeeMe;

    private $__table;

    public function __construct(){
        $this->__table = app(config('database.models.ModelHasManufacture', ModelHasManufacture::class));
    }

    public function up(): void{
        $table_name = $this->__table->getTable();
        if (!$this->isTableExists()){
            Schema::create($table_name, function (Blueprint $table) {
                $manufacture = app(config('database.models.Manufacture', Manufacture::class));

                $table->id();
                $table->string('model_type',50)->nullable(false);
                $table->string('model_id',36)->nullable(false);
                $table->foreignIdFor($manufacture::class)->index()
                      ->constrained()->cascadeOnUpdate()->restrictOnDelete();
                
                $table->index(['model_type','model_id'],'model_ref');
                $table->index(['model_type','model_id','manufacture_id'],'model_mf_ref');
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
