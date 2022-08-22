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
    <title>Наставник</title>
    <script type="text/javascript">
        function noviZapisnik(prijavaIspita_id, rok_id)
        {
            var student_id = null;
            var predmet_id = null;

            ocena = document.getElementById("ocena").value;

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'prijavaIspitaInfo', arguments: [prijavaIspita_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
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
                if (ocena == "" || ocena<5 || ocena>10 || !$.isNumeric( ocena ))
                {
                    alert("Потребно је унети цео број између 5 и 10!");
                    return false;
                }
                /*
                if (ocena == 5)
                {
                    alert("Студент није положио предмет");
                    return false;
                }
                */
                else
                {
                    jQuery.ajax({
                        type: "POST",
                        url: 'PHP_jQuery.php',
                        dataType: 'json',
                        data: {functionname: 'noviZapisnik', arguments: [ocena, student_id, predmet_id, rok_id]},

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
        }    
    </script>
</head>
<body>
<?php
    ///////////////PROVERA DA NIJE UKUCAN SAMO LINK U BROWSERU
    $prijavaRegularna = false;
    if(isset($_SESSION["tip"]))
    {
        if($_SESSION["tip"] == "nastavnik")  //Ako je uspostavljena sesija
            $prijavaRegularna = true;
    }

    if($prijavaRegularna)  //Ako je uspostavljena sesija
    {
?>
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar">
<?php
                require_once "DB_PDO.php";
                
                $DBveza = new DB_PDO();
                
                ////////////INFORMACIJE O RADNIKU
                $nastavnikInfo = $DBveza->nastavnikInfo($_SESSION["tip_id"]);
                if ($nastavnikInfo)
                {
?>
                    <div class='sidebar-header'>
<?php                    
                        echo "<h2>{$nastavnikInfo['nastavnik_ime']} {$nastavnikInfo['nastavnik_prezime']}</h2>";
?>
                    </div>
                    <ul class="list-unstyled components">
<?php

                    echo '<img src="data:image/jpeg;base64,'.base64_encode( $nastavnikInfo['nastavnik_foto'] ).'" class="rounded-circle" width="250" height="250"/><br><br>';

                    echo "<li>";
                            echo "<a>";
                                echo "Телефон: {$nastavnikInfo['nastavnik_tel']}";
                            echo "</a>";
                        echo "</li>";

                        echo "<li>";
                            echo "<a>";
                                echo "Мејл: {$nastavnikInfo['nastavnik_mejl']}";
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
                                    <a class="nav-link"><h4>НАСТАВНИК</h4></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
<?php
                    /////PRIJAVE ISPITA
                    //ROK
                    $rok_Novi = $DBveza->rokMaxID();

                    $prijavljeni = $DBveza->nastavnikPrijavljeni($_SESSION["tip_id"], $rok_Novi["rok_id"]);
?>
                    <h2>Пријаве испита студената</h2>
                    <table class='table'>
                        <thead class='thead-light'>                            
                            <tr>
                                <th>Шифра предмета</th>
                                <th>Назив предмета</th>
                                <th>Семестар</th>
                                <th>Број индекса</th>
                                <th>Име и презиме</th>
                                <th>Унос оцене</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                        foreach($prijavljeni as $ispit)
                        {
                            echo  "<tr>
                                    <td>{$ispit['predmet_sifra']}</td>
                                    <td>{$ispit['naziv']}</td>
                                    <td>{$ispit['predmet_semestar']}</td>
                                    <td>{$ispit['broj_indeksa']}</td>
                                    <td>{$ispit['student_ime']} {$ispit['student_prezime']}</td>
                                    <td>
                                        <input type='button' id={$ispit['prijavaIspita_id']} value='УНОС' onclick='noviZapisnik(this.id, {$rok_Novi["rok_id"]})'>
                                    </td>";
                            echo  "</tr>";
                        }
?>
                        </tbody>
                    </table><br>

                    <label>Остварена оцена на испиту:</label><br><br>
                    <input type="text" id="ocena" placeholder="оцена" autocomplete="off"><br><br>

                    <div class="line"></div>
<?php
                    //////
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