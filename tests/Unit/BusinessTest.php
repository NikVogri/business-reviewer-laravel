<?php

namespace Tests\Unit;

use App\User;
use App\Review;
use App\Business;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_an_owner()
    {
        $this->withoutExceptionHandling();
        $business = factory(Business::class)->create();
        $this->assertInstanceOf(User::class, $business->owner);
    }

    public function test_it_checks_if_authenticated_user_is_owner()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $business = factory(Business::class)->create(['owner_id' => auth()->id()]);

        $isOwner = $business->amOwner($user);

        $this->assertTrue($isOwner);
    }

    public function test_it_can_add_reviews()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $business = factory(Business::class)->create();

        $business->addReview('A review', 4);

        $this->assertDatabaseHas(
            'reviews',
            ['body' => 'A review']
        );
    }

    public function test_it_can_add_categories()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $business = factory(Business::class)->create();
        $category =  factory(Category::class)->create();

        $business->addCategory($category->id);

        $this->assertDatabaseHas('business_category', ['category_id' => $category->id, 'business_id' => $business->id]);
    }

    public function test_it_has_a_path()
    {
        $business = factory(Business::class)->create();

        $this->assertEquals($business->path(), '/businesses/' . $business->slug);
    }

    public function test_it_has_an_image_path()
    {
        $business = factory(Business::class)->create();

        $this->assertEquals($business->image(),   'storage/' . $business->front_image);
    }

    public function test_it_checks_if_a_user_already_reviewed()
    {
        $this->signIn();
        $business = factory(Business::class)->create();

        $this->assertFalse($business->reviewedAlready());

        $business->addReview('I am a review', 2);

        $this->assertTrue($business->reviewedAlready());
    }
}