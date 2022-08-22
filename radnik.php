<!DOCTYPE html>
<html>
<?php
    session_start();
?>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/proba.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>


    <!-- FUNKCIONALNOSTI -->
    <title>Радник</title>
    <script type="text/javascript">
        function uplataPrikaz(uplata_id)
        {
            var slika = null;

            preview = document.getElementById("preview");

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'uplataPrikaz', arguments: [uplata_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                slika = obj.result.uplata_foto;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            if(slika != null)
            {
                //document.write("Ту смо!");
                preview.setAttribute('src', 'data:image/jpeg;base64,'.concat(slika));
            }
        }
        function novacAzuriranje(uplata_id)
        {
            var student_id = null;

            iznos = document.getElementById("iznos").value;

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'uplataPrikaz', arguments: [uplata_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                student_id = obj.result.student_id;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            if(student_id != null)
            {
                if (iznos == "" || iznos < 0 || !$.isNumeric( iznos ))
                {
                    alert("Потребно је унети ненегативан цео број!");
                    return false;
                }
                //AZURIRAJ NOVAC NA RACUNU
                jQuery.ajax({
                    type: "POST",
                    url: 'PHP_jQuery.php',
                    dataType: 'json',
                    data: {functionname: 'novacAzuriranje', arguments: [student_id, iznos]},

                    success: function (obj, textstatus) {
                                if( !('error' in obj) ) {
                                    yourVariable = obj.result;
                                }
                                else {
                                    console.log(obj.error);
                                }
                            }
                });

                //OBRISI UPLATU
                jQuery.ajax({
                    type: "POST",
                    url: 'PHP_jQuery.php',
                    dataType: 'json',
                    data: {functionname: 'uplataBrisanje', arguments: [uplata_id]},

                    success: function (obj, textstatus) {
                                if( !('error' in obj) ) {
                                    yourVariable = obj.result;
                                    //document.write(yourVariable);
                                }
                                else {
                                    console.log(obj.error);
                                }
                            }
                });

                location.reload();
            }
        }
        /////////////////////////////////////////////////////////////////////////////
        /////PRETRAGA STUDENATA
        function studentPretraga()
        {
            var student_id = null;

            broj_indeksa = document.getElementById("broj_indeksa").value;
            if (broj_indeksa == "")
            {
                alert("Потребно је унети број индекса!");
                return false;
            }

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'studentPretraga', arguments: [broj_indeksa]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                student_id = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            if(student_id != null && student_id != "Nije proslo")
            {
                //document.write(student_id);
                window.location = "student.php?student_id="+student_id;
            }
            else
            {
                alert("Не постоји студент са унетим бројем индекса!");
                return false;
            }
        }

        /////NOVI ROK
        function noviRok()
        {           
            rok_prijava_pocetak = document.getElementById("rok_prijava_pocetak").value;
            rok_prijava_kraj = document.getElementById("rok_prijava_kraj").value;
            rok_pocetak = document.getElementById("rok_pocetak").value;
            rok_kraj = document.getElementById("rok_kraj").value;

            rok_naziv = document.getElementById("rok_naziv").value;
            
            if(!Date.parse(rok_prijava_pocetak) || !Date.parse(rok_prijava_kraj) || !Date.parse(rok_pocetak) || !Date.parse(rok_kraj)){
                alert('Потребно је унети датуме!');
                return false;
            }
            if(Date.parse(rok_prijava_pocetak) < Date.now() || Date.parse(rok_prijava_kraj) < Date.now() || Date.parse(rok_pocetak) < Date.now() || Date.parse(rok_kraj) < Date.now())
            {
                alert("Унет је датум пре данашњег дана!");
                return false;
            }

            if(!(Date.parse(rok_prijava_pocetak) < Date.parse(rok_prijava_kraj)) || !(Date.parse(rok_pocetak) < Date.parse(rok_kraj)) || !(Date.parse(rok_prijava_pocetak) < Date.parse(rok_pocetak)))
            {
                alert("Датуми почетка и краја рока и пријаве треба да буду један за другим!");
                return false;
            }

            if(rok_naziv == "")
            {
                alert("Потребно је унети назив рока!")
                return false;
            }

            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'noviRok', arguments: [rok_prijava_pocetak, rok_prijava_kraj, rok_pocetak, rok_kraj, rok_naziv]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                yourVariable = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            alert("Нови рок је успешно унет!")
            location.reload();
        }
        /////UNOS POLOZENIH ISPITA
        function noviPolozen(zapisnik_id)
        {
            var ocena = null;
            var zapisnik_datum = null;
            var student_id = null;
            var predmet_id = null;

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'zapisnikInfo', arguments: [zapisnik_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                ocena = obj.result.zapisnik_ocena;
                                zapisnik_datum = obj.result.zapisnik_datum;
                                student_id = obj.result.student_id;
                                predmet_id = obj.result.predmet_id;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            if(student_id != null && predmet_id != null)
            {
                jQuery.ajax({
                    type: "POST",
                    url: 'PHP_jQuery.php',
                    dataType: 'json',
                    data: {functionname: 'noviPolozen', arguments: [ocena, zapisnik_datum, student_id, predmet_id]},

                    success: function (obj, textstatus) {
                                if( !('error' in obj) ) {
                                    yourVariable = obj.result;
                                }
                                else {
                                    console.log(obj.error);
                                }
                            }
                });
                //document.write(predmet_id);

                location.reload();
            }
        }
    </script>
</head>
<body>
<?php
    ///////////////PROVERA DA NIJE UKUCAN SAMO LINK U BROWSERU
    $prijavaRegularna = false;
    if(isset($_SESSION["tip"]))
    {
        if($_SESSION["tip"] == "radnik")  //Ako je uspostavljena sesija
            $prijavaRegularna = true;
    }

    if($prijavaRegularna)
    {
?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
<?php
                require_once "DB_PDO.php";
                
                $DBveza = new DB_PDO();
                
                ////////////INFORMACIJE O RADNIKU
                $radnikInfo = $DBveza->radnikInfo($_SESSION["tip_id"]);
                if ($radnikInfo)
                {
?>
                    <div class='sidebar-header'>
<?php                    
                        echo "<h2>{$radnikInfo['radnik_ime']} {$radnikInfo['radnik_prezime']}</h2>";
?>
                    </div>
                    <ul class="list-unstyled components">
<?php
                        echo "<li>";
                            echo "<a>";
                                echo "Телефон: {$radnikInfo['radnik_tel']}";
                            echo "</a>";
                        echo "</li>";

                        echo "<li>";
                            echo "<a>";
                                echo "Мејл: {$radnikInfo['radnik_mejl']}";
                            echo "</a>";
                        echo "</li>";

?>
                    </ul>
                    <ul class="list-unstyled CTAs">
                        <li>
                            <a href="index.php" class="download">ОДЈАВА</a>
                        </li>
                        <li>
                            <a href="predmeti.php" class="article">ПРЕДМЕТИ</a>
                        </li>
                        <li>
                            <a href="admin.php" class="article">НОВИ ЧЛАНОВИ</a>
                        </li>
                    </ul>
            </nav>
        
            <!-- Page Content  -->
            <div id="content">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="container-fluid">

                            <button type="button" id="sidebarCollapse" class="btn btn-info">
                                <i class="fas fa-align-left"></i>
                                <span>ИНФО</span>
                            </button>
                            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fas fa-align-justify"></i>
                            </button>
                        </div>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item active">
                                    <a class="nav-link"><h4>РАДНИК</h4></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
<?php
                    $uplate = $DBveza->uplataInfo();
?>
                    <h2>Евидентирање уплата студената</h2>

                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Број индекса</th>
                                <th>Име и презиме</th>
                                <th>Адреса</th>
                                <th>Фотографија уплатнице</th>
                                <th>Ажурирање новца</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                        foreach($uplate as $uplata)
                        {
                            echo  "<tr>
                                    <td>{$uplata['broj_indeksa']}</td>
                                    <td>{$uplata['student_ime']} {$uplata['student_prezime']}</td>
                                    <td>{$uplata['student_adresa']}</td>
                                    <td>
                                        <input type='button' id={$uplata['uplata_id']} value='ПРИКАЗ' onclick='uplataPrikaz(this.id)'>
                                    </td>
                                    <td>
                                        <input type='button' id={$uplata['uplata_id']} value='АЖУРИРАЊЕ' onClick='novacAzuriranje(this.id);'/>
                                    </td>
                                </tr>";
                        }
?>
                        </tbody>
                    </table>

                    <label>Износ уплате (уколико је уплатница неважећа, унети вредност "0"):</label><br>
                    <input type="text" id="iznos" placeholder="износ" autocomplete="off"><br><br>
                    <img src='' id='preview' class="rounded" style="max-width:800px;width:100%">

                    <div class="line"></div>

                    <h2>Претрага студената</h2>
                    <!-- <label>Број индекса</label> -->
                    <input type="text" id="broj_indeksa" placeholder="број индекса" autocomplete="off">
                    <input type="button" value='ПРЕТРАГА' onClick="studentPretraga();" />

                    <div class="line"></div>
                    
                    <h2>Нови испитни рок</h2>
                    
                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Почетак пријаве</th>
                                <th>Крај пријаве</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="datetime-local" id="rok_prijava_pocetak"></td>
                                <td><input type="datetime-local" id="rok_prijava_kraj"></td>
                            </tr>
                        </tbody>
                        <thead class='thead-light'>
                            <tr>
                                <th>Почетак рока</th>
                                <th>Крај рока</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="datetime-local" id="rok_pocetak"></td>
                                <td><input type="datetime-local" id="rok_kraj"></td>
                            </tr>
                        </tbody>
                        <thead class='thead-light'>
                            <tr>
                                <th>Назив рока (нпр. јунски)</th>
                                <th>Унос новог рока</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="rok_naziv" autocomplete="off"></td>
                                <td><input type="button" value='УНОС' onClick="noviRok();"/></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!--
                    <label>Почетак пријаве</label>
                    <input type="datetime-local" id="rok_prijava_pocetak"><br>
                    <label>Крај пријаве</label>
                    <input type="datetime-local" id="rok_prijava_kraj"><br>
                    <label>Почетак рока</label>
                    <input type="datetime-local" id="rok_pocetak"><br>
                    <label>Крај рока</label>
                    <input type="datetime-local" id="rok_kraj"><br>
                    <label>Назив рока (нпр. јунски)</label>
                    <input type="text" id="rok_naziv"><br>
                    <input type="button" value='УНОС' onClick="noviRok();"/>
                    <br><br>
                    -->
<?php
                    /////ZAPISNIK
                    $zapisnik = $DBveza->radnikZapisnik();
?>
                    <h2>Унос оцена уписаних у записник</h2>

                    <table class='table'>
                        <thead class='thead-light'>    
                            <tr>
                                <th>Шифра предмета</th>
                                <th>Назив предмета</th>
                                <th>Семестар</th>
                                <th>Оцена</th>
                                <th>Датум полагања</th>
                                <th>Број индекса</th>
                                <th>Студент</th>
                                <th>Професор</th>
                                <th>Унос оцене</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                        foreach($zapisnik as $ispit)
                        {
                            echo  "<tr>
                                    <td>{$ispit['predmet_sifra']}</td>
                                    <td>{$ispit['naziv']}</td>
                                    <td>{$ispit['predmet_semestar']}</td>
                                    <td>{$ispit['zapisnik_ocena']}</td>
                                    <td>{$ispit['zapisnik_datum']}</td>
                                    <td>{$ispit['broj_indeksa']}</td>
                                    <td>{$ispit['student_ime']} {$ispit['student_prezime']}</td>
                                    <td>{$ispit['nastavnik_ime']} {$ispit['nastavnik_prezime']}</td>
                                    <td>
                                        <input type='button' id={$ispit['zapisnik_id']} value='УНОС' onclick='noviPolozen(this.id)'>
                                    </td>";
                            echo  "</tr>";
                        }
?>
                        </tbody>
                    </table>
<?php
                }
                else
                {
                    echo "<script type='text/javascript'>alert('Нема података у бази');</script>";
                }
?>
            </div>
        </div>
<?php
    }
    else
    {
        /////NEDOZVOLJEN PRISTUP!!!
?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class='sidebar-header'>
                    <h2>Недозвољен приступ!</h2>
                </div>

                <ul class="list-unstyled CTAs">
                    <li>
                        <a href="index.php" class="download">ПРИЈАВА</a>
                    </li>
                </ul>
            </nav>
            <!-- Page Content  -->
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-align-left"></i>
                            <span>ИНФО</span>
                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>
                    </div>
                </nav>
            </div>
        </div>
<?php
    }
?>
    
    <!-- OVDE SAM UVEZAO JQUERY SA AJAX-OM -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>
</body>
</html>