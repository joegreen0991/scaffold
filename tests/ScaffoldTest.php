<?php

use Mockery as m;


class ScaffoldTest extends \PHPUnit_Framework_TestCase {
    
    const TEST_TABLE = 'testtable';
    
    public function tearDown()
    {
        m::close();
    }
    
    public function mock($mockBuilder = null, $loadPrimaryKey = false)
    {
        $mock = m::mock('Illuminate\Database\Connection');

        $mockExpectation = $mock->shouldReceive('table')
               ->with(self::TEST_TABLE)
               ->once();
        
        $mockBuilder and $mockExpectation->andReturn($mockBuilder);
                
        if($loadPrimaryKey)
        {
            $mockExpectation->shouldReceive('select')
                            ->atLeast()
                            ->once()
                            ->with('SHOW COLUMNS FROM ' . self::TEST_TABLE)
                            ->andReturn(array(
                                array('Field' => 'column1', 'Type' => 'int(11) unsigned', 'Key' => 'PRI'),
                                array('Field' => 'column2', 'Type' => 'timestamp', 'Key' => 'PRI'),
                                array('Field' => 'column3', 'Type' => 'varchar(255)', 'Key' => '')
                            ));
        }

         return $mockExpectation->getMock();
    }

    public function testFieldsCanBeIgnored()
    {
       
        $mockBuilder = m::mock('Illuminate\Database\Query\Builder');
        
        $mockBuilder->shouldReceive('select')
                ->with(array('column1','column2','field1',))
                ->once()
                ->andReturn($mockBuilder);
        
        $mockDb = $this->mock($mockBuilder, true);
        
        $obj = new Scaffold($mockDb, self::TEST_TABLE);
        
        $obj->addElements(array(
            'field1' => array(
                
            ),
            'field2' => array(
                'select' => false
            )
        ));
        
        $this->assertEquals(2, count($obj->getElements()));
        
        $this->assertEquals(array('field1','field2'), array_keys($obj->getElements()));
        
        $this->assertInstanceOf('Illuminate\Database\Query\Builder', $obj->search());
    }
    
    
    public function testFieldsCanBeLimited()
    {
        $mockBuilder = m::mock('Illuminate\Database\Query\Builder');

        $mockBuilder->shouldReceive('select')
                    ->with(array('column1','column2','field1'))
                    ->once()
                    ->andReturn($mockBuilder);
        
        $mockBuilder->shouldReceive('where')
                    ->withArgs(array('column1', '=', 1))
                    ->once();
        
        $mockBuilder->shouldReceive('where')
                    ->withArgs(array('column2', '=', 2))
                    ->once();
        
        
        
        $mockDb = $this->mock($mockBuilder, true);
        
        
        $obj = new Scaffold($mockDb, self::TEST_TABLE);
        
        $obj->addElements(array(
            'field1' => array(
                
            ),
            'field2' => array(
                'select' => false
            )
        ));
        
        $this->assertInstanceOf('Illuminate\Database\Query\Builder', $obj->find(array(1, 2)));
    }
    
    
    public function testFieldsCanBeSearched()
    {
        $mockBuilder = m::mock('Illuminate\Database\Query\Builder');

        $mockBuilder->shouldReceive('select')
                    ->with(array('column1','column2','field1','field2',))
                    ->once()
                    ->andReturn($mockBuilder);
        
        $mockBuilder->shouldReceive('where')
                    ->withArgs(array('field1', '=' , '1234'))
                    ->once();
        
        $mockBuilder->shouldReceive('where')
                    ->withArgs(array('field2', 'LIKE' , '4321'))
                    ->once();

        
        $mockDb = $this->mock($mockBuilder, true);
        
        
        
        $obj = new Scaffold($mockDb, self::TEST_TABLE);
        
        $obj->addElements(array(
            'field1' => array(
                
            ),
            'field2' => array(

            )
        ));
        
        $this->assertInstanceOf('Illuminate\Database\Query\Builder', $obj->search(array(
            array('field1', '=' , '1234'),
            array('field2', 'LIKE' , '4321')
        )));
    }
    
    public function testFieldsCanBeSaved()
    {
        $mockBuilder = m::mock('Illuminate\Database\Query\Builder');

        $mockBuilder->shouldReceive('insertGetId')
                    ->with(array(
                        'field1' => 'blah',
                        'field2' => 'blah',
                    ))
                    ->once();
        
        $mockDb = $this->mock($mockBuilder, true);
        
        $obj = new Scaffold($mockDb, self::TEST_TABLE);
        
        $obj->addElements(array(
            'field1' => array(
                
            ),
            'field2' => array(

            )
        ));
        
        $obj->insert(array(
            'field1' => 'blah',
            'field2' => 'blah'
        ));
    }
    
    public function testFieldsCanBeUpdated()
    {
        $mockBuilder = m::mock('Illuminate\Database\Query\Builder');

        $mockBuilder->shouldReceive('update')
                    ->with(array(
                        'field1' => 'blah',
                        'field2' => 'blah'
                    ))
                    ->once();
        
        $mockBuilder->shouldReceive('select', 'where')
                    ->atLeast()
                    ->once()
                    ->withAnyArgs()
                    ->andReturn($mockBuilder);
        
        $mockDb = $this->mock($mockBuilder, true);
        
        $obj = new Scaffold($mockDb, self::TEST_TABLE);
        
        $obj->addElements(array(
            'field1' => array(
                
            ),
            'field2' => array(

            )
        ));
        
        $obj->update(array(
            'field1' => 'blah',
            'field2' => 'blah',
            'column1' => '1',
            'column2' => '2'
        ));
    }
}