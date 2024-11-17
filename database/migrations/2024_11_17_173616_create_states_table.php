<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\State;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->nestedSet();
            $table->timestamps();
        });

        $states = config('states');
        foreach($states as $state){
            $parent = State::create(['name'=>$state['name']]);
            $children = $state['cities'];
            foreach($children as $child)
                $parent->children()->create(['name'=>$child]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
