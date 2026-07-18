<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('status', 30)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Employment Types
        Schema::create('employment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('status', 30)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Careers (Jobs)
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('educational_requirements')->nullable();
            $table->text('experience_requirements')->nullable();
            $table->text('additional_requirements')->nullable();
            $table->text('skills')->nullable();
            $table->text('benefits')->nullable();
            
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('employment_type_id')->nullable()->constrained('employment_types')->nullOnDelete();
            
            $table->string('location')->nullable();
            $table->integer('vacancy')->default(1);
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_type', 40)->default('Negotiable');
            $table->date('application_deadline')->nullable();
            $table->string('featured_image')->nullable();
            
            $table->string('status', 30)->default('draft'); // draft, active, inactive, closed
            $table->boolean('is_featured')->default(false);
            
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Career Questions
        Schema::create('career_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            $table->string('question');
            $table->string('help_text')->nullable();
            $table->string('question_type', 40)->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Career Applications
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('present_address')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_designation')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->date('available_joining_date')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path');
            $table->string('status', 40)->default('new'); // new, under_review, shortlisted, interview_scheduled, selected, rejected, withdrawn
            $table->text('admin_note')->nullable();
            $table->string('submitted_ip', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Career Application Answers (Dynamic Question Answers)
        Schema::create('career_application_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('career_application_id');
            $table->foreign('career_application_id', 'app_ans_app_id_fk')
                  ->references('id')
                  ->on('career_applications')
                  ->onDelete('cascade');
            $table->foreignId('career_question_id')->nullable()->constrained('career_questions')->nullOnDelete();
            $table->json('question_snapshot');
            $table->text('answer')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });

        // 7. Status Histories
        Schema::create('career_application_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('career_application_id');
            $table->foreign('career_application_id', 'status_hist_app_id_fk')
                  ->references('id')
                  ->on('career_applications')
                  ->onDelete('cascade');
            $table->string('previous_status');
            $table->string('new_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_application_status_histories');
        Schema::dropIfExists('career_application_answers');
        Schema::dropIfExists('career_applications');
        Schema::dropIfExists('career_questions');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('employment_types');
        Schema::dropIfExists('departments');
    }
};
