<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_forms', function (Blueprint $table) {
            $table->id();
            $table->date('date_request');
            $table->date('date_needed');
            $table->integer('status');
            $table->string('full_name');
            $table->foreignId('request_by')->constrained('users', 'id');
            $table->string('contact');
            $table->foreignId('position')->constrained('positions', 'id');
            $table->foreignId('department')->constrained('departments', 'id');
            $table->string('branch');
            $table->string('urgency');
            $table->foreignId('request_type')->constrained('request_types', 'id');
            $table->text('request_details');
            $table->text('remarks');
            $table->foreignId('next_department')->constrained('departments', 'id');
            $table->foreignId('assign_employee')->constrained('users', 'id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requisition_forms');
    }
}
