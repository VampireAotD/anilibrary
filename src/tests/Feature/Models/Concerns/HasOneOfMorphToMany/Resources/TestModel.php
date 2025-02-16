<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Concerns\HasOneOfMorphToMany\Resources;

use App\Database\Relations\OneOfMorphToMany;
use App\Models\Concerns\HasOneOfMorphToManyRelation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestModel extends Model
{
    use HasUuids;
    use HasOneOfMorphToManyRelation;

    public $timestamps = false;

    public function testRelation(): OneOfMorphToMany
    {
        return $this->oneOfMorphToMany(TestRelation::class, 'test_model', 'has_test_relations');
    }

    public function testRelationWithDefaultModel(): OneOfMorphToMany
    {
        return $this->testRelation()->withDefault([
            'content' => 'default',
        ]);
    }

    #[\Override]
    protected static function booted(): void
    {
        parent::booted();

        Relation::enforceMorphMap([
            'test_model' => TestModel::class,
        ]);

        Schema::dropIfExists('test_models');
        Schema::create('test_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('content')->nullable();
        });
    }
}
