<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_note_record()
    {
        // Mock data for the note
        $data = [
            'title' => 'Test Note',
            'description' => 'This is a description for the test note.'
        ];

        // Send a POST request to create the note
        $response = $this->postJson('/api/note', $data);

        // Check if the note was created successfully
        $response->assertStatus(201);
    }

    public function test_it_can_list_notes()
    {
        Note::factory()->count(15)->create();

        $response = $this->getJson('/api/note');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data'); // Assuming 'data' holds the notes array
    }

    public function test_it_can_update_note_record()
    {
        $note = Note::factory()->create([
            'title' => 'Old Title',
            'description' => 'Old description'
        ]);

        $data = [
            'title' => 'NEW Updated Title',
            'description' => 'NEW Updated description'
        ];

        $response = $this->putJson("/api/note/{$note->id}", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('notes', $data);
    }

    public function test_it_can_delete_note_record()
    {
        $note = Note::factory()->create();

        $response = $this->deleteJson("/api/note/{$note->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
