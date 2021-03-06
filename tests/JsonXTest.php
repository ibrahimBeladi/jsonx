<?php
use PHPUnit\Framework\TestCase;
use jsonx\JsonX;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;

class JsonXTest extends TestCase{
    /**
     * @test
     */
    public function testAdd00() {
        $j = new JsonX();
        $this->assertTrue($j->add('a-string', 'This is a string.'));
        $this->assertTrue($j->add('string-as-bool-1', 'NO',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-2', -1,array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-3', 0,array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-4', 1,array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-5', 't',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-6', 'Yes',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-not-as-bool', 'Yes'));
        $this->assertTrue($j->add('null-value', null));
        $this->assertTrue($j->add('infinity', INF));
        $this->assertTrue($j->add('not-a-number', INF));
    }
    /**
     * @test
     */
    public function testAdd01() {
        $j = new JsonX();
        $this->assertTrue($j->add('string-as-bool-1', 'NO',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-2', 'No',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-3', 'false',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-4', 'on',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-5', 't',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-as-bool-6', 'Yes',array('string-as-boolean'=>true)));
        $this->assertTrue($j->add('string-not-as-bool', 'Yes'));
        $this->assertEquals('{"string-as-bool-1":false, '
                . '"string-as-bool-2":false, '
                . '"string-as-bool-3":false, '
                . '"string-as-bool-4":true, '
                . '"string-as-bool-5":true, '
                . '"string-as-bool-6":true, '
                . '"string-not-as-bool":"Yes"}',$j.'');
        $this->assertTrue($j->add('string-as-bool-6', 'False',array('string-as-boolean'=>true)));
        $this->assertEquals('{"string-as-bool-1":false, '
                . '"string-as-bool-2":false, '
                . '"string-as-bool-3":false, '
                . '"string-as-bool-4":true, '
                . '"string-as-bool-5":true, '
                . '"string-as-bool-6":false, '
                . '"string-not-as-bool":"Yes"}',$j.'');
    }
    /**
     * @test
     */
    public function testAdd03() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = array(
            'hello'=>'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            array(4,1.7,true,null),
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            array(array(new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4'))));
        $j->add('big-array', $arr);
        $this->assertEquals('{'
                . '"big-array":[{"hello":"world"}, '
                . '{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                . '[4, 1.7, true, null], '
                . '{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                . '{"test":true}, '
                . '[[{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                . '{"test":true}, '
                . '{"property-00":"p0", "property-01":"p1", "property-02":"p2"}]]]'
                . '}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd04() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = array(
            'hello'=>'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            array(4,1.7,true,null),
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            array(array(new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4'))));
        $j->add('big-array', $arr,array('array-as-object'=>true));
        $this->assertEquals('{'
                . '"big-array":{"hello":"world", '
                . '"0":{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                . '"1":{"0":4, "1":1.7, "2":true, "3":null}, '
                . '"2":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                . '"3":{"test":true}, '
                . '"4":{"0":{"0":{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                . '"1":{"test":true}, '
                . '"2":{"property-00":"p0", "property-01":"p1", "property-02":"p2"}}}}'
                . '}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd05() {
        $j = new JsonX();
        $this->assertFalse($j->add('  ',null));
        $this->assertTrue($j->add('null-value',null));
        $this->assertEquals('{"null-value":null}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd07() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = array(
            'hello'=>'world',
            'null'=>null,
            'boolean'=>true,
            'number'=>665,
            'str-as-bool'=>'f',
            'object-0'=>new Obj0('Nice', 'To', 99, INF, NAN),
            'array-0'=>array(4,1.7,true,null,'t','f'),
            'object-1'=>new Obj1('1', 'Hello', 'No', true, false),
            'jsonx-obj'=>$subJ,
            'array-1'=>array(array(new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4'))));
        $j->add('big-array', $arr,array('array-as-object'=>true));
        $this->assertEquals('{'
                . '"big-array":{"hello":"world", '
                . '"null":null, '
                . '"boolean":true, '
                . '"number":665, '
                . '"str-as-bool":false, '
                . '"object-0":{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                . '"array-0":{"0":4, "1":1.7, "2":true, "3":null, "4":true, "5":false}, '
                . '"object-1":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                . '"jsonx-obj":{"test":true}, '
                . '"array-1":{"0":{"0":{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                . '"1":{"test":true}, '
                . '"2":{"property-00":"p0", "property-01":"p1", "property-02":"p2"}}}}'
                . '}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd06() {
        $j = new JsonX();
        $this->assertFalse($j->add('boolean','null',array('string-as-boolean'=>true)));
    }
    /**
     * @test
     */
    public function testAdd08() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = array(
            'hello'=>'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            array(4,1.7,true,null,'t','f'),
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            array(array(new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4'))));
        $j->add('big-array', $arr);
        $this->assertEquals('{'
                . '"big-array":[{"hello":"world"}, '
                . '{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                . '[4, 1.7, true, null, true, false], '
                . '{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                . '{"test":true}, '
                . '[[{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                . '{"test":true}, '
                . '{"property-00":"p0", "property-01":"p1", "property-02":"p2"}]]]'
                . '}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testGetKeyValue00() {
        $j = new JsonX();
        $this->assertNull($j->get('not-exist'));
        $j->add('hello', 'world');
        $j->add('object', new Obj0('8', 7, '6', '5', 4));
        $j->add('null', null);
        $j->add('nan', NAN);
        $j->add('inf', INF);
        $j->add('bool', true);
        $j->add('number', 667);
        $this->assertEquals('"world"',$j->get('  hello  '));
        $this->assertEquals('{"prop-0":"8", "prop-1":7, "prop-2":"6", "prop-3":4}',$j->get('  object  '));
        $this->assertEquals('null',$j->get('null'));
        $this->assertEquals('"NAN"',$j->get('nan'));
        $this->assertEquals('"INF"',$j->get('inf'));
        $this->assertEquals('true',$j->get('bool'));
        $this->assertEquals('667',$j->get('number'));
    }
    /**
     * @test
     */
    public function testAddNumber00() {
        $j = new JsonX();
        $j->addNumber('   number', 33);
        $this->assertEquals('{"number":33}',$j.'');
    }
    /**
     * @test
     */
    public function testAddBoolean00() {
        $j = new JsonX();
        $j->addBoolean('bool ', true);
        $this->assertEquals('{"bool":true}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray00() {
        $j = new JsonX();
        $arr = array();
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray01() {
        $j = new JsonX();
        $arr = array(1,"Hello",true,NAN,null,99.8,INF);
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{"0":1, "1":"Hello", "2":true, "3":"NAN", "4":null, "5":99.8, "6":"INF"}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray02() {
        $j = new JsonX();
        $arr = array(1,1.5,"Hello",true,NAN,null,INF);
        $j->addArray('arr', $arr,false);
        $this->assertEquals('{"arr":[1, 1.5, "Hello", true, "NAN", null, "INF"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray03() {
        $j = new JsonX();
        $arr = array("number"=>1,"Hello"=>"world!","boolean"=>true,NAN,null);
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{"number":1, "Hello":"world!", "boolean":true, "0":"NAN", "1":null}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddObj00() {
        $j = new JsonX();
        $obj = new Obj0('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"prop-0":"Hello", "prop-1":0, "prop-2":true, "prop-3":"he"}}',$j.'');
    }
    
    /**
     * @test
     */
    public function testAddObj01() {
        $j = new JsonX();
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"property-00":"Hello", "property-01":0, "property-02":true}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest00(){
        $j = new JsonX();
        $this->assertFalse($j->addString('', 'Hello World!'));
        $this->assertFalse($j->addString('  ', 'Hello World!'));
        $this->assertFalse($j->addString("\n", 'Hello World!'));
        $this->assertEquals('{}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest01(){
        $j = new JsonX();
        $this->assertTrue($j->addString('hello', 'Hello World!'));
        $this->assertEquals('{"hello":"Hello World!"}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest02(){
        $j = new JsonX();
        $this->assertFalse($j->addString('invalid-boolean', 'falseX',true));
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars00() {
        $str = 'I\'m "Good".';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('I\'m \"Good\".',$result);
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars01() {
        $str = 'Path: "C:/Windows/Media/onestop.midi"\n';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('Path: \"C:\/Windows\/Media\/onestop.midi\"\\\\n',$result);
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars02() {
        $str = '\tI\'m good. But "YOU" are "Better".\r\n'
                . '\\An inline comment is good.';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('\\\\tI\'m good. But \"YOU\" are \"Better\".\\\\r\\\\n'
                . '\\\\An inline comment is good.',$result);
    }
}
