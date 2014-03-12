<?php

header('content-type:text/html; charset=utf-8');

$ip = "localhost";
$user = "root";
$password = "";
$db = "kategoriler";

try {
    $bag = new PDO("mysql:host=$ip;dbname=$db", $user, $password);
    $bag->exec("SET CHARSET UTF8");
} catch (PDOException $e) {
    die("hata var");
}

//bütün kayýtlarý bir kereye mahsus olmak üzere listeliyoruz; daha doðrusu, bir diziye aktarmak için verileri çekiyoruz
$query = "SELECT * FROM kategoriler order by id";
$goster = $bag->prepare($query);
$goster->execute(); //queriyi tetikliyor

$toplamSatirSayisi = $goster->rowCount(); //malumunuz üzere sorgudan dönen satýr sayýsýný öðreniyoruz

$tumSonuclar = $goster->fetchAll(); //DB'deki bütün satýrlarý ve sutunlarý $tumSonuclar deðiþkenine dizi þeklinde aktarýyoruz
//örnek kullanýmlar :
/*
  echo ($tumSonuclar[0]['ustKategori'] . " " . $tumSonuclar[0]['baslik'] . "<br>");
  echo ($tumSonuclar[1]['ustKategori'] . " " . $tumSonuclar[1]['baslik'] . "<br>");
  echo ($tumSonuclar[2]['ustKategori'] . " " . $tumSonuclar[2]['baslik'] . "<br>");
 */


//alt kategorisi olmayan kategorilerin sayýsýný öðreniyoruz:
$altKategoriSayisi = 0;
for ($i = 0; $i < $toplamSatirSayisi; $i++) {
    if ($tumSonuclar[$i]['ustKategori'] == "0") {
        $altKategoriSayisi++;
    }
}


echo "Kategoriler ($altKategoriSayisi) <br />";

echo "\n";
echo "<ul>";
echo "\n";

for ($i = 0; $i < $toplamSatirSayisi; $i++) {
    if ($tumSonuclar[$i]['ustKategori'] == "0") {
        Kategoriler($tumSonuclar[$i]['id'], $tumSonuclar[$i]['baslik'], $tumSonuclar[$i]['ustKategori']);
    }
}

echo "</ul>";

/*
 * FONKSIYONUN OZELLIKLERI:
 * verilen kategoriyi yazar sonra, yeni bir <ul> </ul> arasina o kategorinin alt kateogirilerini yazar.
 * bu iþlemi sonsuza kadar yapar, yani ne kadar alt kategoriniz varsa hepsini ekler
 */

function Kategoriler($id, $baslik, $ustKategori) {

    global $tumSonuclar;
    global $toplamSatirSayisi;

    //kategorinin, alt kategori sayýsýný öðreniyoruz:
    $altKategoriSayisi = 0;
    for ($i = 0; $i < $toplamSatirSayisi; $i++) {
        if ($tumSonuclar[$i]['ustKategori'] == $id) {
            $altKategoriSayisi++;
        }
    }
    ///////////////////////////////////////////


    echo "\n";
    echo "<li>";
    echo "\n";

    echo "\t";
    echo "<a href='$baslik.html'>  $baslik  ";
    if ($altKategoriSayisi > 0) {
        echo "( $altKategoriSayisi )";
    }
    echo "</a>";


    if ($altKategoriSayisi > 0) { //alt kategorisi varsa onlarý da listele
        echo "\n";
        echo "<ul>";

        for ($i = 0; $i < $toplamSatirSayisi; $i++) {

            if ($tumSonuclar[$i]['ustKategori'] == $id) {
                Kategoriler($tumSonuclar[$i]['id'], $tumSonuclar[$i]['baslik'], $tumSonuclar[$i]['ustKategori']);
            }
        }

        echo "</ul>";
    }
    echo "\n";
    echo "</li>";

    echo "\n";
}
?>






