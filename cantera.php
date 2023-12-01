<?php

function debuguear($variable): void
{
    echo "<pre>";
    print_r($variable);
    echo "</pre>";
}

class MyClass
{
    private $a = 1;

    public function add(int $a)
    {
        $this->a += $a;
        return $this;
    }

    public function get()
    {
        return $this->a;
    }
}

echo 'Funciones Encadenadas: ';
echo '<br>';

$object = new MyClass();
var_dump($object->add(4)->get());

echo '<br>';
echo '<hr>';
echo 'Operador ternario solo pasan false';

// * Nota: esta es una operacion ternaria que solo mostrara un false es decir si es true no asigna nada

$miVal = null;

$miVal = $miVal ?: "Esta vacio";

debuguear($miVal);

echo '<br>';
echo '<hr>';
echo 'Operador de Nave Espacial';

echo '<br>';
print(1 <=> 1); // 0
echo '<br>';
print(1 <=> 2); // -1
echo '<br>';
print(2 <=> 1); // 1

echo '<br>';

echo "Path:", __FILE__, "\n";

echo '<br>';

echo "Our script is located in the:", __DIR__, "\n";

echo '<br>';

echo "Our script is located in the:", dirname(__FILE__), "\n";

echo '<br>';

echo $_GET["myVar"] ?? 'Esta Vacio'; // Que imprima lo que contiene, si no que imprima esta vacio

// if (!file_exists($folderLocation)) mkdir($folderLocation);

echo '<br>';
echo '<hr>';
echo 'Paso de Referencia';

// * Nota: Lo que esta haciendo es recorrer el arreglo eh irlo incrementando pero almacenandolo en el mismo arreglo (Al parecer es muy Util)

$arr = array(1, 2, 3, 4, 5);
foreach ($arr as &$num) {
    $num++;
}

debuguear($arr);

$var = 5;

// Con esto practicamente se le pasa la variable que esta afuera y dentro de la funcion se incrementara el valor 
// y la operacion(referencia)se pasa en la variable de afuera
function add(&$var)
{
    $var++;
}
// LLamado
add($var);

echo $var;

echo '<br>';
echo '<hr>';
echo 'Compact con variales a array asosiativo';

// * la funcion compact se le pasan el nombre de las variables como string y esta los mete en un arreglo asosiativo en donde
// *    el nombre de la variable es la llave y el contenido es el value

$username = 'Hadibut';
$email = 'hadibut@example.org';
$variables = compact('username', 'email');
// $variables is now ['username' => 'Hadibut', 'email' => 'hadibut@example.org']

debuguear($variables);

echo '<br>';
echo '<hr>';
echo 'Arreglos en columnas';

// * Util pasa buscar un valor dentro de un arreglo de areglos asosiativos

$userdb = [
    [
        "uid" => '100',
        "name" => 'Sandra Shush',
        "url" => 'urlof100',
    ],
    [
        "uid" => '5465',
        "name" => 'Stefanie Mcmohn',
        "pic_square" => 'urlof100',
    ],
    [
        "uid" => '40489',
        "name" => 'Michael',
        "pic_square" => 'urlof40489',
    ]
];
$key = array_search(40489, array_column($userdb, 'uid'));

$mivalx = array_column($userdb, 'name');

debuguear($key);


echo '<br>';
echo '<hr>';
echo 'Iteradores de Array' . '<br>' . '<br>';

// * Nota: Aqui se muestran algunas de las formas de iterar arreglos 

$people = ['Tim', 'Tony', 'Turanga'];
$foods = ['chicken', 'beef', 'slurm'];

echo 'Con map' . '<br>';

$mivalor = array_map(function ($person, $food) {
    return "$person likes $food\n";
}, $people, $foods);

debuguear($mivalor);

echo 'Con ForEach' . '<br>';

foreach ($people as $index => $person) {
    $food = $foods[$index];
    echo "<br>" . "$person likes $food\n";
}

echo '<br>' . '<br>' . 'Convinacion de arreglos de index a asosiativo' . '<br>';
// Nota: Solo se hace con dos arreglos de la misma longitud
$combinedArray = array_combine($people, $foods);
// $combinedArray = ['Tim' => 'chicken', 'Tony' => 'beef', 'Turanga' => 'slurm'];
debuguear($combinedArray);

echo '<br>';
echo '<hr>';
echo '????' . '<br>' . '<br>';

$array = ["Alpha", "Beta", "Gamma", "Delta"];
while (key($array) !== null) { // Verifica que no sea arreglo asosiativo
    echo current($array) . PHP_EOL;
    next($array);
}

debuguear(key($array) ?? 'No es Arreglo Asosiativo');