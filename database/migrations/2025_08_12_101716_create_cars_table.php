<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('engine_cc')->nullable();   
            $table->unsignedSmallInteger('power_hp')->nullable();   
            $table->tinyInteger('doors')->nullable();
            $table->tinyInteger('seats')->nullable();
            $table->unsignedInteger('mileage_km')->nullable();
            $table->string('registration_number')->nullable()->unique();
            $table->integer('status')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('hidden_at')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('brand_id')->nullable()->constrained('makes');
            $table->foreignId('car_model_id')->nullable()->constrained('car_models');
            $table->foreignId('fuel_type_id')->nullable()->constrained('fuel_types');
            $table->foreignId('gearbox_id')->nullable()->constrained('gearboxes');
            $table->index(['status','published_at']);
            $table->index(['car_model_id','fuel_type_id','gearbox_id']);
            $table->index(['year','mileage_km']);
            $table->index(['power_hp','engine_cc']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
