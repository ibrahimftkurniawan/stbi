<?php
/*
## File: index.php
## File Created: Thursday, 2nd December 2021 10:13:01 am
## Author: ais (aisyah1800018329@webmail.uad.ac.id)
## Copyright @ 2021 Aisyah Fortuna Nadapoetri Alhaq
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: STBI - Indexing & Retrieval ::</title>
</head>
<body>
    <h1 align=center>STBI - Proses Indexing & Retrieval</h1>
    <hr>
    <div align=center>
        <a href="index.php">Beranda</a> |
        <a href="index.php?act=corpus">Tampilkan Corpus</a> |
        <a href="index.php?act=indexing">Buat Index</a> |
        <a href="index.php?act=bobot">Hitung Bobot</a> |
        <a href="index.php?act=panjangvektor">Hitung Panjang Vektor</a> |
        <a href="index.php?act=showindex">Tampilkan Index</a> |
        <a href="index.php?act=showvektor">Tampilkan Panjang Vektor</a> |
        <a href="index.php?act=retrieve">Retrieval</a> |
        <a href="index.php?act=cache">Tampilkan Cache</a> |
        <a href="index.php?act=rank">Tampilkan Ranking Query</a> |
        <a href="index.php?act=rankdoc">Tampilkan Ranking Doc</a> |
    </div>
    <hr />
    
<?php
include 'koneksi.php';
include 'fungsi.php';
// $konek = mysqli_connect("localhost","root","","dbstbi") ;
//periksa apa yang diinginkan pengguna (variabel act)
$apa = null;
if(isset($_GET['act'])) {
    $apa = $_GET["act"];
}
//jika "corpus"
$query="SELECT * FROM tbberita ORDER BY Id";
if ($apa == "corpus") {
    $result = mysqli_query($konek, $query);
    while($row = mysqli_fetch_array($result)) {
        echo $row['Id'] .". <font color=blue>" . $row['Judul'] . "</font><br />" . $row['Berita'];
        echo "<hr />";
    }
}
//jika "indexing"
else if ($apa == "indexing") {
    buatindex();
    print("<hr />");
}
else if ($apa == "bobot") {
    hitungbobot();
    print("<hr />");
}
else if ($apa == "panjangvektor") {
    panjangvektor();
    print("<hr />");
}
else if ($apa == "showvektor") {
    print("<table>");
    print("<tr><td>Doc-ID</td><td>Bobot Dokumen</td></tr>"); 
    $query1 = "SELECT * FROM tbvektor";
    $result = mysqli_query($konek, $query1);
    while($row = mysqli_fetch_array($result)) {
        print("<tr>");
        print("<td>" . $row['DocId'] . "</td><td>" . $row['Panjang'] . "</td>");
        print("</tr>");
    }
    print("</table><hr />");
}
//jika "showindex"
else if ($apa == "showindex") {
    print("<table>");
    print("<tr><td>#</td><td>Term</td><td>DocID</td><td>Count</td><td>Bobot</td></tr>");
    $query2 = "SELECT * FROM tbindex ORDER BY Id";
    $result = mysqli_query($konek, $query2);
    while($row = mysqli_fetch_array($result)) {
        print("<tr>");
        print("<td>" . $row['Id'] . "</td><td>" . $row['Term'] . "</td><td>" . $row['DocId'] . "</td><td>" . $row['Count'] . "</td><td>" . $row['Bobot'] . "</td>");
        print("</tr>");
    }
    print("</table><hr />");
}
//jika "retrieve"
else if ($apa == "retrieve") {
    print('<center><form action="index.php?act=retrieve" method="post">
    Kata kunci: <input type="text" name="keyword" />
    <input name = "Cari!" type="submit" />
    </form></center><hr />');
    $keyword = null;
    if(isset($_POST['keyword'])){
        $keyword = $_POST["keyword"]; 
    }

    if ($keyword) {
        $keyword = preproses($keyword);
        print('Hasil retrieval untuk <font color=blue><b>' . $_POST["keyword"] .  '</b></font> (<font color=blue><b>' . $keyword . '</b></font>) adalah <hr />');
        ambilcache($keyword);
        //hitungsim($keyword); 
    }
} //end retrieve
//jika "cache"
else if ($apa == "cache") {
    print("<table>");
    print("<tr><td>#</td><td>Query</td><td>Doc-ID</td><td>Value</td></tr>");
    $query3 = "SELECT * FROM tbcache ORDER BY Query ASC";
    $result = mysqli_query($konek, $query3);
    while($row = mysqli_fetch_array($result)) {
        print("<tr>");
        print("<td>" . $row['Id'] . "</td><td>" . $row['Query'] . "</td><td>" . $row['DocId'] . "</td><td>" . $row['Value'] . "</td>");
        print("</tr>");
    }
    print("</table><hr />");
}

else if ($apa == "rank") {
    print("<table>");
    print("<tr><td>Rank</td><td>Query</td><td>Value</td></tr>");
    $query3 = "SELECT Query, SUM(ROUND(Value, 6)) AS Value  FROM tbcache GROUP BY Query ORDER BY Value DESC";
    $result = mysqli_query($konek, $query3);
    $no = 0;
    while($row = mysqli_fetch_array($result)) {
        $no++;
        print("<tr>");
        print("<td>" . $no . "</td><td>" . $row['Query'] . "</td><td>" . round(sqrt($row['Value']), 6) . "</td>");
        print("</tr>");
    }
    print("</table><hr />");
}

else if ($apa == "rankdoc") {
    print("<table>");
    print("<tr><td>Rank</td><td>Dokumen</td><td>Value</td></tr>");
    $query3 = "SELECT DocId, SUM(ROUND(Value, 6)) AS Value  FROM tbcache WHERE DocId <> '0' GROUP BY DocId ORDER BY Value DESC";
    $result = mysqli_query($konek, $query3);
    $no = 0;
    while($row = mysqli_fetch_array($result)) {
        $no++;
        print("<tr>");
        print("<td>" . $no . "</td><td>" . $row['DocId'] . "</td><td>" . round(sqrt($row['Value']), 6) . "</td>");
        print("</tr>");
    }
    print("</table><hr />");
}

//jika beranda atau tidak memilih apapun
else {
    print("<p align=center>Pilih salah satu link di atas!</p><hr />"); 
    } 
?>

<h5 align=center>Aisyah Fortuna Nadapoetri Alhaq, Teknik Informatika, Universitas Ahmad Dahlan, 2021</h5>
</body>
</html>