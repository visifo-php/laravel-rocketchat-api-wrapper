<?php
declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Roles\Role;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Roles extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function create(string $name, string $scope, ?string $description = null): Role
    {
        $this->checkEmptyString($name);
        $this->checkEmptyString($scope);

        if(is_null($description))
            unset($description);
        else{
            $this->checkEmptyString($description);
        }

        $data = get_defined_vars();
        $response = $this->rocket->post("roles.create", $data);

        return Deserializer::deserialize($response, Role::class);
    }
}
