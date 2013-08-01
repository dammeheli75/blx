<?php
namespace Blx\Db\TableGateway;

use Zend\Db\TableGateway\TableGatewayInterface as ZendTableGatewayInterface;

interface TableGatewayInterface extends ZendTableGatewayInterface {
    public function count($conditions = null);
}