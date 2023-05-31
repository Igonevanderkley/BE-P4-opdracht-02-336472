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

    // public function getGegevensInstructeur()
    // {
    //     $sql = "SELECT ty.typeVoertuig, vo.Type, vo.Kenteken, vo.Bouwjaar, vo.Brandstof, ty.RijbewijsCategorie
    //             from instructeur ins
    //             left join voertuiginstructeur voins
    //             on ins.Id = voins.Id
    //             left join voertuig vo
    //             on vo.Id = ins.Id
    //             left join typevoertuig ty
    //             on ty.Id = vo.typevoertuigId
    //             where ins.Id = 1";

    //     $this->db->query($sql);

    //     return $this->db->resultSet();
    // }

    // public function getLizhan(){
    //     $sql = "SELECT * 
    //     FROM instructeur
    //     where Id = 1";

    //     $this->db->query($sql);

    //     return $this->db->resultSet();
    // }

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
}
