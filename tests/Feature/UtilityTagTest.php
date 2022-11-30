<?php

namespace Tests\Feature;

use Corals\Modules\Utility\Tag\Models\Tag;
use Corals\Settings\Facades\Modules;
use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UtilityTagTest extends TestCase
{
    use DatabaseTransactions;

    protected $tag;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);

    }

    public function test_utility_tag_store()
    {
        $modules = [
            'Classified' => 'corals-classified',
            'TroubleTicket' => 'corals-trouble-ticket',
            'Directory' => 'corals-directory',
            'Marketplace' => 'corals-marketplace',
            'CMS' => 'corals-cms',
        ];

        $tags = ['palestine', 'brazil', 'france', 'turkey', 'australia'];
        $active = false;
        foreach ($modules as $module => $code) {
            if (Modules::isModuleActive($code)) {
                $active = true;
                $tag = array_rand($tags);
                $response = $this->post('utilities/tags', [
                    "name" => $tags[$tag],
                    "slug" => $tags[$tag],
                    "module" => $module,
                    "status" => "active",
                ]);

                $this->tag = Tag::query()->first();

                $response->assertDontSee('The given data was invalid')
                    ->assertRedirect('utilities/tags');

                $this->assertDatabaseHas('utility_tags', [
                    "name" => $this->tag->name,
                    "slug" => $this->tag->slug,
                    "module" => $this->tag->module,
                    "status" => $this->tag->status,
                ]);
            }
        }

        if (!$active) {
            $tag = array_rand($tags);
            $response = $this->post('utilities/tags', [
                "name" => $tags[$tag],
                "slug" => $tags[$tag],
                "module" => null,
                "status" => "active",
            ]);
            $this->tag = Tag::query()->first();

            $response->assertDontSee('The given data was invalid')
                ->assertRedirect('utilities/tags');

            $this->assertDatabaseHas('utility_tags', [
                "name" => $this->tag->name,
                "slug" => $this->tag->slug,
                "module" => $this->tag->module,
                "status" => $this->tag->status,
            ]);
        }
    }

    public function test_utility_tag_edit()
    {
        $this->test_utility_tag_store();

        if ($this->tag) {
            $response = $this->get('utilities/tags/' . $this->tag->hashed_id . '/edit');

            $response->assertViewIs('utility-tag::tags.create_edit')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_utility_tag_update()
    {
        $this->test_utility_tag_store();

        if ($this->tag) {
            $response = $this->put('utilities/tags/' . $this->tag->hashed_id, [
                "name" => $this->tag->name,
                "slug" => $this->tag->slug,
                "status" => 'inactive']);

            $response->assertRedirect('utilities/tags');
            $this->assertDatabaseHas('utility_tags', [
                "name" => $this->tag->name,
                "slug" => $this->tag->slug,
                "module" => $this->tag->module,
                "status" => 'inactive',
            ]);
        }
        $this->assertTrue(true);
    }

    public function test_utility_tag_delete()
    {
        $this->test_utility_tag_store();

        if ($this->tag) {
            $response = $this->delete('utilities/tags/' . $this->tag->hashed_id);

            $response->assertStatus(200)->assertSeeText('Tag has been deleted successfully.');

            $this->assertDatabaseMissing('utility_tags', [
                "name" => $this->tag->name,
                "slug" => $this->tag->slug,
                "module" => $this->tag->module,
                "status" => $this->tag->status]);
        }
        $this->assertTrue(true);
    }
}
