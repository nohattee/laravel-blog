<?php

namespace Tests\Unit\UseCases;

use Tests\TestCase;
use App\Models\PostCategory;
use App\UseCases\PostCategoryUseCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class PostCategoryUseCaseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_create_post_category_with_parent_id()
    {
        $parent = PostCategory::factory()->create();
        $input = PostCategory::factory()->raw([
            "parent_id" => $parent->id
        ]);
        $uc = new PostCategoryUseCase(new PostCategory());
        $postCategory = $uc->create($input);
        $this->assertDatabaseHas('post_categories', ["id" => $postCategory->id]);
        $this->assertDatabaseHas('tree_paths', [
            'ancestor_id' => $parent->id,
            'descendant_id' => $postCategory->id,
            'entity_type' => PostCategory::class,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_create_post_category_without_parent_id()
    {
        $input = [
            'name' => $this->faker->word,
            'description' => $this->faker->text(),
        ];
        $uc = new PostCategoryUseCase(new PostCategory());
        $postCategory = $uc->create($input);
        $this->assertDatabaseHas('post_categories', ["id" => $postCategory->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_create_post_category_with_parent_id_has_null_value()
    {
        $input = PostCategory::factory()->raw([
            "parent_id" => null,
        ]);
        $uc = new PostCategoryUseCase(new PostCategory());
        $postCategory = $uc->create($input);
        $this->assertDatabaseHas('post_categories', ["id" => $postCategory->id]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_will_be_error_if_parent_id_does_not_exist()
    {
        $this->expectException(ValidationException::class);
        $randomID = -1;
        $input = PostCategory::factory()->raw([
            "parent_id" => $randomID,
        ]);
        $uc = new PostCategoryUseCase(new PostCategory());
        $uc->create($input);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_update_post_category_with_parent_id()
    {
        $parent = PostCategory::factory()->create();
        $postCategory = PostCategory::factory()->create([
            "parent_id" => null,
        ]);

        $input = PostCategory::factory()->raw([
            "parent_id" => $parent->id,
        ]);

        $uc = new PostCategoryUseCase(new PostCategory());
        $uc->update($input, $postCategory);
        $this->assertDatabaseHas('tree_paths', [
            'ancestor_id' => $parent->id,
            'descendant_id' => $postCategory->id,
            'entity_type' => PostCategory::class,
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_update_post_category_without_parent_id()
    {
        $parent = PostCategory::factory()->create();
        $postCategory = PostCategory::factory()->create([
            "parent_id" => null,
        ]);

        $input = PostCategory::factory()->raw([
            "parent_id" => $parent->id,
        ]);

        $uc = new PostCategoryUseCase(new PostCategory());
        $uc->update($input, $postCategory);
        $this->assertDatabaseHas('tree_paths', [
            'ancestor_id' => $parent->id,
            'descendant_id' => $postCategory->id,
            'entity_type' => PostCategory::class,
        ]);
    }
}
