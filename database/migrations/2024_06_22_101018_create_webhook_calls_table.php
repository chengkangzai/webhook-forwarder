<?php

use App\Models\Instance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('webhook_calls', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('url');
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->text('exception')->nullable();

            $table->foreignId(Instance::class)->nullable();

            $table->timestamps();
        });
    }
};
