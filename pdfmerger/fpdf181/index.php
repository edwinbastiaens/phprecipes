<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('logo.png',10,6,30);

        $this->SetFont('Arial','',15);
        $this->Cell(25,30,'Tolima Comm.V');
        $this->SetFont('Arial','',12);
        $this->Ln(18);
        $this->Cell(0,5,'De Burburestaat 29 bus 11, 2000 Antwerpen',0,1);
        $this->Cell(0,5,'BTW: BE 0828.535.594',0,1);
        $this->Cell(0,5,'Bank: Argenta BE78 9730 0863 8286',0,1);
        $this->Ln();
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-55);
        // Arial italic 8
        $this->SetFont('Arial','I',7);
        // Page number
        $legal = "Deze factuur is betaalbaar binnen 15 kalenderdagen na factuurdatum. Bij ontstentenis van betaling binnen de overeengekomen termijn, zullen vanaf de vervaldag op het onbetaald bedrag van de betrokken factuur van rechtswege en zonder voorafgaande ingebrekestelling nalatigheidsinteresten verschuldigd zijn gelijk aan de rente bepaald in de Wet van 2 augustus 2002 (gewijzigd bij Wet van 22 november 2013) betreffende de bestrijding van de betalingsachterstand bij handelstransacties. Eveneens is de Opdrachtgever bij niet-betaling van het factuur op de vervaldag, van rechtswege en zonder voorafgaande ingebrekestelling, ten titel van schadebeding een forfaitaire schadevergoeding verschuldigd van 15% van het op de vervaldag onbetaalde factuurbedrag, met een minimum van 50 EUR, onverminderd het recht van de uitvoerder een hogere schadevergoeding te vorderen mits bewijs van grotere werkelijk geleden schade. Bovendien behoud Tolima zich het recht voor om bij niet-betaling van het factuurbedrag of in geval van laattijdige betaling een vergoeding te vorderen voor de gerechtelijke invorderingskosten, dit conform het recht op de gerechtskosten en het recht op de bijdrage in de erelonen en onkosten van advocaten. Alle geschillen m.b.t. deze factuur behoren tot de uitsluitende bevoegdheid van de rechtbanken in Antwerpen.";
        $this->Write(5,$legal);
    }
}

//$pdf = new FPDF();
//$pdf->AddPage();
//$pdf->SetFont('Arial','B',16);
//$pdf->Cell(40,10,'Hello World!');
//$pdf->Output();
$klanten = [
    "Denk Freelance BVBA" => [
        "adres" => "Tielendorp 47",
        "plaats" => "2460 Kasterlee",
        "btw" => "BE 0830.711.265",
    ],
    "Technology Consulting & Solutions" => [
        "adres" => "De Burburestraat 29 bus 11",
        "plaats" => "2000 Antwerpen",
        "btw" => "BTW: BE0476.130.735",
    ],
    "Belgisch-Zweedse Vereniging" => [
        "adres" => "Florencestraat 39",
        "plaats" => "1050 Brussel",
        "btw" => "",
    ],
    "Patricia Van den Kerckhove"  => [
        "adres" => "",
        "plaats" => "",
        "btw" => "",
    ],
    "DName-iT NV"  => [
        "adres" => "Gaston Geenslaan 1",
        "plaats" => "3001 Leuven",
        "btw" => "BE00506.754.823",
    ],];

class Facturen
{
    public static function form()
    {
        global $klanten;
        $namen = "";
        foreach($klanten as $k => $v){
            $namen .= "<option>".$k."</option>";
        }
        return <<<RET
    <form method='post'>
        <select name='naam'>{$namen}</select><br/><br/>
        <input type='number' plqaceholder='factuurnummer' min='1' name='nr' value='1' /><br/><br/>
        <textarea rows='8' cols='80' name='prestaties'>100 uren Onderhoud en development aan websites aan een uurtarief van 36- Euro netto + 7.56- Euro BTW(21%)</textarea>
        <br/><br/>
        <input name='prijs' placeholder='nettoprijs' value='' /><br/><br/>
        <input type='submit' value='aanmaken' />
    </form>
RET;
    }
    public static function process()
    {
        global $klanten;
        if (! isset($_POST['naam'])) return false;
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $naam = $_POST['naam'];
        $adres = $klanten[$naam]["adres"];
        $plaats = $klanten[$naam]["plaats"];
        $btw = $klanten[$naam]["btw"];

        $pdf->SetFont('Arial','',12);
        //$pdf->Cell($w,$h,$txt,$border,$ln,$align);
        $pdf->SetXY(120,50);
        $pdf->Write(5,$naam);
        $pdf->SetXY(120,57);
        $pdf->Write(5,$adres);
        $pdf->SetXY(120,64);
        $pdf->Write(5,$plaats);
        $pdf->SetXY(120,71);
        $pdf->Write(5,$btw);

        $pdf->Line(1, 100, 207, 100);  
        $pdf->Line(1, 110, 207, 110);
        $pdf->SetXY(15,103);
        $nr = $_POST['nr'];
        if ($nr < 10) {
            $snr = "00" . $nr;
        } else if ($nr < 100) {
            $snr = "0" . $nr;
        }
        $factuurnr = "Antwerpen, ".date('d')."-".date('m')."-".date('Y')."                         FACTUUR Nr. ".date('Y').$snr;
        $pdf->Write(5,$factuurnr);

        $prestaties = $_POST['prestaties'];
        $pdf->SetXY(15,120);
        $pdf->Write(5,"Voor volgende prestaties door Tolima cv. :");
        $pdf->SetXY(15,130);
        $pdf->Write(5,$prestaties);
        $pdf->SetXY(15,160);
        $pdf->Write(5,"wordt volgende prijs aangerekend :");
        
        $prijs = floatval($_POST['prijs']);
        $btw = floatval(0.21 * $prijs);
        $tot = floatval(1.21 * $prijs);
        
        $pdf->SetXY(120,170);
        $pdf->Write(5,$prijs . " Euro   netto");
        $pdf->SetXY(120,177);
        $pdf->Write(5,$btw . " Euro     BTW 21%");
        $pdf->SetXY(120,184);
        $pdf->Write(5,$tot . " Euro     Totaal");
        $pdf->Line(120, 182, 160, 182);
        $pdf->Output();
    }
}

if (!Facturen::process()){
    echo Facturen::form();
}
