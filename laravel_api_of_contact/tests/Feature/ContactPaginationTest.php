<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_contacts_use_requested_page_and_limit(): void
    {
        foreach (["Alice", "Beatrice", "Cecilia", "Dorian", "Elena"] as $name) {
            Contact::create([
                "name" => $name,
                "email" => strtolower($name) . "@example.com",
                "designation" => "Engineer",
                "contact_no" => "555-0100",
            ]);
        }

        $response = $this->getJson("/api/contacts?page=2&limit=2");

        $response->assertOk()
            ->assertJsonPath("contacts.current_page", 2)
            ->assertJsonPath("contacts.per_page", 2)
            ->assertJsonPath("contacts.total", 5)
            ->assertJsonPath("contacts.data.0.name", "Cecilia")
            ->assertJsonPath("contacts.data.1.name", "Dorian");
    }

    public function test_search_filters_all_contact_fields_before_paginating(): void
    {
        Contact::create([
            "name" => "Needle Name",
            "email" => "name@example.com",
            "designation" => "Engineer",
            "contact_no" => "555-0101",
        ]);
        Contact::create([
            "name" => "Email Match",
            "email" => "needle@example.com",
            "designation" => "Engineer",
            "contact_no" => "555-0102",
        ]);
        Contact::create([
            "name" => "Phone Match",
            "email" => "phone@example.com",
            "designation" => "Engineer",
            "contact_no" => "needle-0103",
        ]);
        Contact::create([
            "name" => "Role Match",
            "email" => "role@example.com",
            "designation" => "Needle Specialist",
            "contact_no" => "555-0104",
        ]);
        Contact::create([
            "name" => "No Match",
            "email" => "other@example.com",
            "designation" => "Designer",
            "contact_no" => "555-0105",
        ]);

        $response = $this->getJson("/api/contacts?search=needle&page=2&limit=2");

        $response->assertOk()
            ->assertJsonPath("contacts.current_page", 2)
            ->assertJsonPath("contacts.per_page", 2)
            ->assertJsonPath("contacts.total", 4)
            ->assertJsonPath("contacts.data.0.name", "Phone Match")
            ->assertJsonPath("contacts.data.1.name", "Role Match");
    }

    public function test_contacts_can_be_ordered_by_a_requested_column_and_direction(): void
    {
        foreach (["Alice", "Cecilia", "Beatrice"] as $name) {
            Contact::create([
                "name" => $name,
                "email" => strtolower($name) . "@example.com",
                "designation" => "Engineer",
                "contact_no" => "555-0100",
            ]);
        }

        $response = $this->getJson("/api/contacts?orderBy=name&orderDirection=desc");

        $response->assertOk()
            ->assertJsonPath("contacts.data.0.name", "Cecilia")
            ->assertJsonPath("contacts.data.1.name", "Beatrice")
            ->assertJsonPath("contacts.data.2.name", "Alice");
    }

    public function test_contacts_reject_unsupported_ordering_values(): void
    {
        $this->getJson("/api/contacts?orderBy=password")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(["orderBy"]);

        $this->getJson("/api/contacts?orderBy=name&orderDirection=random")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(["orderDirection"]);
    }
}