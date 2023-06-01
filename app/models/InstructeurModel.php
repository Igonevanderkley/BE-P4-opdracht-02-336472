<?php

class InstructeurModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getInstructeurs()
    {
        $sql = "SELECT * 
                FROM instructeur 
                ORDER BY AantalSterren DESC ";

        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function getInstructeurInfoById($instructeurId)
    {
        $sql = "SELECT INST.Voornaam
                        ,INST.Tussenvoegsel
                        ,INST.Achternaam
                        ,DatumInDienst
                        ,INST.Aantalsterren
        FROM Instructeur as INST
        WHERE Id = $instructeurId";

        $this->db->query($sql);

        return $this->db->single();
    }

    public function getAssignedVehiclesToInstructor($instructeurId)
    {
        $sql = "SELECT 
                       TYVO.TypeVoertuig
                       ,VOER.Type
                       ,VOER.Kenteken
                       ,VOER.Bouwjaar
                       ,VOER.Brandstof
                       ,TYVO.Rijbewijscategorie
                FROM VoertuigInstructeur as VOIN
                INNER JOIN Voertuig as VOER
                ON         VOER.Id = VOIN.VoertuigId

                INNER JOIN TypeVoertuig as TYVO
                ON         TYVO.Id = VOER.TypeVoertuigId
                WHERE VOIN.InstructeurId = $instructeurId
                
                ORDER BY TYVO.RijbewijsCategorie ASC";

        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function getUnassignedVehicles()
    {
        $sql = "SELECT TypeVoertuig.TypeVoertuig, Type, Voertuig.Id as Id, Voertuig.Kenteken, Voertuig.Bouwjaar, Voertuig.Brandstof, TypeVoertuig.Rijbewijscategorie
        FROM Voertuig
        LEFT JOIN VoertuigInstructeur ON Voertuig.Id = VoertuigInstructeur.VoertuigId
        LEFT JOIN TypeVoertuig ON Voertuig.TypeVoertuigId = TypeVoertuig.Id
        WHERE VoertuigInstructeur.Id IS NULL;
        ";

        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function assignVehicleToInstructor($instructeurId, $vehicleId)
    {
        $sql = "INSERT INTO VoertuigInstructeur (VoertuigId, InstructeurId, DatumToekening, DatumAangemaakt, DatumGewijzigd)
        VALUES ($vehicleId, $instructeurId, CURRENT_DATE, CURRENT_DATE, CURRENT_DATE);";

        $this->db->query($sql);

        return $this->db->resultSet();
    }
}
