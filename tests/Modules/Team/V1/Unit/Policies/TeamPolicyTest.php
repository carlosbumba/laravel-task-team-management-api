<?php

use Auth\Infrastructure\Persistence\Model\User;
use Team\Infrastructure\Persistence\Model\Team;
use Team\Policies\TeamPolicy;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->policy = new TeamPolicy();
    $this->admin = User::factory()->make(['role' => 'admin']);
    $this->manager = User::factory()->make(['role' => 'manager']);
    $this->member = User::factory()->make(['role' => 'member']);
    $this->team = Team::factory()->create();
    $this->team->setRelation('members', collect([$this->manager, $this->member]));
});

it('permite admin ou manager ver todas as equipes', function () {
    expect($this->policy->viewAny($this->admin))->toBeTrue();
    expect($this->policy->viewAny($this->manager))->toBeTrue();
    expect($this->policy->viewAny($this->member))->toBeFalse();
});

it('permite admin, manager ou membro ver equipe específica', function () {
    expect($this->policy->view($this->admin, $this->team))->toBeTrue();
    expect($this->policy->view($this->manager, $this->team))->toBeTrue();
    expect($this->policy->view($this->member, $this->team))->toBeFalse();

    $outsider = User::factory()->make(['role' => 'member']);
    expect($this->policy->view($outsider, $this->team))->toBeFalse();
});

it('permite apenas admin ou manager criar equipes', function () {
    expect($this->policy->create($this->admin))->toBeTrue();
    expect($this->policy->create($this->manager))->toBeTrue();
    expect($this->policy->create($this->member))->toBeFalse();
});

it('permite admin ou manager atualizar equipe', function () {
    expect($this->policy->update($this->admin, $this->team))->toBeTrue();
    expect($this->policy->update($this->manager, $this->team))->toBeTrue();
    expect($this->policy->update($this->member, $this->team))->toBeFalse();
});

it('permite apenas admin deletar equipe', function () {
    expect($this->policy->delete($this->admin, $this->team))->toBeTrue();
    expect($this->policy->delete($this->manager, $this->team))->toBeFalse();
    expect($this->policy->delete($this->member, $this->team))->toBeFalse();
});

it('permite admin ou manager adicionar membros', function () {
    expect($this->policy->addMember($this->admin, $this->team))->toBeTrue();
    expect($this->policy->addMember($this->manager, $this->team))->toBeTrue();
    expect($this->policy->addMember($this->member, $this->team))->toBeFalse();
});

it('impede manager de se remover da equipe', function () {
    expect($this->policy->removeMember($this->manager, $this->team, $this->manager))->toBeFalse();
});

it('permite admin remover qualquer membro', function () {
    expect($this->policy->removeMember($this->admin, $this->team, $this->member))->toBeTrue();
});

it('impede manager remover outro membro', function () {
    expect($this->policy->removeMember($this->manager, $this->team, $this->member))->toBeFalse();
});

it('impede member de remover membros', function () {
    expect($this->policy->removeMember($this->member, $this->team, $this->admin))->toBeFalse();
});
