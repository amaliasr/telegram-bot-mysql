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
$TOKEN      = "780751210:AAFvkUEJAkjM9S3LAZ30-W9RwAZDkP_dekY"; // ganti dengan token bot anda
$usernamebot= "@RaraCobaBot"; // sesuaikan besar kecilnya, bermanfaat nanti jika bot dimasukkan grup.
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
    $jawab = '';
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
                
    if ($text == "/start") {
        return "Selamat Datang di BOT Wisata Batu, untuk mengetahui informasi tentang wisata apa saja di Kota Batu, ketikkan nama wisata yang ingin anda tuju";
    }else{
        $conn=mysqli_connect("us-cdbr-iron-east-02.cleardb.net","b922761359a8ef","f2dc7336","heroku_4d1c88e045301e7");
        $nama_wisata = $text;
        $jum_input = count(explode(" ",$nama_wisata));
        // pecahan kata2
        for ($i=0; $i < $jum_input ; $i++) { 
            $input[$i] = explode(" ",$nama_wisata)[$i];
        }
        $query2 = "SELECT * FROM wisata";
        $hasil2 = mysqli_query($conn,$query2);
        $a = 1;
        $b = 0;
        while($row2 = mysqli_fetch_assoc($hasil2)) {
            $jumlah_char[$b++] = count(explode(" ",$row2["nama_wisata"]));
        }
        $query3 = "SELECT * FROM wisata";
        $hasil3 = mysqli_query($conn,$query3);
        $c = 0;
        while($row3 = mysqli_fetch_assoc($hasil3)){
            $id_wisata = $row3["idwisata"];
            $total = 0;
            $jum_query[$id_wisata] = count(explode(" ",$row3["nama_wisata"]));
            // pecahan kata2 query per row
            $suku_sama = 0;
            for ($i=0; $i < $jum_query[$id_wisata];$i++) { 
                $querys[$i] = explode(" ",$row3["nama_wisata"])[$i];
                    for ($j=0; $j < $jum_input ; $j++) { 
                        if (ucwords($querys[$i]) == ucwords($input[$j])) {
                            $suku_sama++;
                        }
                    }
            }
            $jumlah_suku_sama[$id_wisata] = $suku_sama;
            // rumus
            $total = $jumlah_suku_sama[$id_wisata] /($jum_input + $jumlah_char[$c] - $jumlah_suku_sama[$id_wisata]);
            $total_array[$id_wisata] = round($total,3);
            $c++;
        }
        arsort($total_array);
        $b = 1;
        foreach ($total_array as $key => $value) {
            if ($b==1) {
                // if ($value != 0) {
                    $query = "SELECT * FROM wisata WHERE idwisata = '$key'";
                    $hasil = mysqli_query($con,$query);
                    while($row = mysqli_fetch_assoc($hasil)){
                        $id_tertinggi = $row["idwisata"];
                        $nilai_tertinggi = $value;
                    }
                // }
            }
            $b++;
        }
        $a = 1;
        foreach ($total_array as $key => $value) {
            if ($a == 1) {
                if ($value != 0) {
                    $query = "SELECT * FROM wisata WHERE idwisata = '$key'";
                    $hasil = mysqli_query($conn,$query);
                    while($row = mysqli_fetch_assoc($hasil)){
                        return "Informasi ".$row["nama_wisata"]." : ".$row["informasi"];
                    }
                }else{
                    return "Kata Anda Tidak Ditemukan, Coba Lagi";
                }
            }elseif ($nilai_tertinggi == $value) {
                if ($value != 0) {
                    $query = "SELECT * FROM wisata WHERE idwisata = '$key'";
                    $hasil = mysqli_query($con,$query);
                    while($row = mysqli_fetch_assoc($hasil)){
                        return "Temuan Lainnya : ".$row["nama_wisata"]." , ".$row["informasi"];
                    }
                }
            }
            $a++;
        }
    }
   
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
