<?php

class Auth
{
private $m_db, 
    $m_timeLeft, 
    $m_currTime, 
    $m_banUser, 
    $m_key, 
    $m_hwid, 
    $m_ip;

    public function __construct(mysqli $db)
    {
        $this->m_db = $db;
    }

    // -- safetly stores the recieved url params
    function getData($keyParam, $hwidParam, $banParam)
    {
        $this->m_key = $this->m_db->real_escape_string($keyParam); 
        $this->m_hwid = $this->m_db->real_escape_string($hwidParam); 
        $this->m_banUser = $banParam;
    }

    function sendData($data)
    {

    }

/*     function fetchHWIDData()
    {
        $stmt = $this->m_db->prepare("SELECT * FROM users WHERE hwid = :$this->m_hwid");
        $stmt->bind_param();
    } */

    function firstTimeSetup()
    {
        // get the keyparam and and the hwid with SQL
        // selects the hwid of a user where key = $keyParam

        $result = $this->m_db->query("SELECT hwid, token FROM users WHERE token=$this->m_key");

        $r    = mysqli_fetch_array($result);
        $hwid = $r['hwid'];
        $key  = $r['token'];

        if ($hwid == 0 && $key == $this->m_key)
        {
            // Update the hwid if its zero and the key is real
            // UPDATE users SET hwid = 1111 WHERE token = 1337
            $t = $this->m_db->query("UPDATE users SET hwid=$this->m_hwid WHERE token=$this->m_key");
            echo "first time setup true!\n" . $t;
        }
        else echo "first time setup false!\n";
    }

    function Exists()
    {
        // -- if hwid matches and key return true, if key matches but not the hwid (if the key is found for multiple hwids, ban the hwid)
        $result = $this->m_db->query("SELECT token, hwid FROM users WHERE token=$this->m_key AND hwid=$this->m_hwid");
    
        // -- if the key and hwid matches the user exists
        return $result->num_rows == 1;
    }

    function userBanned()
    {
        // -- if the key param matches with a banned key return true (key banned)
        $r = $this->m_db->query("SELECT * FROM banned_users WHERE hwid = $this->m_hwid");
        return $r->num_rows == 1;
    }

    function banHWID()
    {
        // -- add key to banned_hwids table
        // -- add hwid to banned_hwids     
        $this->m_db->query("INSERT INTO banned_users (token, hwid) VALUES ($this->m_key, $this->m_hwid)");
    }

    // -- gets the ip address from the connected party
    function getIP()
    {
        $this->m_ip = $_SERVER["REMOTE_ADDR"];
    }

    
    function timeLeft()
    {
        
    }

    function timeExpired()
    {
        return FALSE;
    }

    function addTime($time)
    {

    }
}

?>