<?php

namespace Tests\Feature;

use App\Models\Location\Country;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Contracts\SearchTestContract;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonError;
use Tests\Helpers\JsonPagination;
use Tests\Helpers\JsonValidationError;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class CountryTest extends TestCase implements CrudTestContract, SearchTestContract
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new Country();
        $this->route = 'country';
        $this->table = $this->model->getTable();

        $this->authenticate(JwtApiAuthenticatable::class);
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
            route(
                "{$this->route}.index",
                [
                    'filter[name]' => 'BRASIL',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'name' => 'BRASIL',
                'code' => 'BRA',
            ]);
    }

    public function test_listagem_com_search()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'filter[search]' => 'BRASIL',
                ],
            ),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE)
            ->assertJsonFragment([
                'name' => 'BRASIL',
                'code' => 'BRA',
            ]);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
        $response = $this->getJson(
            route(
                "{$this->route}.index",
                [
                    'filter[inexistente]' => 'inexistente',
                ],
            ),
        );

        $response->assertStatus(400)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_exibe_registro_existente()
    {
        $first = $this->model->first();

        if (!$first) {
            $this->markTestSkipped('No records found in the model.');
        }

        $response = $this->getJson(route("{$this->route}.show", $first->id));

        $response->assertStatus(200)
            ->assertJson($first->toArray());
    }

    public function test_exibe_registro_inexistente()
    {
        $response = $this->getJson(route("{$this->route}.show", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }

    public function test_cria_registro()
    {
        $country = Country::factory()->make();

        $response = $this->postJson(route("{$this->route}.store"), $country->toArray());

        $response->assertCreated()
                 ->assertJsonFragment($country->toArray());

        $this->assertDatabaseHas($this->table, $country->toArray());
    }

    public function test_erro_cria_registro_com_campos_incorretos()
    {
        $data = ['name' => 'URUGUAY', 'code' => 'TOO_LONG'];

        $response = $this->postJson(route("{$this->route}.store"), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['code']);
    }

    public function test_atualiza_registro()
    {
        $country        = Country::factory()->create();
        $updatedCountry = Country::factory()->make();

        $response = $this->putJson(route("{$this->route}.update", $country->id), $updatedCountry->toArray());

        $response->assertStatus(200)
            ->assertJsonFragment($updatedCountry->toArray());

        $this->assertDatabaseHas($this->table, $updatedCountry->toArray());
    }

    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
        $country = Country::factory()->create();

        $data = [
            'name' => $country->name,
            'code' => 'TOO_LONG',
        ];

        $response = $this->putJson(route("{$this->route}.update", $country->id), $data);

        $response->assertStatus(422)
            ->assertJsonStructure(JsonValidationError::STRUCTURE)
            ->assertJsonValidationErrors(['code']);
    }

    public function test_deleta_registro()
    {
        $country = Country::factory()->create();

        $response = $this->deleteJson(route("{$this->route}.destroy", $country->id));

        $response->assertStatus(204);

        $this->assertSoftDeleted($this->table, $country->toArray());
    }

    public function test_erro_deleta_registro_inexistente()
    {
        $response = $this->deleteJson(route("{$this->route}.destroy", -1));

        $response->assertStatus(404)
            ->assertJsonStructure(JsonError::STRUCTURE);
    }
}
