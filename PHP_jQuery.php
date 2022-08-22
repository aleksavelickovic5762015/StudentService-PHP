<?php
    header('Content-Type: application/json');

    $aResult = array();

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            /////////////////////////////////////////////////////////////////////////////////
            /////INDEX
            case 'proveraSifra':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $kor_ime = $_POST['arguments'][0];
                    $sifra = $_POST['arguments'][1];
                    ////                    
                    $podaci = $DBveza->proveraSifra($kor_ime, $sifra);
                    if ($podaci)
                    {
                        /////SESIJA
                        session_start();
                        $_SESSION["tip"] = $podaci['tip'];
                        $_SESSION["tip_id"] = $podaci['tip_id'];

                        $aResult['result'] = $podaci['tip'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;

            /////////////////////////////////////////////////////////////////////////////////
            /////STUDENT
            case 'prijavaIspita':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 3) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    $predmet_id = $_POST['arguments'][1];
                    $rok_id = $_POST['arguments'][2];
                    $prijavaIspita_datum = date('Y-m-d H:i:s');
                    //
                    $prijavaBool = $DBveza->prijavaIspita($student_id, $predmet_id, $rok_id, $prijavaIspita_datum);
                    
                    if($prijavaBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'brojPrijavaIspita':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    $predmet_id = $_POST['arguments'][1];
                    //
                    $podaci = $DBveza->brojPrijavaIspita($student_id, $predmet_id);
                    
                    if($podaci)
                        $aResult['result'] = $podaci['brojPrijava'];
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'novacSuma':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    //
                    $podaci = $DBveza->novacSuma($student_id);
                    
                    if($podaci)
                        $aResult['result'] = $podaci['suma'];
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'naplataPrijave':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    //
                    $naplataBool = $DBveza->naplataPrijave($student_id);
                    
                    if($naplataBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'uplata':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $student_id = $_POST['arguments'][0];
                    //
                    $slika = $_POST['arguments'][1];
                    $uplata_foto = addslashes(file_get_contents($slika));
                    //
                    ////
                    $uplataBool = $DBveza->uplata($student_id, $uplata_foto);
                    
                    if($uplataBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            /////////////////////////////////////////////////////////////////////////////////
            /////RADNIK
            case 'uplataPrikaz':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $uplata_id = $_POST['arguments'][0];
                    ////
                    $podaci = $DBveza->uplataPrikaz($uplata_id);
                    
                    if($podaci)
                    {
                        $aResult['result'] = array();
                        $aResult['result']['uplata_foto'] = base64_encode($podaci['uplata_foto']);
                        $aResult['result']['student_id'] = $podaci['student_id'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;
            case 'novacAzuriranje':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    $iznos = $_POST['arguments'][1];
                    //
                    $azuriranjeBool = $DBveza->novacAzuriranje($student_id, $iznos);
                    
                    if($azuriranjeBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'uplataBrisanje':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $uplata_id = $_POST['arguments'][0];
                    //
                    $brisanjeBool = $DBveza->uplataBrisanje($uplata_id);
                    
                    if($brisanjeBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'studentPretraga':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $broj_indeksa = $_POST['arguments'][0];
                    ////
                    $podaci = $DBveza->studentPretraga($broj_indeksa);
                    
                    if($podaci)
                    {
                        $aResult['result'] = $podaci['student_id'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;
            
            case 'upisNaredna':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $student_id = $_POST['arguments'][0];
                    $godina_id = $_POST['arguments'][1];
                    $upis_datum = date('Y-m-d H:i:s');
                    ////
                    $upisNarednaBool = $DBveza->upisNaredna($student_id, $godina_id, $upis_datum);
                    
                    if($upisNarednaBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'upisObnova':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $student_id = $_POST['arguments'][0];
                    $godina_id = $_POST['arguments'][1];
                    $upis_datum = date('Y-m-d H:i:s');
                    //
                    $upisObnovaBool = $DBveza->upisObnova($student_id, $godina_id, $upis_datum);
                    
                    if($upisObnovaBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            
            case 'noviRok':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 5) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    /*
                    $rok_prijava_pocetak = date("Y-m-d H:i:s", strtotime($_POST['arguments'][0]));
                    $rok_prijava_kraj = date("Y-m-d H:i:s", strtotime($_POST['arguments'][1]));
                    $rok_pocetak = date("Y-m-d H:i:s", strtotime($_POST['arguments'][2]));
                    $rok_kraj = date("Y-m-d H:i:s", strtotime($_POST['arguments'][3]));
                    */
                    $rok_prijava_pocetak = $_POST['arguments'][0];
                    $rok_prijava_kraj = $_POST['arguments'][1];
                    $rok_pocetak = $_POST['arguments'][2];
                    $rok_kraj = $_POST['arguments'][3];

                    $rok_naziv = $_POST['arguments'][4];
                    ////
                    $noviRokBool = $DBveza->noviRok($rok_prijava_pocetak, $rok_prijava_kraj, $rok_pocetak, $rok_kraj, $rok_naziv);
                    
                    if($noviRokBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;

            case 'zapisnikInfo':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $zapisnik_id = $_POST['arguments'][0];
                    ////
                    $podaci = $DBveza->zapisnikInfo($zapisnik_id);
                    
                    if($podaci)
                    {
                        $aResult['result'] = array();
                        $aResult['result']['zapisnik_ocena'] = $podaci['zapisnik_ocena'];
                        $aResult['result']['zapisnik_datum'] = $podaci['zapisnik_datum'];
                        $aResult['result']['student_id'] = $podaci['student_id'];
                        $aResult['result']['predmet_id'] = $podaci['predmet_id'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;            
            case 'noviPolozen':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $polozen_ocena = $_POST['arguments'][0];
                    $polozen_datum = $_POST['arguments'][1];
                    $student_id = $_POST['arguments'][2];
                    $predmet_id = $_POST['arguments'][3];
                    ////
                    $noviPolozenBool = $DBveza->noviPolozen($polozen_ocena, $polozen_datum, $student_id, $predmet_id);
                    
                    if($noviPolozenBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            ///////////////////////////////////////////////////////////////////////////
            /////NASTAVNIK
            case 'prijavaIspitaInfo':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $prijavaIspita_id = $_POST['arguments'][0];
                    ////
                    $podaci = $DBveza->prijavaIspitaInfo($prijavaIspita_id);
                    
                    if($podaci)
                    {
                        $aResult['result'] = array();
                        $aResult['result']['student_id'] = $podaci['student_id'];
                        $aResult['result']['predmet_id'] = $podaci['predmet_id'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;
            
            case 'noviZapisnik':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $zapisnik_ocena = $_POST['arguments'][0];
                    $student_id = $_POST['arguments'][1];
                    $predmet_id = $_POST['arguments'][2];
                    $rok_id = $_POST['arguments'][3];
                    $zapisnik_datum = date('Y-m-d H:i:s');
                    ////
                    $noviZapisnikBool = $DBveza->noviZapisnik($zapisnik_ocena, $zapisnik_datum, $student_id, $predmet_id, $rok_id);
                    
                    if($noviZapisnikBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            /////////////////////////////////////////////////////////////////////////////////
            /////ADMIN
            case 'noviBrojIndeksa':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    
                    ////
                    $studentMaxID = $DBveza->studentMaxID();
                    preg_match('([^\/]+$)', $studentMaxID['broj_indeksa'], $regex);
                    $godina = $regex[0];
                    preg_match('(^[^/]+)', $studentMaxID['broj_indeksa'], $regex);
                    $indeks = $regex[0];

                    if(date('Y') == $godina)
                    {
                        $indeks = $indeks + 1;
                        $broj_indeksa = $indeks."/".$godina;
                    }
                    else
                    {
                        $indeks = 1;
                        $broj_indeksa = $indeks."/".date('Y');
                    }
                    
                    if($studentMaxID)
                    {
                        $aResult['result']["broj_indeksa"] = $broj_indeksa;
                        $aResult['result']["student_id"] = $studentMaxID['student_id'];
                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;
            case 'noviStudent':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 7) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $student_ime = $_POST['arguments'][0];
                    $student_prezime = $_POST['arguments'][1];
                    $student_tel = $_POST['arguments'][2];
                    $student_adresa = $_POST['arguments'][3];
                    $student_mejl = $_POST['arguments'][4];
                    $broj_indeksa = $_POST['arguments'][5];
                    //
                    $slika = $_POST['arguments'][6];
                    $student_foto = addslashes(file_get_contents($slika));
                    //
                    ////
                    $noviStudent_ID = $DBveza->noviStudent($student_ime, $student_prezime, $student_tel, $student_adresa, $student_mejl, $broj_indeksa, $student_foto);
                    
                    if($noviStudent_ID)
                        $aResult['result'] = $noviStudent_ID;
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'novaSifra':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $kor_ime = $_POST['arguments'][0];
                    $sifra = $_POST['arguments'][1];
                    $tip = $_POST['arguments'][2];
                    $tip_id = $_POST['arguments'][3];
                    ////
                    $novaSifraBool = $DBveza->novaSifra($kor_ime, $sifra, $tip, $tip_id);
                    
                    if($novaSifraBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'novaSifraProvera':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $kor_ime = $_POST['arguments'][0];
                    $sifra = $_POST['arguments'][1];
                    ////
                    $novaSifraProveraBool = $DBveza->novaSifraProvera($kor_ime, $sifra);
                    
                    if($novaSifraProveraBool == null)
                        $aResult['result'] = true;
                    else
                        $aResult['result'] = false;
               }
               break;
            case 'noviNovac':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $student_id = $_POST['arguments'][0];
                    ////
                    $noviNovacBool = $DBveza->noviNovac($student_id);
                    
                    if($noviNovacBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;

            case 'noviNastavnik':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 5) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $nastavnik_ime = $_POST['arguments'][0];
                    $nastavnik_prezime = $_POST['arguments'][1];
                    $nastavnik_tel = $_POST['arguments'][2];
                    $nastavnik_mejl = $_POST['arguments'][3];
                    //
                    $slika = $_POST['arguments'][4];
                    $nastavnik_foto = addslashes(file_get_contents($slika));
                    //
                    ////
                    $noviNastavnik_ID = $DBveza->noviNastavnik($nastavnik_ime, $nastavnik_prezime, $nastavnik_tel, $nastavnik_mejl, $nastavnik_foto);
                    
                    if($noviNastavnik_ID)
                        $aResult['result'] = $noviNastavnik_ID;
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            case 'noviRadnik':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $radnik_ime = $_POST['arguments'][0];
                    $radnik_prezime = $_POST['arguments'][1];
                    $radnik_tel = $_POST['arguments'][2];
                    $radnik_mejl = $_POST['arguments'][3];
                    ////
                    $noviRadnik_ID = $DBveza->noviRadnik($radnik_ime, $radnik_prezime, $radnik_tel, $radnik_mejl);
                    
                    if($noviRadnik_ID)
                        $aResult['result'] = $noviRadnik_ID;
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            /////////////////////////////////////////////////////////////////////////////////
            /////PREDMETI
            case 'predmetiNoviNastavnik':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    //
                    $predmet_id = $_POST['arguments'][0];
                    $nastavnik_id = $_POST['arguments'][1];
                    //
                    $noviNastavnikBool = $DBveza->predmetiNoviNastavnik($predmet_id, $nastavnik_id);
                    
                    if($noviNastavnikBool)
                        $aResult['result'] = "Proslo";
                    else
                        $aResult['result'] = "Nije proslo";
               }
               break;
            
            case 'grafikPodaci':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) )
               {
                   $aResult['error'] = 'Error in arguments!';
               }
               else
               {
                    require_once "DB_PDO.php";
                    $DBveza = new DB_PDO();
                    ////
                    $predmet_id = $_POST['arguments'][0];
                    ////
                    $polozenGodine = $DBveza->polozenGodine($predmet_id);
                    
                    if($polozenGodine)
                    {
                        $aResult['result']["godine"] = array();
                        foreach($polozenGodine as $godina)
                            $aResult['result']['godine'][] = $godina['godina'];
                        
                        /////VRATI BR STUDENATA KOJI SU POLOZILI SVAKOM OD OCENA KROZ GODINE U KOJIMA JE POLAGAN DATI PREDMET
                        $ocena = 6;
                        while($ocena <= 10)
                        {
                            $indeks = "_".$ocena;
                            $aResult['result'][$indeks] = array();
                            foreach($aResult['result']['godine'] as $godina)
                            {
                                $brojStudenata = $DBveza->polozenBrojStudenata($predmet_id, $godina, $ocena);
                                $aResult['result'][$indeks][] = (int)$brojStudenata["brojStudenata"];
                            }
                            
                            $ocena = $ocena + 1;
                        }

                    }
                    else
                    {
                        $aResult['result'] = "Nije proslo";
                    }
               }
               break;

            default:
                $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
                break;
        }

    }

    echo json_encode($aResult);
?>