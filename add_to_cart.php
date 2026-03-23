<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    echo "login_required";
    exit();
}

$user_id = $_SESSION['user_id'];

/* TRY JSON FIRST */
$data = json_decode(file_get_contents("php://input"), true);

/* IF JSON NOT PRESENT → USE POST */
if($data){

    $name = mysqli_real_escape_string($conn, $data['name'] ?? '');
    $price = $data['price'] ?? '';
    $qty = $data['qty'] ?? 1;
    $image = mysqli_real_escape_string($conn, $data['image'] ?? '');
    $instructions = mysqli_real_escape_string($conn, $data['instructions'] ?? '');

}else{

    $name = mysqli_real_escape_string($conn, $_POST['food_name'] ?? '');
    $price = $_POST['price'] ?? '';
    $qty = $_POST['quantity'] ?? 1;
    $image = mysqli_real_escape_string($conn, $_POST['image'] ?? '');
    $instructions = '';
}

/* CHECK REQUIRED */
if($name == ''){
    echo "no_data";
    exit();
}

/* AUTO FIX missing price/image */
if($price == '' || $image == ''){

    $menuMap = [

    "Butter Chicken"=>["price"=>350,"image"=>"Images/butterChicken.jpg"],
    "Paneer Tikka"=>["price"=>250,"image"=>"Images/paneerTikka.jpg"],
    "Biryani"=>["price"=>300,"image"=>"Images/biryani.jpg"],
    "Masala Dosa"=>["price"=>180,"image"=>"Images/masalaDosa.jpg"],
    "Chole Bhature"=>["price"=>200,"image"=>"Images/choleBhature.jpg"],
    "Palak Paneer"=>["price"=>240,"image"=>"Images/palakPaneer.jpg"],
    "Tandoori Chicken"=>["price"=>320,"image"=>"Images/tandooriChicken.jpg"],
    "Rajma Rice"=>["price"=>190,"image"=>"Images/rajmaRice.jpg"],
    "Samosa"=>["price"=>60,"image"=>"Images/samosa.jpg"],
    "Gulab Jamun"=>["price"=>120,"image"=>"Images/gulabJamun.jpg"],

    "Pizza"=>["price"=>450,"image"=>"Images/pizza.jpg"],
    "Pasta Alfredo"=>["price"=>380,"image"=>"Images/pastaAlfredo.jpg"],
    "Lasagna"=>["price"=>420,"image"=>"Images/lasagna.jpg"],
    "Bruschetta"=>["price"=>180,"image"=>"Images/bruschetta.jpg"],
    "Tiramisu"=>["price"=>220,"image"=>"Images/tiramisu.jpg"],

    "Fried Rice"=>["price"=>220,"image"=>"Images/friedRice.jpg"],
    "Hakka Noodles"=>["price"=>200,"image"=>"Images/hakkaNoodles.jpg"],
    "Spring Rolls"=>["price"=>150,"image"=>"Images/springRolls.jpg"],
    "Dumplings"=>["price"=>260,"image"=>"Images/dumplings.jpg"],
    "Manchurian"=>["price"=>210,"image"=>"Images/manchurian.jpg"],

    "Chocolate Cake"=>["price"=>200,"image"=>"Images/chocolateCake.jpg"],
    "Ice Cream"=>["price"=>150,"image"=>"Images/iceCream.jpg"],
    "Donut"=>["price"=>90,"image"=>"Images/donut.jpg"],
    "Cupcake"=>["price"=>110,"image"=>"Images/cupcake.jpg"],
    "Cheesecake"=>["price"=>230,"image"=>"Images/cheesecake.jpg"],

    "Chocolate Milkshake"=>["price"=>180,"image"=>"Images/chocolateMilkshake.jpg"],
    "Strawberry Milkshake"=>["price"=>170,"image"=>"Images/strawberryMilkshake.jpg"],
    "Vanilla Milkshake"=>["price"=>160,"image"=>"Images/vanillaMilkshake.webp"],
    "Oreo Shake"=>["price"=>200,"image"=>"Images/oreoShake.jpg"],
    "Banana Shake"=>["price"=>150,"image"=>"Images/bananaShake.jpg"],

    "Cappuccino"=>["price"=>140,"image"=>"Images/cappuccino.jpg"],
    "Latte"=>["price"=>150,"image"=>"Images/latte.jpg"],
    "Americano"=>["price"=>120,"image"=>"Images/americano.jpg"],
    "Mocha"=>["price"=>170,"image"=>"Images/mocha.jpg"],
    "Cold Brew"=>["price"=>160,"image"=>"Images/coldBrew.jpg"],
    ];

    if(isset($menuMap[$name])){
        $price = $menuMap[$name]['price'];
        $image = $menuMap[$name]['image'];
    }
}

/* FINAL CHECK */
if($price == '' || $image == ''){
    echo "something went wrong";
    exit();
}

/* CHECK EXISTING */
$result = mysqli_query($conn,
"SELECT * FROM cart WHERE user_id='$user_id' AND food_name='$name'");

if(mysqli_num_rows($result) > 0){

    mysqli_query($conn,
    "UPDATE cart 
     SET quantity = quantity + $qty 
     WHERE user_id='$user_id' AND food_name='$name'");

}else{

    mysqli_query($conn,
    "INSERT INTO cart 
    (user_id, food_name, price, quantity, image, instructions)
    VALUES
    ('$user_id','$name','$price','$qty','$image','$instructions')");
}

/* RESPONSE HANDLING */
if($data){
    echo "added";
    exit();
}else{
    header("Location: cart.php");
    exit();
}
