<?php
/*
## File: fungsi.php
## File Created: Thursday, 2nd December 2021 10:20:32 am
## Author: ais (aisyah1800018329@webmail.uad.ac.id)
## Copyright @ 2021 Aisyah Fortuna Nadapoetri Alhaq
*/

function preproses($teks) {
    $konek = mysqli_connect("localhost","root","","dbstbi") ;
    //bersihkan tanda baca, ganti dengan space
    $teks = str_replace("'", " ", $teks);
    $teks = str_replace("-", " ", $teks);
    $teks = str_replace(")", " ", $teks);
    $teks = str_replace("(", " ", $teks);
    $teks = str_replace("\"", " ", $teks);
    $teks = str_replace("/", " ", $teks);
    $teks = str_replace("=", " ", $teks);
    $teks = str_replace(".", " ", $teks);
    $teks = str_replace(",", " ", $teks);
    $teks = str_replace(":", " ", $teks);
    $teks = str_replace(";", " ", $teks);
    $teks = str_replace("!", " ", $teks);
    $teks = str_replace("?", " ", $teks);
    //ubah ke huruf kecil
    $teks = strtolower(trim($teks));
    //terapkan stop word removal
    $astoplist = array ("yang", "juga", "dari", "dia", "kami", "kamu", "ini", "itu","atau", "dan", "tersebut", "pada", "dengan", "adalah", "yaitu",  "ke");
    foreach ($astoplist as $i => $value) {
        $teks = str_replace($astoplist[$i], "", $teks);
    }
    //terapkan stemming
    //buka tabel tbstem dan bandingkan dengan berita
    $query = "SELECT * FROM tbstem ORDER BY Id";
    $restem = mysqli_query($konek, $query);
    while($rowstem = mysqli_fetch_array($restem)) {
        $teks = str_replace($rowstem['Term'], $rowstem['Stem'], $teks);
    }
    //kembalikan teks yang telah dipreproses
    $teks = strtolower(trim($teks));
    return $teks;
} //end function preproses
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
//fungsi untuk membuat index
function buatindex() {
    $konek = mysqli_connect("localhost","root","","dbstbi");
    //hapus index sebelumnya
    $querycate = "TRUNCATE TABLE tbindex";
    mysqli_query($konek, $querycate);
    //ambil semua berita (teks)
    $query = "SELECT * FROM tbberita ORDER BY Id";
    $resBerita = mysqli_query($konek, $query);
    $num_rows = mysqli_num_rows($resBerita);
    print("Mengindeks sebanyak " . $num_rows . " berita. <br />");
    while($row = mysqli_fetch_array($resBerita)) {
        $docId = $row['Id'];
        $berita = $row['Berita'];
        //terapkan preprocessing
        $berita = preproses($berita);
        //simpan ke inverted index (tbindex)
        $aberita = explode(" ", trim($berita));
        foreach ($aberita as $j => $value) {
            //hanya jika Term tidak null atau nil, tidak kosong
            if ($aberita[$j] != "") {
                //berapa baris hasil yang dikembalikan query tersebut?
                $query1 = "SELECT Count FROM tbindex WHERE Term = '$aberita[$j]' AND DocId =  $docId";
                $rescount = mysqli_query($konek, $query1);
                $num_rows = mysqli_num_rows($rescount);
                //jika sudah ada DocId dan Term tersebut , naikkan Count (+1)
                if ($num_rows > 0) {
                    $rowcount = mysqli_fetch_array($rescount);
                    $count = $rowcount['Count'];
                    $count++;
                    $query2 ="UPDATE tbindex SET Count =$count WHERE Term ='$aberita[$j]'AND DocId =$docId";
                    mysqli_query($konek, $query2); 
                }
                //jika belum ada, langsung simpan ke tbindex
                else {
                    $query3 = "INSERT INTO tbindex (Term, DocId, Count) VALUES ('$aberita[$j]', $docId,  1)";
                    mysqli_query($konek, $query3); 
                }
            } //end if
        } //end foreach
    } //end while
} //end function buatindex()

//fungsi hitungbobot, menggunakan pendekatan tf.idf 
function hitungbobot() {
    $konek = mysqli_connect("localhost","root","","dbstbi") ;
    //berapa jumlah DocId total?, n
    $query1 = "SELECT DISTINCT DocId FROM tbindex";
    $resn = mysqli_query($konek, $query1);
    $n = mysqli_num_rows($resn);
    //ambil setiap record dalam tabel tbindex
    //hitung bobot untuk setiap Term dalam setiap DocId
    $query2 = "SELECT * FROM tbindex ORDER BY Id";
    $resBobot = mysqli_query($konek, $query2);
    $num_rows = mysqli_num_rows($resBobot);
    print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");
    while($rowbobot = mysqli_fetch_array($resBobot)) {
        //$w = tf * log (n/N)
        $term = $rowbobot['Term'];
        $tf = $rowbobot['Count'];
        $id = $rowbobot['Id'];
        //berapa jumlah dokumen yang mengandung term tersebut?, N
        $query3 = "SELECT Count(*) as N FROM tbindex WHERE Term = '$term'";
        $resNTerm = mysqli_query($konek, $query3);
        $rowNTerm = mysqli_fetch_array($resNTerm);
        $NTerm = $rowNTerm['N'];
        $w = $tf * log($n/$NTerm);
        //update bobot dari term tersebut
        $query4 = "UPDATE tbindex SET Bobot = $w WHERE Id = $id";
        $resUpdateBobot = mysqli_query($konek, $query4);
    } //end while $rowbobot
} //end function hitungbobot
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------

function panjangvektor() {
    //fungsi panjangvektor, jarak euclidean 
    //akar(penjumlahan kuadrat dari bobot  setiapTerm)
    $konek = mysqli_connect("localhost","root","","dbstbi") ;
    //hapus isi tabel tbvektor
    $query1 = "TRUNCATE TABLE tbvektor";
    mysqli_query($konek, $query1);
    //ambil setiap DocId dalam tbindex
    //hitung panjang vektor untuk setiap DocId tersebut
    //simpan ke dalam tabel tbvektor
    $query2 = "SELECT DISTINCT DocId FROM tbindex";
    $resDocId = mysqli_query($konek, $query2);
    $num_rows = mysqli_num_rows($resDocId);
    print("Terdapat " . $num_rows . " dokumen yang dihitung panjang vektornya. <br/>");
    while($rowDocId = mysqli_fetch_array($resDocId)) {
        $docId = $rowDocId['DocId'];
        $query3 = "SELECT Bobot FROM tbindex WHERE DocId = $docId";
        $resVektor = mysqli_query($konek, $query3);
        //jumlahkan semua bobot kuadrat
        $panjangVektor = 0;
        while($rowVektor = mysqli_fetch_array($resVektor)) {
            $panjangVektor = $panjangVektor + $rowVektor['Bobot'] * $rowVektor['Bobot'];
        }
        //hitung akarnya
        $panjangVektor = sqrt($panjangVektor);
        //masukkan ke dalam tbvektor
        $query4 = "INSERT INTO tbvektor (DocId, Panjang) VALUES ($docId, $panjangVektor)";
        $resInsertVektor = mysqli_query($konek, $query4);
    } //end while $rowDocId } //end function  panjangvektor
}
//-------------------------------------------------------------------------

function hitungSim($query) {
    //-----------------------------------------------------------------------
    //fungsi hitungsim - kemiripan antara query
    //setiap dokumen dalam database (berdasarkan bobot di tbindex)
    $konek = mysqli_connect("localhost","root","","dbstbi") ;
    //ambil jumlah total dokumen yang telah diindex (tbindex atau tbvektor), n
    $query5 = "SELECT Count(*) as n FROM tbvektor";
    $resn = mysqli_query($konek, $query5);
    $rown = mysqli_fetch_array($resn);
    $n = $rown['n'];
    //terapkan preprocessing terhadap $query
    $aquery = explode(" ", $query);
    //hitung panjang vektor query
    $panjangQuery = 0;
    $aBobotQuery = array();
    for ($i=0; $i<count($aquery); $i++) {
        //hitung bobot untuk term ke-i pada query, log(n/N);
        //hitung jumlah dokumen yang mengandung term tersebut
        $query6 = "SELECT Count(*) as N from tbindex WHERE Term = '$aquery[$i]'";
        $resNTerm = mysqli_query($konek, $query6);
        $rowNTerm = mysqli_fetch_array($resNTerm);
        $NTerm = $rowNTerm['N'] ;
        //$idf = 0;
        $idf = 0;
        if($NTerm > 0) $idf = log($n/$NTerm);
        //simpan di array
        $aBobotQuery[] = $idf;
        $panjangQuery = $panjangQuery + $idf * $idf;
    }

    $panjangQuery = sqrt($panjangQuery);
    $jumlahmirip = 0;
    //ambil setiap term dari DocId, bandingkan dengan Query
    $query7 = "SELECT * FROM tbvektor ORDER BY DocId";
    $resDocId= mysqli_query($konek, $query7);
    while ($rowDocId = mysqli_fetch_array($resDocId)) {
        $dotproduct = 0;
        $docId = $rowDocId['DocId'];
        $panjangDocId = $rowDocId['Panjang'];

        $query8 = "SELECT * FROM tbindex WHERE DocId = $docId";
        $resTerm = mysqli_query($konek, $query8);
        while ($rowTerm = mysqli_fetch_array($resTerm)) {
            for ($i=0; $i<count($aquery); $i++) {
                //jika term sama
                if ($rowTerm['Term'] == $aquery[$i]) {
                    $dotproduct = $dotproduct + $rowTerm['Bobot'] * $aBobotQuery[$i];
                } //end if
            } //end for $i
        } //end while ($rowTerm)

        if ($dotproduct > 0) {
            $sim = $dotproduct / ($panjangQuery * $panjangDocId);
            //simpan kemiripan > 0 ke dalam tbcache
            $query9 = "INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', $docId, $sim)";
            $resInsertCache = mysqli_query($konek, $query9);
            $jumlahmirip++;
        }
    } //end while $rowDocId

    if ($jumlahmirip == 0) {
        $query10 = "INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', 0, 0)";
        $resInsertCache = mysqli_query($konek, $query10);
    }
} //end hitungSim()
//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
function ambilcache($keyword) {
    $konek = mysqli_connect("localhost","root","","dbstbi") ;
    $query11 = "SELECT * FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC";
    $resCache = mysqli_query($konek, $query11);
    $num_rows = mysqli_num_rows($resCache);
    if ($num_rows >0) {
        //tampilkan semua berita yang telah terurut
        while ($rowCache = mysqli_fetch_array($resCache)) {
            $docId = $rowCache['DocId'];
            $sim = $rowCache['Value'];
            if ($docId != 0) {
                //ambil berita dari tabel tbberita, tampilkan
                $query12 = "SELECT * FROM tbberita WHERE Id = $docId";
                $resBerita = mysqli_query($konek, $query12);
                $rowBerita = mysqli_fetch_array($resBerita);
                $judul = $rowBerita['Judul'];
                $berita = $rowBerita['Berita'];
                print($docId . ". (" . $sim . ") <font color=blue><b>" . $judul . "</b></font><br/>");
                print($berita . "<hr />");
            } else {
                print("<b>Tidak ada... </b><hr />");
            }
        }//end while (rowCache = mysql_fetch_array($resCache))
    }//end if $num_rows>0
    else {
        hitungsim($keyword);
        //pasti telah ada dalam tbcache
        $query13 = "SELECT * FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC";
        $resCache = mysqli_query($konek, $query13);
        $num_rows = mysqli_num_rows($resCache);
        while ($rowCache = mysqli_fetch_array($resCache)) {
            $docId = $rowCache['DocId'];
            $sim = $rowCache['Value'];
            if ($docId != 0) {
                //ambil berita dari tabel tbberita, tampilkan
                $query14 = "SELECT * FROM tbberita WHERE Id = $docId";
                $resBerita = mysqli_query($konek, $query14);
                $rowBerita = mysqli_fetch_array($resBerita);
                $judul = $rowBerita['Judul'];
                $berita = $rowBerita['Berita'];
                print($docId . ". (" . $sim . ") <font color=blue><b>" . $judul . "</b></font><br/>");
                print($berita . "<hr />");
            } else {
                print("<b>Tidak ada... </b><hr />");
            }
        } //end while
    }
} //end function ambilcache
//-------------------------------------------------------------------------


?>