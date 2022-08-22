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

    <!-- GRAFIK -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <link rel="stylesheet" href="css/probaChart.css">

    <!-- FUNKCIONALNOSTI -->
    <title>Предмети</title>
    <script type="text/javascript">
        function predmetiNoviNastavnik(predmet_id)
        {
            nastavnik_id = document.getElementById("noviNastavnikCombo").value;
            
            if(nastavnik_id == " ")
            {
                alert("Потребно је означити новог наставника!");
                return false;
            }
            //PROMENA NASTAVNIKA NA PREDMETU
            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'predmetiNoviNastavnik', arguments: [predmet_id, nastavnik_id]},

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

        function grafikPodaci(predmet_id)
        {
            var godine = null;
            var sest = null;
            var sedam = null;
            var osam = null;
            var devet = null;
            var deset = null;

            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'grafikPodaci', arguments: [predmet_id]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                godine = obj.result.godine;
                                sest = obj.result._6;
                                sedam = obj.result._7;
                                osam = obj.result._8;
                                devet = obj.result._9;
                                deset = obj.result._10;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });
            
            if(godine != null)
            {
                return [godine, sest, sedam, osam, devet, deset];
                //return [[2019, 2020], [1,2], [3,4], [5,6], [7,8], [9,10]];
            }
            else
            {
                /////PREDMET NIJE POLAGAN
                return [[], [], [], [], [], []];
            }
        }
        function grafik_StackedColumn(godine, sest, sedam, osam, devet, deset)
        {
            Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Структура оцена по годинама'
                },
                xAxis: {
                    categories: godine
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Број студената'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor:
                        Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '<p style="color:{series.color};padding:0">{series.name}</p>: <b>{point.percentage:.0f}</b>%<br/>Укупан број: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [
                    {
                    name: '6',
                    data: sest

                }, {
                    name: '7',
                    data: sedam

                }, {
                    name: '8',
                    data: osam

                }, {
                    name: '9',
                    data: devet

                }, {
                    name: '10',
                    data: deset

                }]
            });
        }
        function predmetiStatistika(predmet_id)
        {
            podaci = grafikPodaci(predmet_id);
            godine = podaci[0];
            sest = podaci[1];
            sedam = podaci[2];
            osam = podaci[3];
            devet = podaci[4];
            deset = podaci[5];
            
            grafik_StackedColumn(godine, sest, sedam, osam, devet, deset);
        }
    </script>
</head>
<body>
<?php
    ///////////////PROVERA DA NIJE UKUCAN SAMO LINK U BROWSERU
    $prijavaRegularna = false;
    if(isset($_SESSION["tip"]))
    {
        if($_SESSION["tip"] == "nastavnik" || $_SESSION["tip"] == "radnik")  //Ako je uspostavljena sesija
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
                
                if($_SESSION["tip"] == "nastavnik")
                {
                    ////////////INFORMACIJE O NASTAVNIKU
                    $Info = $DBveza->nastavnikInfo($_SESSION["tip_id"]);
                    $ime = $Info['nastavnik_ime'];
                    $prezime = $Info['nastavnik_prezime'];
                    $tel = $Info['nastavnik_tel'];
                    $mejl = $Info['nastavnik_mejl'];
                    //
                    $foto = $Info['nastavnik_foto'];
                }
                elseif($_SESSION["tip"] == "radnik")
                {
                    ////////////INFORMACIJE O RADNIKU
                    $Info = $DBveza->radnikInfo($_SESSION["tip_id"]);
                    $ime = $Info['radnik_ime'];
                    $prezime = $Info['radnik_prezime'];
                    $tel = $Info['radnik_tel'];
                    $mejl = $Info['radnik_mejl'];
                }
                
                if ($Info)
                {
?>
                    <div class='sidebar-header'>
<?php                    
                        echo "<h2>{$ime} {$prezime}</h2>";
?>
                    </div>
                    <ul class="list-unstyled components">
<?php
                        if($_SESSION["tip"] == "nastavnik")
                        {
                            echo '<img src="data:image/jpeg;base64,'.base64_encode( $foto ).'" class="rounded-circle" width="250" height="250"/><br><br>';
                        }
                        echo "<li>";
                            echo "<a>";
                                echo "Телефон: {$tel}";
                            echo "</a>";
                        echo "</li>";

                        echo "<li>";
                            echo "<a>";
                                echo "Мејл: {$mejl}";
                            echo "</a>";
                        echo "</li>";

?>
                    </ul>
                    <ul class="list-unstyled CTAs">
                        <li>
                            <a href="index.php" class="download">ОДЈАВА</a>
                        </li>
                        <li>
<?php
                        if($_SESSION["tip"] == "nastavnik")
                        {
?>
                            <a href="nastavnik.php" class="article">СТРАНИЦА НАСТАВНИКА</a>
<?php
                        }
                        elseif($_SESSION["tip"] == "radnik")
                        {
?>
                            <a href="radnik.php" class="article">СТРАНИЦА РАДНИКА</a>
                            <a href="admin.php" class="article">НОВИ ЧЛАНОВИ</a>
<?php
                        }
?>
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
                                    <a class="nav-link"><h4>ПРЕДМЕТИ</h4></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
<?php
                    if($_SESSION["tip"] == "nastavnik")
                    {
                        ////////////PREDMETI NASTAVNIKA
                        $predmeti = $DBveza->predmetiInfo_Nastavnik($_SESSION["tip_id"]);
                    }
                    elseif($_SESSION["tip"] == "radnik")
                    {
                        ////////////SVI PREDMETI
                        $predmeti = $DBveza->predmetiInfo();
                    }
?>
                    <h2>Списак предмета</h2>

                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Шифра предмета</th>
                                <th>Назив предмета</th>
                                <th>Семестар</th>
                                <th>ЕСПБ</th>
<?php
                            if($_SESSION["tip"] == "radnik")
                            {
                                echo "<th>Наставник</th>";
                                echo "<th>Промена наставника</th>";
                            }
?>
                                <th>Статистика</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                            foreach($predmeti as $predmet)
                            {
                                echo  "<tr>
                                        <td>{$predmet['predmet_sifra']}</td>
                                        <td>{$predmet['naziv']}</td>
                                        <td>{$predmet['predmet_semestar']}</td>
                                        <td>{$predmet['predmet_ESPB']}</td>";
                                if($_SESSION["tip"] == "radnik")
                                {
                                    echo "<td>{$predmet['nastavnik_ime']} {$predmet['nastavnik_prezime']}</td>";
                                    echo "<td><input type='button' id={$predmet['predmet_id']} value='ПРОМЕНА' onclick='predmetiNoviNastavnik(this.id)'></td>";
                                ///////////
                                }
                                    echo "<td><input type='button' id={$predmet['predmet_id']} value='ПРИКАЗ' onclick='predmetiStatistika(this.id)'></td>";
                                    /*
                                    $polozenGodine = $DBveza->polozenGodine($predmet['predmet_id']);
                                    echo "<td><select id={$predmet['predmet_id']} onchange='predmetiStatistika(this.id, this.value)'>
                                            <option value=' ' selected='selected'>година</option>";
                                        foreach($polozenGodine as $polozenGodina)
                                        {
                                            echo "<option value={$polozenGodina['godina']}>{$polozenGodina['godina']}</option>";
                                        }
                                    echo "</select><td>";
                                    */
                                echo  "</tr>";
                            }
?>
                        </tbody>
                    </table>
<?php
                    if($_SESSION["tip"] == "radnik")
                    {
                        $nastavnici = $DBveza->nastavnikInfo_Combo();
                        if($nastavnici)
                        {
?>
                            <label>Избор новог наставника:</label><br>
                            <select id="noviNastavnikCombo">
                                <option value=" " selected="selected">означити</option>
<?php
                                foreach($nastavnici as $nastavnik)
                                    echo "<option value={$nastavnik['nastavnik_id']}>{$nastavnik['nastavnik_ime']} {$nastavnik['nastavnik_prezime']}</option>";
?>
                            </select>
<?php
                        }
                    }
?>
                    <div class="line"></div>
                            
                    <h2>Статистика</h2>
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description">
                            График приказује структуру оцена по годинама за посматрани предмет.
                        </p>
                    </figure>
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