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
    <!-- DOLE SAM UVEZAO JQUERY SA AJAXOM <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
    <title>Студент</title>
    
    <script type="text/javascript">
        
        function prijavaIspita(student_id, predmet_id, rok_id)
        {
            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'prijavaIspita', arguments: [student_id, predmet_id, rok_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                yourVariable = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            location.reload();
        }
        /////PROVERA
        function brojPrijava_Novac_INT(student_id, predmet_id, rok_id, brojPrijava, novac)
        {
            if(brojPrijava < 3)
            {
                prijavaIspita(student_id, predmet_id, rok_id);
            }
            else
            {
                if(novac >= 1000)       //Ne mora: parseInt(novac)
                {
                    prijavaIspita(student_id, predmet_id, rok_id);
                    
                    //SKINI PARE SA RACUNA
                    jQuery.ajax({
                        type: "POST",
                        url: 'PHP_jQuery.php',
                        dataType: 'json',
                        data: {functionname: 'naplataPrijave', arguments: [student_id]},

                        success: function (obj, textstatus) {
                                    if( !('error' in obj) ) {
                                        yourVariable = obj.result;
                                    }
                                    else {
                                        console.log(obj.error);
                                    }
                                }
                    });
                }
                else
                {
                    alert('Нема довољно новца за пријаву испита (цена - 1000 дин.)!');
                }
            }
        }
        
        function prijavaIspitaProvera(student_id, predmet_id, rok_id)
        {
            var brojPrijava = null;
            var novac = null;

            ////BROJ PRIJAVA
            jQuery.ajax({
                async: false,       //da promenljiva ostane globalna u funk
                type: "POST",
                global: false,      //da promenljiva ostane globalna u funk
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'brojPrijavaIspita', arguments: [student_id, predmet_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                brojPrijava = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            /////NOVAC
            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'novacSuma', arguments: [student_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                novac = obj.result;
                                //document.write(typeof novac);
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            /////
            if((brojPrijava != null) && (novac != null))
                brojPrijava_Novac_INT(student_id, predmet_id, rok_id, brojPrijava, novac);
            else
                alert('Нису враћени подаци из базе!');
        }
        /////UPLATNICA
        function isFileImage(file) {
            const acceptedImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
            return file && acceptedImageTypes.includes(file['type'])

            //return file && file['type'].split('/')[0] === 'image';

        }
        function uplata(student_id, input)
        {
            //preview = document.getElementById("preview");
            var reader;
            if (input.files && input.files[0])
            {
                reader = new FileReader();                
                reader.onload = function(e)
                {
                    //preview.setAttribute('src', e.target.result);
                    /////
                    if(isFileImage(input.files[0])) //e.target.result
                    {
                        jQuery.ajax({
                            type: "POST",
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'uplata', arguments: [student_id, e.target.result]}, //slika

                            success: function (obj, textstatus) {
                                        if( !('error' in obj) ) {
                                            yourVariable = obj.result;
                                        }
                                        else {
                                            console.log(obj.error);
                                        }
                                    }
                        });
                        /////
                        alert('Уплатница је послата!');
                    }
                    else
                    {
                        alert("Унете датотека није слика!");
                        return false;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        //////
        ////////////////////////////////////////////
        /////RADNIK
        function upisNaredna(student_id, narednaGodina)
        {
            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'upisNaredna', arguments: [student_id, narednaGodina]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                yourVariable = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            
            location.reload();
        }
        function upisObnova(student_id, godina_id)
        {
            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'upisObnova', arguments: [student_id, godina_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                yourVariable = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            
            location.reload();
        }
        ////////////////////////////////////////////
    </script>
</head>
<body>
<?php
    ///////////////PROVERA DA NIJE UKUCAN SAMO LINK U BROWSERU
    $prijavaRegularna = false;
    if(isset($_SESSION["tip"]))
    {
        if($_SESSION["tip"] == "student" || ($_SESSION["tip"] == "radnik" && isset($_GET["student_id"])))  //Ako je uspostavljena sesija
            $prijavaRegularna = true;
    }
    
    if($prijavaRegularna)
    {
?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
<?php
            
                ////////////////////////////////////////////
                /////RADNIK
                if($_SESSION["tip"] == "student")
                {
                    $student_id = $_SESSION["tip_id"];
                }
                elseif($_SESSION["tip"] == "radnik")
                {
                    $student_id = $_GET["student_id"];
                }
                ////////////////////////////////////////////


                require_once "DB_PDO.php";
                
                $DBveza = new DB_PDO();
                
                ////////////INFORMACIJE O STUDENTU
                $studentInfo = $DBveza->studentInfo($student_id);
                if ($studentInfo)
                {
?>
                    <div class='sidebar-header'>
<?php                    
                        echo "<h2>{$studentInfo['student_ime']} {$studentInfo['student_prezime']}</h2>";
?>
                    </div>
                    <ul class="list-unstyled components">
<?php
                        echo '<img src="data:image/jpeg;base64,'.base64_encode( $studentInfo['student_foto'] ).'" class="rounded-circle" width="250" height="250"/><br><br>';

                        echo "<li>";
                            echo "<a>";
                                echo "Број индекса: {$studentInfo['broj_indeksa']}";
                            echo "</a>";
                        echo "</li>";

                        /////Regularni izraz
                        preg_match('([^\/]+$)', $studentInfo['broj_indeksa'], $regex);
                        echo "<li>";
                            echo "<a>";
                                echo "Година уписа: {$regex[0]}";
                            echo "</a>";
                        echo "</li>";
                        
                        /////
                        echo "<li>";
                            echo "<a>";
                                echo "Телефон: {$studentInfo['student_tel']}";
                            echo "</a>";
                        echo "</li>";

                        echo "<li>";
                            echo "<a>";
                                echo "Мејл: {$studentInfo['student_mejl']}";
                            echo "</a>";
                        echo "</li>";
                        
                        echo "<li>";
                            echo "<a>";
                                echo "Адреса: {$studentInfo['student_adresa']}";
                            echo "</a>";
                        echo "</li>";
                        
                        /////GODINA STUDIJA
                        $upisMaxGodina = $DBveza->upisMaxGodina($student_id);
                        echo "<li>";
                            echo "<a>";
                                echo "Година студија: {$upisMaxGodina['maxGodina']}";
                            echo "</a>";
                        echo "</li>";
                        
                        /////PROSEK OCENA
                        $studentProsek = $DBveza->studentProsek($student_id);
                        echo "<li>";
                            echo "<a>";
                                echo "Просек оцена: ".number_format((float)$studentProsek['prosekOcena'], 2, '.', '');
                            echo "</a>";
                        echo "</li>";

                        /////ESPB
                        $espb = $DBveza->sumaESPB($student_id);
                        if($espb['sumaESPB'] == 0)
                            $espb['sumaESPB'] = 0;
                        echo "<li>";
                            echo "<a>";
                                echo "Освојено ЕСПБ: {$espb['sumaESPB']}";
                            echo "</a>";
                        echo "</li>";

                        /////NOVAC
                        $novac = $DBveza->novacSuma($student_id);
                        echo "<li>";
                            echo "<a>";
                                echo "Новац на рачуну: {$novac['suma']} дин.";
                            echo "</a>";
                        echo "</li>";
?>
                    </ul>
                    <ul class="list-unstyled CTAs">
                        <li>
                            <a href="index.php" class="download">ОДЈАВА</a>
                        </li>
<?php
                        if($_SESSION["tip"] == "radnik")
                        {
?>
                            <li>
                                <a href="radnik.php" class="article">СТРАНИЦА РАДНИКА</a>
                            </li>
<?php
                        }
?>
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
                                    <a class="nav-link"><h4>СТУДЕНТ</h4></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
<?php
                    ////////////////////////////////////////////
                    /////RADNIK
                    if($_SESSION["tip"] == "radnik")
                    {
                        $narednaGodina = $upisMaxGodina['maxGodina'] + 1;
                        $uslov = $DBveza->godinaUslovESPB($narednaGodina);

                        if($espb['sumaESPB'] >= $uslov["godina_ESPB"] && $uslov != null)  //drugi uslov - za studente 4. (zavrsne) godine
                        {
                            echo "<h2>Упис студента у наредну годину студија</h2>";
                            echo "<input type='button' value='УПИС' onclick='upisNaredna({$student_id}, {$narednaGodina})'>";
                        }
                        else
                        {
                            echo "<h2>Обнова године студија</h2>";
                            echo "<input type='button' value='ОБНОВА' onclick='upisObnova({$student_id}, {$upisMaxGodina['maxGodina']})'>";
                        }
?>
                        <div class="line"></div>
<?php
                    }
                    ////////////////////////////////////////////

                    $upisInfo = $DBveza->upisInfo($student_id);
?>
                    
                    <h2>Уписи/обнове година</h2>
                    
                    <!-- <table class='table table-striped table-dark'> -->
                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                    <th scope='col'>Година</th>
                                    <th scope='col'>Датум уписа/обнове</th>
                                    <th scope='col'>Број обнова</th>
                            </tr>
                        </thead>
                        <tbody>
<?php                        
                        foreach($upisInfo as $upisGodina)
                        {
                            echo  "<tr>
                                        <td>{$upisGodina['godina_id']}</td>
                                        <td>{$upisGodina['upis_datum']}</td>
                                        <td>{$upisGodina['obnova']}</td>
                                    </tr>";
                        }
?>
                        </tbody>
                    </table>
                    
                    <div class="line"></div>
<?php
                    ////////////POLOZENI ISPITI
                    $polozeni = $DBveza->studentPolozeni($student_id);
?>
                    <h2>Положени испити</h2>
                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Шифра предмета</th>
                                <th>Назив предмета</th>
                                <th>Семестар</th>
                                <th>Оцена</th>
                                <th>Датум полагања</th>
                                <th>Професор</th>
                            </tr>
                        </thead>
                        <tbody>
<?php                    
                        foreach($polozeni as $ispit)
                        {
                            echo  "<tr>
                                    <td>{$ispit['predmet_sifra']}</td>
                                    <td>{$ispit['naziv']}</td>
                                    <td>{$ispit['predmet_semestar']}</td>
                                    <td>{$ispit['polozen_ocena']}</td>
                                    <td>{$ispit['polozen_datum']}</td>
                                    <td>{$ispit['nastavnik_ime']} {$ispit['nastavnik_prezime']}</td>
                                </tr>";
                        }
?>
                        </tbody>
                    </table>

                    <div class="line"></div>
<?php                    
                    ///////ROK
                    $rok_Novi = $DBveza->rokMaxID();
                    $datumDanas = date('Y-m-d H:i:s');
                    if(($datumDanas >= $rok_Novi['rok_prijava_pocetak']) && ($datumDanas <= $rok_Novi['rok_prijava_kraj']))
                        $rokAktivan = true;
                    else
                        $rokAktivan = false;
                    ////////////NEPOLOZENI ISPITI
                    $semestarUpisan = $upisMaxGodina['maxGodina'] * 2;
                    $nepolozeni = $DBveza->studentNepolozeni($student_id, $semestarUpisan);
?>
                    <h2>Неположени испити</h2>
                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Шифра предмета</th>
                                <th>Назив предмета</th>
                                <th>Семестар</th>
                                <th>ЕСПБ</th>
                                <th>Наставник</th>
<?php
                            if($rokAktivan && $_SESSION["tip"] == "student")
                                echo "<th>Пријава испита</th>";
?>
                            </tr>
                        </thead>
                        <tbody>
<?php
                            foreach($nepolozeni as $ispit)
                            {
                                echo  "<tr>
                                        <td>{$ispit['predmet_sifra']}</td>
                                        <td>{$ispit['naziv']}</td>
                                        <td>{$ispit['predmet_semestar']}</td>
                                        <td>{$ispit['predmet_ESPB']}</td>
                                        <td>{$ispit['nastavnik_ime']} {$ispit['nastavnik_prezime']}</td>";
                                if($rokAktivan && $_SESSION["tip"] == "student")
                                {
                                ///////////VEC PRIJAVLJEN ISPIT U AKTIVNOM ROKU?
                                $podaci = $DBveza->brojPrijavaIspita_ROK($student_id, $ispit['predmet_id'], $rok_Novi['rok_id']);
                                $brojPriava_ROK = $podaci['brojPrijava'];
                                if($brojPriava_ROK < 1)  //true
                                    echo "<td><input type='button' id={$ispit['predmet_id']} value='ПРИЈАВА' onclick='prijavaIspitaProvera({$student_id}, this.id, {$rok_Novi['rok_id']})'></td>";
                                else
                                    echo "<td>Пријављен!</td>";
                                ///////////
                                }
                                echo  "</tr>";
                            }
?>
                        </tbody>
                    </table>
<?php
                    //////
                    if(!$rokAktivan)
                    {
                        echo "<br>";
                        echo "<p>Тренутно нису отворене пријаве за наредни рок!</p>";
                    }
?>
                    <div class="line"></div>
<?php
                    if($_SESSION["tip"] == "student")
                    {
                        //////UPLATNICA
                        echo "<h2>Уплатница</h2>";
                        echo "<br>";
?>

<?php
                                    echo "<input type='file' id='filetag' onchange='uplata({$student_id}, this);'>";
                                    //echo "<img src='' id='preview'>";
?>

<?php                        
                        //echo "<br>";
                        //$uplataInfo = $DBveza->uplataInfo($student_id);
                        //echo '<img src="data:image/jpeg;base64,'.base64_encode( $uplataInfo['uplata_foto'] ).'"/><br>';
                    }
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
        <!--<form action="index.php">
                <input type="submit" value='Пријава'/>
            </form> -->
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

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
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