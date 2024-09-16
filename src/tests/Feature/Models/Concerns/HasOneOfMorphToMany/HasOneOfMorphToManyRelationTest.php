<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Concerns\HasOneOfMorphToMany;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Models\Concerns\HasOneOfMorphToMany\Resources\TestModel;
use Tests\Feature\Models\Concerns\HasOneOfMorphToMany\Resources\TestRelation;
use Tests\TestCase;

class HasOneOfMorphToManyRelationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('has_test_relations');
        Schema::create('has_test_relations', function (Blueprint $table) {
            $table->foreignUuid('test_relation_id');
            $table->uuidMorphs('test_model');
        });
    }

    public function testCanAttachOneRelation(): void
    {
        $model    = TestModel::query()->create();
        $relation = TestRelation::query()->create();

        $this->assertNull($model->testRelation);
        $this->assertDatabaseCount('has_test_relations', 0);

        $model->testRelation()->attach($relation);

        $model->refresh();

        $this->assertNotNull($model->testRelation);
        $this->assertEquals($relation->id, $model->testRelation->id);
        $this->assertDatabaseCount('has_test_relations', 1);
    }

    public function testCanDetachOneRelation(): void
    {
        $model    = TestModel::query()->create();
        $relation = TestRelation::query()->create();

        $this->assertDatabaseCount($model, 1);
        $this->assertDatabaseCount($relation, 1);

        $model->testRelation()->attach($relation);

        $this->assertDatabaseCount('has_test_relations', 1);

        $model->testRelation()->detach();

        $this->assertDatabaseCount($model, 1);
        $this->assertDatabaseCount($relation, 1);
        $this->assertDatabaseCount('has_test_relations', 0);
    }

    public function testCanGetRelation(): void
    {
        $model    = TestModel::query()->create();
        $relation = TestRelation::query()->create();

        $this->assertNull($model->testRelation);

        $model->testRelation()->attach($relation);
        $model->refresh();

        $this->assertNotNull($model->testRelation);
        $this->assertEquals($relation->id, $model->testRelation->id);
    }

    public function testWillGetOnlyOneRelation(): void
    {
        $model = TestModel::query()->create();

        $firstRelation  = TestRelation::query()->create();
        $secondRelation = TestRelation::query()->create();

        $model->testRelation()->sync([$firstRelation->id, $secondRelation->id]);
        $model->refresh();

        $this->assertNotInstanceOf(Collection::class, $model->testRelation);
        $this->assertInstanceOf(TestRelation::class, $model->testRelation);
        $this->assertEquals($firstRelation->id, $model->testRelation->id);
    }

    public function testWillGetOnlyOneRelationUsingEagerLoading(): void
    {
        $model = TestModel::query()->create();

        $firstRelation  = TestRelation::query()->create();
        $secondRelation = TestRelation::query()->create();

        $model->testRelation()->sync([$firstRelation->id, $secondRelation->id]);

        $model->load('testRelation');

        $this->assertNotInstanceOf(Collection::class, $model->testRelation);
        $this->assertInstanceOf(TestRelation::class, $model->testRelation);
        $this->assertEquals($firstRelation->id, $model->testRelation->id);

        $modelWith = TestModel::with('testRelation')->first();

        $this->assertNotInstanceOf(Collection::class, $modelWith->testRelation);
        $this->assertInstanceOf(TestRelation::class, $modelWith->testRelation);
        $this->assertEquals($firstRelation->id, $modelWith->testRelation->id);
    }

    public function testCanGetDefaultRelation(): void
    {
        $model = TestModel::query()->create();

        $this->assertDatabaseCount(TestRelation::class, 0);
        $this->assertDatabaseCount('has_test_relations', 0);

        $this->assertNull($model->testRelation);
        $this->assertNotNull($model->testRelationWithDefaultModel);
        $this->assertEquals('default', $model->testRelationWithDefaultModel->content);
    }
}
