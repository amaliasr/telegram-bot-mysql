<?php $conn=mysqli_connect("localhost","root","","wisata_batu"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Bot Telegram</title>
</head>
<body>

</body>
</html>
<?php
//isikan token dan nama botmu yang di dapat dari bapak bot :
$TOKEN      = "936352772:AAGnW442bLHmBgP34kmSfUvIibx83nkJmzk"; // ganti dengan token bot anda
$usernamebot= "@WisataBatuBot"; // sesuaikan besar kecilnya, bermanfaat nanti jika bot dimasukkan grup.
// aktifkan ini jika perlu debugging
$debug = false;
 
// fungsi untuk mengirim/meminta/memerintahkan sesuatu ke bot
function request_url($method)
{
    global $TOKEN;
    return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}
 
// fungsi untuk meminta pesan
// bagian ebook di sesi Meminta Pesan, polling: getUpdates
function get_updates($offset)
{
    $url = request_url("getUpdates")."?offset=".$offset;
        $resp = file_get_contents($url);
        $result = json_decode($resp, true);
        if ($result["ok"]==1)
            return $result["result"];
        return array();
}
// fungsi untuk mebalas pesan,
// bagian ebook Mengirim Pesan menggunakan Metode sendMessage
function send_reply($chatid, $msgid, $text)
{
    global $debug;
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text,
        'reply_to_message_id' => $msgid   // <---- biar ada reply nya balasannya, opsional, bisa dihapus baris ini
    );
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents(request_url('sendMessage'), false, $context);
    if ($debug)
        print_r($result);
}
 
// fungsi mengolahan pesan, menyiapkan pesan untuk dikirimkan
function create_response($text, $message)
{
    global $usernamebot;
    global $input_wisata;
    global $nama_wisata;

    // inisiasi variable hasil yang mana merupakan hasil olahan pesan
    $hasil = '';  
    $fromid = $message["from"]["id"]; // variable penampung id user
    $chatid = $message["chat"]["id"]; // variable penampung id chat
    $pesanid= $message['message_id']; // variable penampung id message
    // variable penampung username nya user
    isset($message["from"]["username"])
        ? $chatuser = $message["from"]["username"]
        : $chatuser = '';
   
    // variable penampung nama user
    isset($message["from"]["last_name"])
        ? $namakedua = $message["from"]["last_name"]
        : $namakedua = '';  
    $namauser = $message["from"]["first_name"]. ' ' .$namakedua;
    // ini saya pergunakan untuk menghapus kelebihan pesan spasi yang dikirim ke bot.
    $textur = preg_replace('/\s\s+/', ' ', $text);
    // memecah pesan dalam 2 blok array, kita ambil yang array pertama saja
    $command = explode(' ',$textur,2); 
    $input_wisata = "batu";
    //
   // identifikasi perintah (yakni kata pertama, atau array pertamanya)
    $query = "SELECT * FROM wisata";
    $hasil = mysqli_query($conn,$query);
    $a=0;
    while($row = mysqli_fetch_assoc($hasil)) {
        $nama_wisata[$a] = $row["nama_wisata"];
        $a++;
    }

    if ($text == $nama_wisata) {
        return $nama_wisata;
    }else{
        return "not found";
    }
    // switch ($text) {
    //     // jika ada permintaan waktu
    //     case '/time':
    //     case '/time'.$usernamebot :
    //         $hasil  = "$namauser, waktu lokal bot sekarang adalah :\n";
    //         $hasil .= "\xE2\x8C\x9A".date("d M Y")."\nPukul ".date("H:i:s");
    //         break;
           
    //     case '/start':
    //          $hasil  = "Halo saudara/i $namauser, selamat datang di Wisata Batu Bot
    //                    berikut list command dari bot ini :
    //                    => /time --> untuk menampilkan waktu Anda
    //                    => ketik nama wisata untuk informasi lengkapnya
    //                    ";
    //          break;
         
    //     case $input_wisata:
    //          $hasil  = "wah mbatu $text";
    //          break;
    //     // balasan default jika pesan tidak di definisikan
    //     default:
    //         $hasil = 'Input Anda Tidak Teridentifikasi';
    //         break;
    // }
    // return $hasil;
}
 
// jebakan token, klo ga diisi akan mati
// boleh dihapus jika sudah mengerti
if (strlen($TOKEN)<20)
    die("Token mohon diisi dengan benar!\n");
// fungsi pesan yang sekaligus mengupdate offset
// biar tidak berulang-ulang pesan yang di dapat
function process_message($message)
{
    $updateid = $message["update_id"];
    $message_data = $message["message"];
    if (isset($message_data["text"])) {
    $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];
        $text = $message_data["text"];
        $response = create_response($text, $message_data);
        if (!empty($response))
          send_reply($chatid, $message_id, $response);
    }
    return $updateid;
}
 
function process_one()
{
    global $debug;
    $update_id  = 0;
    echo "-";
 
    if (file_exists("last_update_id"))
        $update_id = (int)file_get_contents("last_update_id");
 
    $updates = get_updates($update_id);
    // jika debug=0 atau debug=false, pesan ini tidak akan dimunculkan
    if ((!empty($updates)) and ($debug) )  {
        echo "\r\n===== isi diterima \r\n";
        print_r($updates);
    }
 
    foreach ($updates as $message)
    {
        echo '+';
        $update_id = process_message($message);
    }
   
    // update file id, biar pesan yang diterima tidak berulang
    file_put_contents("last_update_id", $update_id + 1);
}
// process_one();
// while (true) {
//     process_one();
//     sleep(1);
// }
$entityBody = file_get_contents('php://input');
$pesanditerima = json_decode($entityBody, true);
process_message($pesanditerima);
   
?>
