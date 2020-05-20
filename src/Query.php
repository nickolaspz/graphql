<?php
namespace Nickolaspz\GraphQL;

class Query 
{
    private $table, $type;

    private $limit = 10;

    private $values = [];

    private $conditions = [];

    private $var_types = ['String', 'MultiTypeInput'];

    private $comparators = ['=', '>=', '<=', '>', '<', '!=', 'like', 'ilike'];

    function __construct()
    {
        return $this;
    }

    public function find($table)
    {
        $this->table = $table;

        $this->type = 'query';

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function filter($kwargs)
    {
        // if ($arg3 == null) {
        //     $this->conditions[] = [$arg1, '=', $arg2];
        // } else {
        //     if (!in_array(strtolower($arg2), $this->comparators)) {
        //         throw new \InvalidArgumentException($arg2 . 
        //             ' is not an accepted comparable symbol: ' . 
        //             implode(', ', $this->comparators)
        //         );
        //     }
        //     $this->conditions[] = [$arg1, $arg2, $arg3];
        // }

        return $this;
    }

    public function get()
    {
        $body = '' . $this->type;

        if (count($this->conditions) > 0 || count($this->values) > 0) {
            $body .= $this->getVariables();
        }

        $body .= '{';

        switch ($this->type) {
            case 'update':
                $body .= '
                    updateRow(table: "';
            case 'create':
                $body .= '
                    createRow(table: "';
            default:
                $body .= '
                    table(name: "';
        }

        $body .= $this->table . '" ' . ' limit: ' . $this->limit;

        if (count($this->conditions) > 0) {
            $body .= ', filter: [';
            
            foreach ($this->conditions as $condition) {
                $body .= '{ ';
            }
        }

        dd($body);

        // 'query($username: String) {
        //     table(name: "propertyuser__c", limit: 1, 
        //         filter: [
        //         {
        //             table: "propertyuser__c",
        //             left: "username__c",
        //             right: $username,
        //             expression: "="
        //         }
        //     ])
        // }'

        // 'mutation($id: String!, $name : MultiTypeInput, $role__c : MultiTypeInput, $password__c : MultiTypeInput) {
        //     updateRow(table:"propertyuser__c", id: $id, values: [
        //         {
        //             property: "name",
        //             value: $name
        //         },
        //         {
        //             property: "role__c",
        //             value: $role__c
        //         },
        //         {
        //             property: "password__c",
        //             value: $password__c
        //         }
        //     ])
        // }'
    }

    private function getVariables()
    {
        $variables = '(';

        if ($this->type == 'query') {
            foreach ($this->conditions as $condition) {
                $variables .= '$' . $condition[0] . ': ' . 'String';
            }
        }

        return $variables . ')';
    }
}