<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProcessesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('processes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
            $table->integer('expert_id')->unsigned()->nullable();
            $table->foreign('expert_id')->references('user_id')->on('experts')->onDelete('set null');
            $table->integer('insured_id')->unsigned()->nullable();
            $table->foreign('insured_id')->references('id')->on('insureds')->onDelete('set null');
            $table->integer('taker_id')->unsigned()->nullable();
            $table->foreign('taker_id')->references('id')->on('insureds')->onDelete('set null');
            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('set null');
            $table->integer('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('set null');
            $table->string('certificate');
            $table->string('reference');
            $table->string('client_insureds_info', 5000);
            $table->string('client_others_info', 5000);
            $table->string('email');
            $table->string('apolice');
            $table->integer('deadline_preliminar')->default(1);
            $table->integer('deadline_complete')->default(30);
            $table->string('preliminar_date');
            $table->string('situation_date');
            $table->string('situation_observations', 5000)->nullable();
            $table->string('situation_losts');
            $table->boolean('preliminar_sent');
            $table->timestamp('finish');
            $table->string('complete_report', 500);
            $table->string('invoice', 500);
            $table->string('cancel_reason', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('processes');
    }

}
