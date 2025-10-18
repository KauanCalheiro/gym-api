<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class WorkoutTest extends TestCase
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new Workout();
        $this->route = 'workout';
        $this->table = $this->model->getTable();
    }

    public function test_listagem_com_paginacao()
    {
        $response = $this->getJson(route("{$this->route}.index"));

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_listagem_com_paginacao_e_filtros()
    {
        $response = $this->getJson(
            route("{$this->route}.index", ['filter[name]' => 'treino']),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(
            route("{$this->route}.index", ['filter[invalid_field]' => 'test']),
        );

        $response->assertStatus(400);
    }

    public function test_exibe_registro_existente()
    {
        $user    = User::factory()->create();
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $response = $this->getJson(route("{$this->route}.show", $workout->id));

        $response->assertStatus(200)
            ->assertJsonFragment($workout->toArray());
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404);
    }

    public function test_cria_registro()
    {
        $user = User::factory()->create();

        $data = [
            'name'        => 'Treino A - Peito e Tríceps',
            'user_id'     => $user->id,
            'description' => 'Treino focado em peitorais e tríceps',
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas($this->table, $data);
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = [
            'name'    => '',
            'user_id' => null,
        ];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'user_id']);
    }

    public function test_atualiza_registro()
    {
        $user    = User::factory()->create();
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino B',
            'description' => 'Treino focado em costas',
        ]);

        $data = [
            'name'        => 'Treino B - Costas e Bíceps Atualizado',
            'description' => 'Descrição atualizada',
        ];

        $response = $this->putJson(route("{$this->route}.update", $workout->id), $data);

        $workout->refresh();

        $response->assertStatus(200)
            ->assertJsonFragment($workout->toArray());

        $this->assertDatabaseHas($this->table, $workout->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $user    = User::factory()->create();
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino B',
            'description' => 'Treino focado em costas',
        ]);

        $data = [
            'user_id' => -1,
        ];

        $response = $this->putJson(route("{$this->route}.update", $workout->id), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_deleta_registro()
    {
        $user    = User::factory()->create();
        $workout = Workout::create([
            'user_id'     => $user->id,
            'name'        => 'Treino A',
            'description' => 'Treino focado em peitorais',
        ]);

        $response = $this->deleteJson(route("{$this->route}.destroy", $workout->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, [
            'id' => $workout->id,
        ]);
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404);
    }
}
