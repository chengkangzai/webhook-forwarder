<?php

use App\Models\Instance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('url');
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->json('exception')->nullable();

            $table->string('status')->nullable();
            $table->dateTime('forwarded_at')->nullable();

            $table->foreignIdFor(Instance::class)->nullable();

            $table->timestamps();
        });
    }
};
