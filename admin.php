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
    <title>Админ</title>
    <script type="text/javascript">
    
    /////PROVERA ZA SLIKU
    function isFileImage(file) {
        const acceptedImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
        return file && acceptedImageTypes.includes(file['type'])

        //return file && file['type'].split('/')[0] === 'image';

    }

    /////NOVI STUDENT
    function noviStudent()
    {
        //preview = document.getElementById("preview");

        input = document.getElementById("student_foto");

        student_ime = document.getElementById("student_ime").value;
        student_prezime = document.getElementById("student_prezime").value;
        student_tel = document.getElementById("student_tel").value;
        student_adresa = document.getElementById("student_adresa").value;
        student_mejl = document.getElementById("student_mejl").value;

        student_kor_ime = document.getElementById("student_kor_ime").value;
        student_sifra = document.getElementById("student_sifra").value;

        if (student_ime == "" || student_prezime == "" || student_tel == "" || student_adresa == "" || student_mejl == "" || student_kor_ime == "" || student_sifra == "")
        {
            alert("Потребно је попунити поља!");
            return false;
        }

        var broj_indeksa = null;
        var student_id = null;

        /////NOVI BROJ INDEKSA I STUDENT_ID
        jQuery.ajax({
            async: false,
            type: "POST",
            global: false,
            url: 'PHP_jQuery.php',
            dataType: 'json',
            data: {functionname: 'noviBrojIndeksa', arguments: [0]},

            success: function (obj, textstatus) {
                        if( !('error' in obj) ) {
                            broj_indeksa = obj.result.broj_indeksa;
                            //student_id = obj.result.student_id;
                        }
                        else {
                            console.log(obj.error);
                        }
                    }
        });

        /////NOVA SIFRA

        //PROVERA DA LI POSTOJE KORISNICKO IME ILI SIFRA U BAZI
        var novaSifraProveraBool = null;
        jQuery.ajax({
            async: false,
            type: "POST",
            global: false,
            url: 'PHP_jQuery.php',
            dataType: 'json',
            data: {functionname: 'novaSifraProvera', arguments: [student_kor_ime, student_sifra]},

            success: function (obj, textstatus) {
                        if( !('error' in obj) ) {
                            novaSifraProveraBool = obj.result;
                        }
                        else {
                            console.log(obj.error);
                        }
                    }
        });
        if(novaSifraProveraBool == true)
        {
            var reader;
            if (input.files && input.files[0])
            {
                reader = new FileReader();                
                reader.onload = function(e)
                {
                    //preview.setAttribute('src', e.target.result);
                    if(isFileImage(input.files[0])) //e.target.result
                    {
                        jQuery.ajax({
                            async: false,
                            type: "POST",
                            global: false,
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'noviStudent', arguments: [student_ime, student_prezime, student_tel, student_adresa, student_mejl, broj_indeksa, e.target.result]},

                            success: function (obj, textstatus) {
                                        if( !('error' in obj) ) {
                                            //yourVariable = obj.result;
                                            student_id = obj.result;    //VRACA POSLEDNJI UNET ID
                                        }
                                        else {
                                            console.log(obj.error);
                                        }
                                    }
                        });

                        jQuery.ajax({
                            type: "POST",
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'novaSifra', arguments: [student_kor_ime, student_sifra, "student", student_id]},

                            success: function (obj, textstatus) {
                                        if( !('error' in obj) ) {
                                            yourVariable = obj.result;
                                        }
                                        else {
                                            console.log(obj.error);
                                        }
                                    }
                        });
                        jQuery.ajax({
                            type: "POST",
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'noviNovac', arguments: [student_id]},

                            success: function (obj, textstatus) {
                                        if( !('error' in obj) ) {
                                            yourVariable = obj.result;
                                        }
                                        else {
                                            console.log(obj.error);
                                        }
                                    }
                        });
                        jQuery.ajax({
                            type: "POST",
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'upisNaredna', arguments: [student_id, 1]},

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
                        alert('Број индекса унетог студента: '.concat(broj_indeksa));
                    }
                    else
                    {
                        alert("Унете датотека није слика!");
                        return false;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
            else
            {
                alert('Није унета слика!');
                return false;
            }

            location.reload();
        }
        else
        {
            alert("Корисничко име или шифра су заузети!");
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////
    /////NOVI NASTAVNIK
    function noviNastavnik()
    {
        input = document.getElementById("nastavnik_foto");

        nastavnik_ime = document.getElementById("nastavnik_ime").value;
        nastavnik_prezime = document.getElementById("nastavnik_prezime").value;
        nastavnik_tel = document.getElementById("nastavnik_tel").value;
        nastavnik_mejl = document.getElementById("nastavnik_mejl").value;

        nastavnik_kor_ime = document.getElementById("nastavnik_kor_ime").value;
        nastavnik_sifra = document.getElementById("nastavnik_sifra").value;

        if (nastavnik_ime == "" || nastavnik_prezime == "" || nastavnik_tel == "" || nastavnik_mejl == "" || nastavnik_kor_ime == "" || nastavnik_sifra == "")
        {
            alert("Потребно је попунити поља!");
            return false;
        }

        var nastavnik_id = null;

        //PROVERA DA LI POSTOJE KORISNICKO IME ILI SIFRA U BAZI
        var novaSifraProveraBool = null;
        jQuery.ajax({
            async: false,
            type: "POST",
            global: false,
            url: 'PHP_jQuery.php',
            dataType: 'json',
            data: {functionname: 'novaSifraProvera', arguments: [nastavnik_kor_ime, nastavnik_sifra]},

            success: function (obj, textstatus) {
                        if( !('error' in obj) ) {
                            novaSifraProveraBool = obj.result;
                        }
                        else {
                            console.log(obj.error);
                        }
                    }
        });
        if(novaSifraProveraBool == true)
        {
            var reader;
            if (input.files && input.files[0])
            {
                reader = new FileReader();                
                reader.onload = function(e)
                {
                    if(isFileImage(input.files[0])) //e.target.result
                    {
                        jQuery.ajax({
                            async: false,
                            type: "POST",
                            global: false,
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'noviNastavnik', arguments: [nastavnik_ime, nastavnik_prezime, nastavnik_tel, nastavnik_mejl, e.target.result]},

                            success: function (obj, textstatus) {
                                        if( !('error' in obj) ) {
                                            nastavnik_id = obj.result;    //VRACA POSLEDNJI UNET ID
                                        }
                                        else {
                                            console.log(obj.error);
                                        }
                                    }
                        });

                        jQuery.ajax({
                            type: "POST",
                            url: 'PHP_jQuery.php',
                            dataType: 'json',
                            data: {functionname: 'novaSifra', arguments: [nastavnik_kor_ime, nastavnik_sifra, "nastavnik", nastavnik_id]},

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
                        alert('Унет нови наставник!');
                    }
                    else
                    {
                        alert("Унете датотека није слика!");
                        return false;
                    }
                    
                }
                reader.readAsDataURL(input.files[0]);
            }
            else
            {
                alert('Није унета слика!');
                return false;
            }

            location.reload();
        }
        else
        {
            alert("Корисничко име или шифра су заузети!");
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////
    /////NOVI RADNIK
    function noviRadnik()
    {
        radnik_ime = document.getElementById("radnik_ime").value;
        radnik_prezime = document.getElementById("radnik_prezime").value;
        radnik_tel = document.getElementById("radnik_tel").value;
        radnik_mejl = document.getElementById("radnik_mejl").value;

        radnik_kor_ime = document.getElementById("radnik_kor_ime").value;
        radnik_sifra = document.getElementById("radnik_sifra").value;

        if (radnik_ime == "" || radnik_prezime == "" || radnik_tel == "" || radnik_mejl == "" || radnik_kor_ime == "" || radnik_sifra == "")
        {
            alert("Потребно је попунити поља!");
            return false;
        }

        var radnik_id = null;

        //PROVERA DA LI POSTOJE KORISNICKO IME ILI SIFRA U BAZI
        var novaSifraProveraBool = null;
        jQuery.ajax({
            async: false,
            type: "POST",
            global: false,
            url: 'PHP_jQuery.php',
            dataType: 'json',
            data: {functionname: 'novaSifraProvera', arguments: [radnik_kor_ime, radnik_sifra]},

            success: function (obj, textstatus) {
                        if( !('error' in obj) ) {
                            novaSifraProveraBool = obj.result;
                        }
                        else {
                            console.log(obj.error);
                        }
                    }
        });
        if(novaSifraProveraBool == true)
        {
            jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'noviRadnik', arguments: [radnik_ime, radnik_prezime, radnik_tel, radnik_mejl]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                radnik_id = obj.result;    //VRACA POSLEDNJI UNET ID
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

            jQuery.ajax({
                type: "POST",
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'novaSifra', arguments: [radnik_kor_ime, radnik_sifra, "radnik", radnik_id]},

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
            alert('Унет нови радник!');

            location.reload();
        }
        else
        {
            alert("Корисничко име или шифра су заузети!");
            return false;
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
        if($_SESSION["tip"] == "admin" || $_SESSION["tip"] == "radnik")  //Ako je uspostavljena sesija
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
                
                if($_SESSION["tip"] == "admin")
                {
                    ////////////INFORMACIJE O ADMINU
                    $Info = $DBveza->adminInfo($_SESSION["tip_id"]);
                    $ime = $Info['admin_ime'];
                    $prezime = $Info['admin_prezime'];
                    $tel = $Info['admin_tel'];
                    $mejl = $Info['admin_mejl'];
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
<?php
                        if($_SESSION["tip"] == "radnik")
                        {
?>
                            <li>
                                <a href="predmeti.php" class="article">ПРЕДМЕТИ</a>
                            </li>
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
<?php
                                    if($_SESSION["tip"] == "admin")
                                    {
?>
                                        <a class="nav-link"><h4>АДМИН</h4></a>
<?php
                                    }
?>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <!-- NOVI STUDENT -->
                    <h2>Нови студент</h2>

                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Име</th>
                                <th>Презиме</th>
                                <th>Број телефона</th>
                                <th>Адреса</th>
                                <th>Фотографија</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="student_ime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="student_prezime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="student_tel" size="10" autocomplete="off"></td>
                                <td><input type="text" id="student_adresa" size="10" autocomplete="off"></td>
                                <td><input type='file' id='student_foto'></td>
                            </tr>
                        </tbody>
                        <thead class='thead-light'>
                            <tr>
                                <th>Мејл</th>
                                <th>Корисничко име</th>
                                <th>Шифра</th>
                                <th>Унос новог студента</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="student_mejl" size="10" autocomplete="off"></td>
                                <td><input type="text" id="student_kor_ime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="student_sifra" size="10" autocomplete="off"></td>
                                <td><input type="button" value='УНОС' onClick="noviStudent();" /></td>
                            </tr>
                        </tbody>
                    </table>

                    <!--
                    <label>Име: </label>
                    <input type="text" id="student_ime">
                    <label>Презиме: </label>
                    <input type="text" id="student_prezime">
                    <label>Број телефона: </label>
                    <input type="text" id="student_tel">
                    <label>Адреса: </label>
                    <input type="text" id="student_adresa"><br><br>
                    <label>Мејл: </label>
                    <input type="text" id="student_mejl">

                    <label>Фотографија: </label>
                    <input type='file' id='student_foto'><br><br>

                    <label>Корисничко име: </label>
                    <input type="text" id="student_kor_ime"><br><br>
                    <label>Шифра: </label>
                    <input type="text" id="student_sifra"><br><br>

                    <input type="button" value='Унос студента' onClick="noviStudent();" />
                    -->

                    <div class="line"></div>
                    
                    <!-- NOVI NASTAVNIK -->
                    <h2>Нови наставник</h2>

                    <table class='table'>
                        <thead class='thead-light'>
                            <tr>
                                <th>Име</th>
                                <th>Презиме</th>
                                <th>Број телефона</th>
                                <th>Фотографија</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="nastavnik_ime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="nastavnik_prezime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="nastavnik_tel" size="10" autocomplete="off"></td>
                                <td><input type='file' id='nastavnik_foto'></td>
                            </tr>
                        </tbody>
                        <thead class='thead-light'>
                            <tr>
                                <th>Мејл</th>
                                <th>Корисничко име</th>
                                <th>Шифра</th>
                                <th>Унос новог наставника</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="nastavnik_mejl" size="10" autocomplete="off"></td>
                                <td><input type="text" id="nastavnik_kor_ime" size="10" autocomplete="off"></td>
                                <td><input type="text" id="nastavnik_sifra" size="10" autocomplete="off"></td>
                                <td><input type="button" value='УНОС' onClick="noviNastavnik();" /></td>
                            </tr>
                        </tbody>
                    </table>

<?php
                        if($_SESSION["tip"] == "admin")
                        {
?>
                            <!-- NOVI RADNIK -->
                    <div class="line"></div>
                            <h2>Нови радник</h2>

                            <table class='table'>
                                <thead class='thead-light'>
                                    <tr>
                                        <th>Име</th>
                                        <th>Презиме</th>
                                        <th>Број телефона</th>
                                        <th>Мејл</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="radnik_ime" size="10" autocomplete="off"></td>
                                        <td><input type="text" id="radnik_prezime" size="10" autocomplete="off"></td>
                                        <td><input type="text" id="radnik_tel" size="10" autocomplete="off"></td>
                                        <td><input type="text" id="radnik_mejl" size="10" autocomplete="off"></td>
                                    </tr>
                                </tbody>
                                <thead class='thead-light'>
                                    <tr>
                                        <th>Корисничко име</th>
                                        <th>Шифра</th>
                                        <th>Унос новог радника</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="radnik_kor_ime" size="10" autocomplete="off"></td>
                                        <td><input type="text" id="radnik_sifra" size="10" autocomplete="off"></td>
                                        <td><input type="button" value='УНОС' onClick="noviRadnik();" /></td>
                                    </tr>
                                </tbody>
                            </table>        
<?php
                        }
                    //<img src='' id='preview'>
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