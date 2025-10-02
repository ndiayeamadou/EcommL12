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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('registration_number')->unique()->nullable();
            $table->string('tax_number')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Sénégal');
            
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('companies')->onDelete('cascade');
            
            $table->enum('type', ['holding', 'subsidiary', 'branch'])->default('subsidiary');
            $table->date('founded_date')->nullable();
            $table->integer('employee_count')->default(0);
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('industry')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_position')->nullable();
            
            $table->json('settings')->nullable();
            $table->json('social_links')->nullable();
            
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'type']);
            $table->index('parent_id');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
