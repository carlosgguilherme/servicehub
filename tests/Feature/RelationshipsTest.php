<?php

use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('company has many projects', function () {
    $c = Company::factory()->create();
    Project::factory()->count(2)->create(['company_id' => $c->id]);

    expect($c->projects)->toHaveCount(2);
});

test('project belongs to company', function () {
    $p = Project::factory()->create();

    expect($p->company)->not->toBeNull()
        ->and($p->company->id)->toBe($p->company_id);
});

test('project has many tickets', function () {
    $p = Project::factory()->create();
    Ticket::factory()->count(3)->create(['project_id' => $p->id]);

    expect($p->tickets)->toHaveCount(3);
});

test('ticket belongs to project', function () {
    $t = Ticket::factory()->create();

    expect($t->project)->not->toBeNull()
        ->and($t->project->id)->toBe($t->project_id);
});

test('ticket has one ticketDetail', function () {
    $t = Ticket::factory()->create();
    $d = TicketDetail::factory()->create(['ticket_id' => $t->id]);

    $t->refresh();

    expect($t->detail)->not->toBeNull()
        ->and($t->detail->id)->toBe($d->id);
});

test('user has one userProfile', function () {
    $u = User::factory()->create();
    $p = UserProfile::factory()->create(['user_id' => $u->id]);

    $u->refresh();

    expect($u->profile)->not->toBeNull()
        ->and($u->profile->id)->toBe($p->id);
});
