<?php
/*
## File: qsearch.php
## File Created: Thursday, 2nd December 2021 10:04:21 am
## Author: ais (aisyah1800018329@webmail.uad.ac.id)
## Copyright @ 2021 Aisyah Fortuna Nadapoetri Alhaq
*/

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