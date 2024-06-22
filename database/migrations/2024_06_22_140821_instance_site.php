<?php

use App\Models\Instance;
use App\Models\Site;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instance_site', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Instance::class);
            $table->foreignIdFor(Site::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instance_site');
    }
};
