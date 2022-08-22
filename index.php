<!DOCTYPE html>
<html>
<?php
    session_start();
	$_SESSION = array();
?>
<head>
	
	<title>Пријава</title>

    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="prijava/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="prijava/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="prijava/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="prijava/css/util.css">
	<link rel="stylesheet" type="text/css" href="prijava/css/main.css">
<!--===============================================================================================-->

	<script type="text/javascript">
        function validacija_submit() 
        {
            kor_ime = document.getElementById("kor_ime").value;
            sifra  = document.getElementById("sifra").value;
            formaPrijava  = document.getElementById("formaPrijava");
            if (kor_ime == "" || sifra == "") 
            {
                alert("Потребно је попунити поља");
                return false;
            }
			
			var tip = null;

			jQuery.ajax({
                async: false,
                type: "POST",
                global: false,
                url: 'PHP_jQuery.php',
                dataType: 'json',
                data: {functionname: 'proveraSifra', arguments: [kor_ime, sifra]},

                success: function (obj, textstatus) {
                            if( !('error' in obj) ) {
                                tip = obj.result;
                            }
                            else {
                                console.log(obj.error);
                            }
                        }
            });

			if(tip != null && tip != "Nije proslo")
			{
				if(tip == "student")
				{
					//window.location = "student.php";
					document.formaPrijava.action = "student.php";
				}
				else if(tip == "nastavnik")
				{
					document.formaPrijava.action = "nastavnik.php";
				}
				else if(tip == "radnik")
				{
					document.formaPrijava.action = "radnik.php";
				}
				else if(tip == "admin")
				{
					document.formaPrijava.action = "admin.php";
				}
				formaPrijava.submit();
			}
			else
			{
				alert("Погрешно унети корисничко име или шифра!");
			}
        }
    </script>
</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" name="formaPrijava" id="formaPrijava" method="POST">
					<span class="login100-form-title p-b-26">
						Студентска служба
					</span>
					<!--
					<span class="login100-form-title p-b-48">
						<i class="zmdi zmdi-font"></i>
					</span>
					-->

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="kor_ime" id="kor_ime" autocomplete="off">
						<span class="focus-input100" data-placeholder="Корисничко име"></span>
					</div>

					<div class="wrap-input100 validate-input">
						<!--
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						-->
						<input class="input100" type="text" name="sifra" id="sifra" autocomplete="off">
						<span class="focus-input100" data-placeholder="Шифра"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" onClick="validacija_submit();">
								Пријава
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>


<!--===============================================================================================-->
	<script src="prijava/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="prijava/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="prijava/vendor/bootstrap/js/popper.js"></script>
	<script src="prijava/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="prijava/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="prijava/vendor/daterangepicker/moment.min.js"></script>
	<script src="prijava/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="prijava/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="prijava/js/main.js"></script>
</body>
</html>