<?php
class Dashboard_model extends CI_Model
{

    function getAllMetrics()
    {
        $query = "SELECT mo.id AS id, met.name AS name FROM MetOrg AS mo, Metric AS met WHERE mo.metric=met.id";
        $q = $this->db->query($query);
        if($q->num_rows() > 0)
            return $this->buildAllMetrics($q);
        else
            return false;
    }

    function buildAllMetrics($q)  // Contruye y retorna el objeto persona
    {
        $this->load->library('Dashboard_library');
        $row = $q->result();
        foreach ($q->result() as $row)
        {
            $parameters = array
            (
                'id' => $row->id,
                'name' => $row->name
            );

            $metrica = new Dashboard_library();
            $metrica_array[] = $metrica->initialize($parameters);

        }

        return $metrica_array;
    }




    //////////////////////////// Funciones de ejemplo///////////////////////////////

    // Inserta todos los componentes de una persona a la base de datos
    function insertUserInfo($user) // busca el id de un usuario en base a su username y password
    {
        $query = "INSERT INTO stm_user_info";
        $query .= " (names, lastnames, nationality, home_address, company, birthdate,";
        $query .= " country, personal_email, home_phone, office_phone, mobile_phone, district, company_email, state,";
        $query .= " city, user_id, position, national_id)";

        $query .= " VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $q = $this->db->query($query, array($user->getNames(), $user->getLastnames(),$user->getNationality(),$user->getHomeAddress(),$user->getCompany(),
            $user->getBirthDate(), $user->getCountry(), $user->getPersonalEmail(), $user->getHomePhone(), $user->getOfficePhone(),
            $user->getMobilePhone(), $user->getDistrict(), $user->getCompanyEmail(), $user->getState(), $user->getCity(),
            $user->getUserId(), $user->getPosition(), $user->getNationalId()));

        return $q;
    }

    // Retorna todo los componentes asociados a una persona basado en el id de persona
    function getUser($user_id)
    {
        $query = "SELECT * FROM stm_user_info WHERE user_id = ?";
        $q = $this->db->query($query, $user_id);
        if($q->num_rows() > 0)
            return $this->buildUser($q);
        else
            return false;
    }

    //actualiza en la base de datos el registro de una persona basado en el parametro $person
    function updateUserInfo($user_info)
    {

        $query = "UPDATE stm_user_info SET ";
        $query .= " names = ?, lastnames = ?, birthdate = ?, ";
        $query .= " national_id = ?, personal_email = ?, ";
        $query .= " company_email = ?, country = ?, ";
        $query .= " state = ?, city = ?, district = ?, ";
        $query .= " home_address = ?, home_phone = ?, mobile_phone = ?, ";
        $query .= " office_phone = ?, company = ?, nationality = ?, position = ? ";
        $query .= " WHERE id = ?";
        $q = $this->db->query($query, array($user_info->getNames(),$user_info->getLastNames(),$user_info->getBirthDate(),$user_info->getNationalId(),
            $user_info->getPersonalEmail(), $user_info->getCompanyEmail(), $user_info->getCountry(),
            $user_info->getState(), $user_info->getCity(), $user_info->getDistrict(), $user_info->getHomeAddress(),
            $user_info->getHomePhone(), $user_info->getMobilePhone(), $user_info->getOfficePhone(), $user_info->getCompany(),
            $user_info->getNationality(), $user_info->getPosition(), $user_info->getId()));

    }



}