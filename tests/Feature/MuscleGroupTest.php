<?php

namespace Tests\Feature;

use App\Models\MuscleGroup;
use Illuminate\Database\Eloquent\Model;
use Tests\Contracts\CrudTestContract;
use Tests\Helpers\Auth\JwtApiAuthenticatable;
use Tests\Helpers\JsonPagination;
use Tests\TestCase;
use Tests\Trait\Authenticatable;

class MuscleGroupTest extends TestCase
    // implements CrudTestContract
{
    use Authenticatable;

    protected Model $model;
    protected string $table;
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticate(JwtApiAuthenticatable::class);

        $this->model = new MuscleGroup();
        $this->route = 'muscle-group';
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
            route("{$this->route}.index", ['filter[name]' => 'peito']),
        );

        $response->assertStatus(200)
            ->assertJsonStructure(JsonPagination::STRUCTURE);
    }

    public function test_erro_listagem_com_filtros_incorretos()
    {
    }
    public function test_exibe_registro_existente()
    {
    }
    public function test_exibe_registro_inexistente()
    {
    }
    public function test_cria_registro()
    {
    }
    public function test_erro_cria_registro_com_campos_incorretos()
    {
    }
    public function test_atualiza_registro()
    {
    }
    public function test_erro_atualiza_registro_com_campos_incorretos()
    {
    }
    public function test_deleta_registro()
    {
    }
    public function test_erro_deleta_registro_inexistente()
    {
    }
}
