<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Concerns\HasOneOfMorphToMany\Resources;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestRelation extends Model
{
    use HasUuids;

    public $timestamps = false;

    public function testModels(): MorphToMany
    {
        return $this->morphToMany(TestModel::class, 'test_model', 'has_test_relations');
    }

    #[\Override]
    protected static function booted(): void
    {
        parent::booted();

        Relation::enforceMorphMap([
            'test_relation' => TestRelation::class,
        ]);

        Schema::dropIfExists('test_relations');
        Schema::create('test_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
        });
    }
}
