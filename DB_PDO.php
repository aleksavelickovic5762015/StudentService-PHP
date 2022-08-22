<?php
class DB_PDO
{
    const server = "localhost";
    const kor_ime = "root";
    const sifra = "";
    const baza = "studentskaSluzba";

    private $veza;

    //konstruktor
    function __construct()
    {
        try
        {
            $vezaString = "mysql:host=".self::server.";dbname=".self::baza.";charset=utf8";
            $this->veza = new PDO($vezaString, self::kor_ime, self::sifra);
            //////EXCEPTIONS
            $this->veza->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    function __destruct()
    {
        $this->veza = null;
    }
    
    public function proveraSifra($kor_ime, $sifra)
    {
        try
        {
            $upit = "SELECT tip, tip_id
                     FROM sifra
                     WHERE kor_ime='{$kor_ime}' AND sifra='{$sifra}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function studentInfo($student_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM student
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function upisMaxGodina($student_id)
    {
        try
        {
            $upit = "SELECT MAX(godina_id) AS maxGodina
                     FROM upis
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function upisInfo($student_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM upis
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function sumaESPB($student_id)
    {
        try
        {
            $upit = "SELECT SUM(predmet_ESPB) AS sumaESPB
                     FROM polozen AS po INNER JOIN predmet AS pr
                                            ON po.predmet_id = pr.predmet_id
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function studentPolozeni($student_id)
    {
        try
        {
            $upit = "SELECT predmet_sifra, naziv, predmet_semestar, polozen_ocena,
                            polozen_datum, nastavnik_ime, nastavnik_prezime
                     FROM polozen AS po INNER JOIN predmet AS pr
                                            ON po.predmet_id = pr.predmet_id
                                        INNER JOIN nastavnik AS n
                                            ON pr.nastavnik_id = n.nastavnik_id
                     WHERE po.student_id='{$student_id}'
                     ORDER BY predmet_semestar";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function studentProsek($student_id)
    {
        try
        {
            $upit = "SELECT AVG(polozen_ocena) AS prosekOcena
                     FROM polozen
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function studentNepolozeni($student_id, $semestarUpisan)
    {
        try
        {
            $upit = "SELECT predmet_sifra, naziv, predmet_semestar, predmet_ESPB,
                            nastavnik_ime, nastavnik_prezime, pr.predmet_id AS predmet_id
                     FROM predmet AS pr INNER JOIN nastavnik AS n
                                            ON pr.nastavnik_id = n.nastavnik_id
                     WHERE pr.predmet_id NOT IN (SELECT predmet_id
                                                 FROM polozen
                                                 WHERE student_id='{$student_id}') AND
                           predmet_semestar <= '{$semestarUpisan}'
                     ORDER BY predmet_semestar";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function prijavaIspita($student_id, $predmet_id, $rok_id, $prijavaIspita_datum)
    {
        try
        {
            $upit = "INSERT INTO prijavaIspita
                        (student_id, predmet_id, rok_id, prijavaIspita_datum)
                     VALUES ('{$student_id}', '{$predmet_id}', '{$rok_id}', '{$prijavaIspita_datum}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function rokMaxID()
    {
        try
        {
            $upit = "SELECT *
                     FROM rok
                     WHERE rok_id = (SELECT MAX(rok_id)
                                     FROM rok)";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function brojPrijavaIspita($student_id, $predmet_id)
    {
        try
        {
            $upit = "SELECT COUNT(*) AS brojPrijava
                     FROM prijavaIspita
                     WHERE student_id='{$student_id}' AND predmet_id='{$predmet_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function brojPrijavaIspita_ROK($student_id, $predmet_id, $rok_id)
    {
        try
        {
            $upit = "SELECT COUNT(*) AS brojPrijava
                     FROM prijavaIspita
                     WHERE student_id='{$student_id}' AND predmet_id='{$predmet_id}' AND rok_id='{$rok_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function novacSuma($student_id)
    {
        try
        {
            $upit = "SELECT suma
                     FROM novac
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function naplataPrijave($student_id)
    {
        try
        {
            $upit = "UPDATE novac
                     SET suma = suma - 1000
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function uplata($student_id, $uplata_foto)
    {
        try
        {
            $upit = "INSERT INTO uplata
                        (uplata_foto, student_id)
                     VALUES ('{$uplata_foto}', '{$student_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////
    /////RADNIK
    public function radnikInfo($radnik_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM radnik
                     WHERE radnik_id='{$radnik_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function uplataInfo()
    {
        try
        {
            $upit = "SELECT uplata_id, broj_indeksa, student_ime, student_prezime, student_adresa
                     FROM uplata AS u INNER JOIN student AS s
                                            ON u.student_id = s.student_id";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function uplataPrikaz($uplata_id)
    {
        try
        {
            $upit = "SELECT uplata_foto, student_id
                     FROM uplata
                     WHERE uplata_id='{$uplata_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function novacAzuriranje($student_id, $iznos)
    {
        try
        {
            $upit = "UPDATE novac
                     SET suma = suma + '{$iznos}'
                     WHERE student_id='{$student_id}'";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function uplataBrisanje($uplata_id)
    {
        try
        {
            $upit = "DELETE
                     FROM uplata
                     WHERE uplata_id='{$uplata_id}'";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }

    public function studentPretraga($broj_indeksa)
    {
        try
        {
            $upit = "SELECT student_id
                     FROM student
                     WHERE broj_indeksa='{$broj_indeksa}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function godinaUslovESPB($godina_id)
    {
        try
        {
            $upit = "SELECT godina_ESPB
                     FROM godina
                     WHERE godina_id='{$godina_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function upisNaredna($student_id, $godina_id, $upis_datum, $obnova=0)
    {
        try
        {
            $upit = "INSERT INTO upis
                        (upis_datum, obnova, student_id, godina_id)
                     VALUES ('{$upis_datum}', '{$obnova}', '{$student_id}', '{$godina_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function upisObnova($student_id, $godina_id, $upis_datum)
    {
        try
        {
            $upit = "UPDATE upis
                     SET obnova = obnova + 1, upis_datum = '{$upis_datum}'
                     WHERE student_id='{$student_id}' AND godina_id='{$godina_id}'";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }

    public function noviRok($rok_prijava_pocetak, $rok_prijava_kraj, $rok_pocetak, $rok_kraj, $rok_naziv)
    {
        try
        {
            $upit = "INSERT INTO rok
                        (rok_prijava_pocetak, rok_prijava_kraj, rok_pocetak, rok_kraj, rok_naziv)
                     VALUES ('{$rok_prijava_pocetak}', '{$rok_prijava_kraj}', '{$rok_pocetak}', '{$rok_kraj}', '{$rok_naziv}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    
    public function radnikZapisnik()
    {
        try
        {
            $upit = "SELECT zapisnik_id, zapisnik_ocena, zapisnik_datum,
                            predmet_sifra, naziv, predmet_semestar,
                            broj_indeksa, student_ime, student_prezime,
                            nastavnik_ime, nastavnik_prezime
                     FROM zapisnik AS z INNER JOIN predmet AS pr
                                            ON z.predmet_id = pr.predmet_id
                                        INNER JOIN student AS s
                                            ON z.student_id = s.student_id
                                        INNER JOIN nastavnik AS n
                                            ON pr.nastavnik_id = n.nastavnik_id
                     WHERE zapisnik_ocena <> 5 AND
                           pr.predmet_id NOT IN (SELECT po.predmet_id
                                                 FROM polozen AS po
                                                 WHERE po.student_id = s.student_id)
                     ORDER BY predmet_semestar, naziv, broj_indeksa";
                     
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    
    public function zapisnikInfo($zapisnik_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM zapisnik
                     WHERE zapisnik_id='{$zapisnik_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function noviPolozen($polozen_ocena, $polozen_datum, $student_id, $predmet_id)
    {
        try
        {
            $upit = "INSERT INTO polozen
                        (polozen_ocena, polozen_datum, student_id, predmet_id)
                     VALUES ('{$polozen_ocena}', '{$polozen_datum}', '{$student_id}', '{$predmet_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////
    /////NASTAVNIK
    public function nastavnikInfo($nastavnik_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM nastavnik
                     WHERE nastavnik_id='{$nastavnik_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    
    public function nastavnikPrijavljeni($nastavnik_id, $rok_id)
    {
        try
        {
            $upit = "SELECT p_i.prijavaIspita_id AS prijavaIspita_id,
                            pr.predmet_id AS predmet_id, predmet_sifra, naziv, predmet_semestar,
                            s.student_id, broj_indeksa, student_ime, student_prezime
                     FROM prijavaIspita AS p_i INNER JOIN predmet AS pr
                                                    ON p_i.predmet_id = pr.predmet_id
                                               INNER JOIN student AS s
                                                    ON p_i.student_id = s.student_id
                     WHERE pr.nastavnik_id='{$nastavnik_id}' AND rok_id='{$rok_id}' AND
                           pr.predmet_id NOT IN (SELECT z.predmet_id
                                                 FROM zapisnik AS z
                                                 WHERE z.student_id = s.student_id AND z.rok_id='{$rok_id}')
                     ORDER BY predmet_semestar, naziv, broj_indeksa";
                     //GROUP BY pr.predmet_id, s.student_id, prijavaIspita_id
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function prijavaIspitaInfo($prijavaIspita_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM prijavaIspita
                     WHERE prijavaIspita_id='{$prijavaIspita_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    
    public function noviZapisnik($zapisnik_ocena, $zapisnik_datum, $student_id, $predmet_id, $rok_id)
    {
        try
        {
            $upit = "INSERT INTO zapisnik
                        (zapisnik_ocena, zapisnik_datum, student_id, predmet_id, rok_id)
                     VALUES ('{$zapisnik_ocena}', '{$zapisnik_datum}', '{$student_id}', '{$predmet_id}', '{$rok_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    /*
    public function polozenStatistika($nastavnik_id)
    {
        try
        {
            $upit = "SELECT 
                     FROM polozen AS po INNER JOIN predmet AS pr
                     WHERE pr.nastavnik_id='{$nastavnik_id}'
                     GROUP BY 
                     HAVING ";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    */
    ///////////////////////////////////////////////////////////////
    /////ADMIN
    public function adminInfo($admin_id)
    {
        try
        {
            $upit = "SELECT *
                     FROM admin
                     WHERE admin_id='{$admin_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function studentMaxID()
    {
        try
        {
            $upit = "SELECT *
                     FROM student
                     WHERE student_id = (SELECT MAX(student_id)
                                         FROM student)";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function noviStudent($student_ime, $student_prezime, $student_tel, $student_adresa, $student_mejl, $broj_indeksa, $student_foto)
    {
        try
        {
            $upit = "INSERT INTO student
                        (student_ime, student_prezime, student_tel, student_adresa, student_mejl, broj_indeksa, student_foto)
                     VALUES ('{$student_ime}', '{$student_prezime}', '{$student_tel}', '{$student_adresa}', '{$student_mejl}', '{$broj_indeksa}', '{$student_foto}')";
            $pdo_izraz = $this->veza->exec($upit);
            //return true;
            return $this->veza->lastInsertId();     //VRACA POSLEDNJI UNET ID, PRI CITANJU NISU AZURIRANI PODACI DOVOLJNO BRZO
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function novaSifra($kor_ime, $sifra, $tip, $tip_id)
    {
        try
        {
            $upit = "INSERT INTO sifra
                        (kor_ime, sifra, tip, tip_id)
                     VALUES ('{$kor_ime}', '{$sifra}', '{$tip}', '{$tip_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function novaSifraProvera($kor_ime, $sifra)
    {
        try
        {
            $upit = "SELECT *
                     FROM sifra
                     WHERE kor_ime = '{$kor_ime}' OR sifra = '{$sifra}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e) {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function noviNovac($student_id, $suma=0)
    {
        try
        {
            $upit = "INSERT INTO novac
                        (suma, student_id)
                     VALUES ('{$suma}', '{$student_id}')";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function noviNastavnik($nastavnik_ime, $nastavnik_prezime, $nastavnik_tel, $nastavnik_mejl, $nastavnik_foto)
    {
        try
        {
            $upit = "INSERT INTO nastavnik
                        (nastavnik_ime, nastavnik_prezime, nastavnik_tel, nastavnik_mejl, nastavnik_foto)
                     VALUES ('{$nastavnik_ime}', '{$nastavnik_prezime}', '{$nastavnik_tel}', '{$nastavnik_mejl}', '{$nastavnik_foto}')";
            $pdo_izraz = $this->veza->exec($upit);
            //return true;
            return $this->veza->lastInsertId();     //VRACA POSLEDNJI UNET ID, PRI CITANJU NISU AZURIRANI PODACI DOVOLJNO BRZO
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    public function noviRadnik($radnik_ime, $radnik_prezime, $radnik_tel, $radnik_mejl)
    {
        try
        {
            $upit = "INSERT INTO radnik
                        (radnik_ime, radnik_prezime, radnik_tel, radnik_mejl)
                     VALUES ('{$radnik_ime}', '{$radnik_prezime}', '{$radnik_tel}', '{$radnik_mejl}')";
            $pdo_izraz = $this->veza->exec($upit);
            return $this->veza->lastInsertId();     //VRACA POSLEDNJI UNET ID, PRI CITANJU NISU AZURIRANI PODACI DOVOLJNO BRZO
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }
    ///////////////////////////////////////////////////////////////
    /////PREDMETI
    public function predmetiInfo()
    {
        try
        {
            $upit = "SELECT predmet_sifra, naziv, predmet_semestar, predmet_ESPB, p.predmet_id AS predmet_id,
                            nastavnik_ime, nastavnik_prezime
                     FROM predmet AS p INNER JOIN nastavnik AS n
                                        ON p.nastavnik_id = n.nastavnik_id";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function predmetiInfo_Nastavnik($nastavnik_id)
    {
        try
        {
            $upit = "SELECT predmet_sifra, naziv, predmet_semestar, predmet_ESPB, p.predmet_id AS predmet_id,
                            nastavnik_ime, nastavnik_prezime
                     FROM predmet AS p INNER JOIN nastavnik AS n
                                        ON p.nastavnik_id = n.nastavnik_id
                     WHERE n.nastavnik_id='{$nastavnik_id}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }

    public function nastavnikInfo_Combo()
    {
        try
        {
            $upit = "SELECT nastavnik_id, nastavnik_ime, nastavnik_prezime
                     FROM nastavnik";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function predmetiNoviNastavnik($predmet_id, $nastavnik_id)
    {
        try
        {
            $upit = "UPDATE predmet
                     SET nastavnik_id = '{$nastavnik_id}'
                     WHERE predmet_id='{$predmet_id}'";
            $pdo_izraz = $this->veza->exec($upit);
            return true;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
            return false;
        }
    }

    public function polozenGodine($predmet_id)
    {
        try
        {
            $upit = "SELECT DISTINCT YEAR(polozen_datum) AS godina
                     FROM polozen
                     WHERE predmet_id='{$predmet_id}'
                     ORDER BY YEAR(polozen_datum)"; // DESC
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetchALL(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
    public function polozenBrojStudenata($predmet_id, $godina, $polozen_ocena)
    {
        try
        {
            $upit = "SELECT COUNT(*) AS brojStudenata
                     FROM polozen
                     WHERE predmet_id='{$predmet_id}' AND
                            YEAR(polozen_datum)='{$godina}' AND polozen_ocena='{$polozen_ocena}'";
            $pdo_izraz = $this->veza->query($upit);
            $podaci = $pdo_izraz->fetch(PDO::FETCH_ASSOC);
            return $podaci;
        }
        catch(PDOException $e)
        {
            echo "GRESKA: ";
            echo $e->getMessage();
        }
    }
}

?>