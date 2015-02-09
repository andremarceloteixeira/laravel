<?php

class ClientTest extends TestCase {

    /**
     * The array with the fake data for creating
     *
     * @var array
     */
    public $createData = [
        'email' => 'test123e@cliente.com',
        'name' => 'Allianz',
        'password' => '12345',
        'password_confirmation' => '12345',
        'address' => 'Rua do sitio, 444, Vila Nova de Gaia',
        'zipcode' => '4470-123',
        'reference' => '12345678',
        'city' => 'Vila Nova de Gaia',
        'nif' => '1234567',
        'asd' => 'ads',
        'country_id' => '188',
        'code' => '3',
    ];

    /**
     * The array with the fake data for updating
     *
     * @var array
     */
    public $updateData = [
        'email' => 'teste2@cliente2.com',
        'name' => 'Risen',
        'address' => 'Rua dos Benguiados, 254, Vila do Conde',
        'zipcode' => '4470-123',
        'reference' => '12345678',
        'nif' => '1234567',
        'country_id' => '188',
    ];
    
    public $find_id = 1;
    
    public function setUp() {
        parent::setUp();
        Eloquent::reguard();
    }

    /**
     * Tests the creating of a new client
     *
     * @return void
     */
    public function testCreate() {
        Eloquent::reguard();
        
        /* Checks if the rules for validation are working fine */
        $v = Validator::make($this->createData, Client::$rules);
        $this->assertTrue($v->passes());

        /* Check if the client was created successfully, with his user credentials */ 
        $c = Client::create($this->createData);
        $this->assertEquals($this->createData['email'], $c->email);
        $this->assertTrue(Hash::check($this->createData['password'], $c->user->password));
        $this->assertEquals($this->createData['name'], $c->name);
        $this->assertEquals(1, $c->user->role_id);
        $this->assertEquals($this->createData['address'], $c->address);
        $this->assertEquals($this->createData['city'], $c->city);
        $this->assertEquals($this->createData['zipcode'], $c->zipcode);
        $this->assertEquals($this->createData['reference'], $c->reference);
        $this->assertEquals($this->createData['nif'], $c->nif);
        $this->assertEquals($this->createData['country_id'], $c->country_id);
    }

    /**
     * Tests the updating of a client
     *
     * @return void
     */
    public function testUpdate() {
        $c = Client::create($this->createData);
        $c->update($this->updateData);

        $this->assertEquals($this->updateData['email'], $c->user->email);
        $this->assertEquals($this->updateData['name'], $c->user->name);
        $this->assertEquals($this->updateData['address'], $c->address);
        $this->assertEquals($this->updateData['zipcode'], $c->zipcode);
        $this->assertEquals($this->updateData['reference'], $c->reference);
        $this->assertEquals($this->updateData['nif'], $c->nif);
        $this->assertEquals($this->updateData['country_id'], $c->country_id);
    }
    
    /**
     * Tests the updating of a client
     *
     * @return void
    */
    public function testDelete() {
        $c = Client::create($this->createData);
        
        $this->assertEquals(null, $c->delete());
    }

}
