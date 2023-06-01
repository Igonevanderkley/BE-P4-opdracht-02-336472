<?php

class Instructeur extends BaseController
{
    private $instructeurModel;
    public function __construct()
    {
        $this->instructeurModel = $this->model('InstructeurModel');
    }
    public function index()
    {
        /**
         * Haal alle instructeurs op uit de database (model)
         */
        $instructeurs = $this->instructeurModel->getInstructeurs();

        // var_dump($instructeurs);
        $aantalInstructeurs = sizeof($instructeurs);

        /**
         * Maak de rows voor de tbody in de view
         */
        $tableRows = '';

        foreach ($instructeurs as $instructeur) {
            $datum = date_create($instructeur->DatumInDienst);
            $datum = date_format($datum, 'd-m-Y');
            $tableRows .=  "<tr>
                                <td>$instructeur->Voornaam</td>
                                <td>$instructeur->Tussenvoegsel</td>
                                <td>$instructeur->Achternaam</td>
                                <td>$instructeur->Mobiel</td>
                                <td>$datum</td>
                                <td>$instructeur->AantalSterren</td>
                                <td>
                                    <a href='" . URLROOT . "/Instructeur/gebruikteVoertuigen/$instructeur->Id'>
                                    <i class='bi bi-car-front'></i>
                                </td>
                            </tr>";
        }

        /**
         * Het $data-array geeft alle belangrijke info door aan de view
         */
        $data = [
            'title' => 'Instructeurs in dienst',
            'aantalInstructeurs' => $aantalInstructeurs,
            'tableRows' => $tableRows
        ];

        $this->view('Instructeur/index', $data);
    }

    public function gebruikteVoertuigen($instructeurId)
    {

        // Haal de info van de instructeur op uit de database (model)

        $instructeur = $this->instructeurModel->getInstructeurInfoById($instructeurId);

        // var_dump($instructeur);

        //opgalen toegewezen voerthuigen
        $assignedVehicles = $this->instructeurModel->getAssignedVehiclesToInstructor($instructeurId);

        // var_dump($assignedVehicles);       // $aantalSterren = sizeof($);

        $tableRows = "";

        foreach ($assignedVehicles as $vehicles) {
            $tableRows .= "<tr>
                                <td>$vehicles->TypeVoertuig</td>
                                <td>$vehicles->Type</td>
                                <td>$vehicles->Kenteken</td>
                                <td>$vehicles->Bouwjaar</td>
                                <td>$vehicles->Brandstof</td>
                                <td>$vehicles->Rijbewijscategorie</td>
                            </tr>";
        }


        $data = [
            'title'         => 'Door instructeur gebruikte voertuigen',
            'voornaam'      => $instructeur->Voornaam,
            'tussenvoegsel' => $instructeur->Tussenvoegsel,
            'achternaam'    => $instructeur->Achternaam,
            'datumInDienst'    => $instructeur->DatumInDienst,
            'aantalSterren' => $instructeur->Aantalsterren,
            'tableRows' => $tableRows,
            'instructeurId' => $instructeurId
        ];
        $this->view('Instructeur/gebruikteVoertuigen', $data);
    }

    public function toevoegen($instructeurId)
    {

        // Haal de info van de instructeur op uit de database (model)

        $instructeur = $this->instructeurModel->getInstructeurInfoById($instructeurId);

        // var_dump($instructeur);

        //opgalen toegewezen voerthuigen
        $unAssignedVehicles = $this->instructeurModel->getUnassignedVehicles($instructeurId);

        // var_dump($unAssignedVehicles);       // $aantalSterren = sizeof($);

        $tableRows = "";

        foreach ($unAssignedVehicles as $vehicles) {
            $tableRows .= "<tr>
                                <td>$vehicles->TypeVoertuig</td>
                                <td>$vehicles->Type</td>
                                <td>$vehicles->Kenteken</td>
                                <td>$vehicles->Bouwjaar</td>
                                <td>$vehicles->Brandstof</td>
                                <td>$vehicles->Rijbewijscategorie</td>
                                <td>
                                <a href='" . URLROOT . "/Instructeur/koppelen/$instructeurId/$vehicles->Id'>Voeg toe</a>
                                </td>
                            </tr>";
        }


        $data = [
            'title'         => 'Door instructeur gebruikte voertuigen',
            'voornaam'      => $instructeur->Voornaam,
            'tussenvoegsel' => $instructeur->Tussenvoegsel,
            'achternaam'    => $instructeur->Achternaam,
            'datumInDienst'    => $instructeur->DatumInDienst,
            'aantalSterren' => $instructeur->Aantalsterren,
            'tableRows' => $tableRows,
            'instructeurId' => $instructeurId
        ];
        $this->view('Instructeur/toevoegen', $data);
    }

    public function koppelen($instructeurId, $vehicleId)
    {
        // var_dump($instructeurId, $vehicleId);

        $this->instructeurModel->assignVehicleToInstructor($instructeurId, $vehicleId);

        header("Refresh: 3; url=/Instructeur/toevoegen/$instructeurId");

        echo "Gelukt!";
    }
}
