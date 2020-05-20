<?php
namespace Nickolaspz\GraphQL;

use PHPUnit\Framework\TestCase;

class CRMTest extends TestCase
{
    /**
     * @var CRM
     */
    private $crm;

    protected function setUp()
    {
        $this->crm = new CRM;
    }

    public function testQuery()
    {
        $variables = [];

        $body = 'query {
            table(
                name:"account", 
                filter: [
                    {
                        table: "account",
                        left: "name",
                        right: "%quebec inc%",
                        expression: "iLike"
                    }
                ], 
                limit: 5
            )
        }';

        $response = $this->crm->exec($body, $variables);

        \var_dump($response);
    }
}