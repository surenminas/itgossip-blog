<?php
echo "<pre>";

class Machine {
    // Properties
    private $numberOfWheels = 4;
    public $type;
    public $name;
    public $color;
    public $hoursePower;
  
    function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
    }
    // Methods
    function set_name($name) {
        $this->name = $name;
    }
    function set_type($type) {
        $this->type = $type;
    }
    function get_name() {
        return $this->name;
    }

    
    function get_numberOfWheels() {
        return $this->numberOfWheels;
    }
}
  
// $sedan = new Machine('Opel Astra 2011', 'Mardatar zibil');
// $truck = new Machine('Mercedes Sprinter', 'Bernatar zibil');

//$sedan->set_name();
//$sedan->set_type();



// var_dump( $sedan->get_numberOfWheels() );

// var_dump( $truck );


// echo $sedan->get_name();
// echo "<br>";
// echo $truck->get_name();

// echo "zzzzzzzzzzzzzzzzzzzzzzz<br><br><br>";
// class Airplane extends Machine{
//     public $zakrilkeqiUgol;

//     public function set_zakrilkeqiUgol($zakrilkeqiUgol) {
//         $this->zakrilkeqiUgol = $zakrilkeqiUgol;
//     }
    
//     function getZakrilkeqiUgol() {
//         return $this->zakrilkeqiUgol;
//     }

//     function get_name() {
//         return 12121212;
//     }

// }

//$airplane = new Airplane();

//$airplane->set_name('Airbus A 320');
// $airplane->set_zakrilkeqiUgol('30');


// var_dump( $airplane );

// var_dump( $airplane->zakrilkeqiUgol );
// var_dump( $airplane->get_name() );





  echo "<br /><br /><br /><br />";
$tupoyString = '[{"id":2,"title":"Qwert2","img":"http:\/\/localhost\/blog\/uploads\/photos\/s1_01.jpg","type":"post","category_name":"CSS"},{"id":11,"title":"Qwert","img":"http:\/\/localhost\/blog\/uploads\/photos\/s1_03.jpg","type":"post","category_name":"PHP"}]';
$tupoyArray = json_decode($tupoyString, true);
var_dump($tupoyArray[0]);
var_dump($tupoyArray[0]["title"]);
echo "<br /><br />";
$tupoyObject = json_decode($tupoyString);


var_dump($tupoyObject[0]);
var_dump($tupoyObject[0]->title);

echo "<br /><br />";