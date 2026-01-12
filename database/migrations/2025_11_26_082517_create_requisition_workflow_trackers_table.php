<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionWorkflowTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_workflow_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained('purchase_requisition_forms', 'id');
            $table->foreignId('department_id')->constrained('departments', 'id');
            $table->foreignId('employee_id')->constrained('users', 'id')->nullable();
            $table->timestamp('submitted_at')->nullable();
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
        Schema::dropIfExists('requisition_workflow_trackers');
    }
}
