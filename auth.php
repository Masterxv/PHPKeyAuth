<?php

class Auth
{
private:
    $m_db, $m_timeLeft, $m_currTime, $m_banUser, $m_key, $m_hwid, $m_ip;

public:
    function __construct(mysqli $db)
    {
        $this->m_db = $db;
    }

    // -- safetly stores the recieved url params
    function getData($keyParam, $hwidParam, $banParam)
    {
        $this->m_key = $this->$m_db->real_escape_string($keyParam); $this->m_hwid = $this->$m_db->real_escape_string($hwidParam); $this->$m_banUser = $banParam
    }

    function sendData()
    {

    }

    function firstTimeSetup(/*$keyParam*/)
    {
        // get the keyparam and and the hwid with SQL
        // selects the hwid of a user where key = $keyParam
        $result = $this->m_db->query("SELECT hwid FROM Users WHERE token='$this->m_key'");
        if ($result === TRUE)
            echo "HWID found!";
        else
            echo "HWID Search failed!";
    }

    function Exists($keyParam, $hwidParam)
    {
        // -- if hwid matches and key return true, if key matches but not the hwid (if the key is found for multiple hwids, ban the hwid)
        $result = $this->m_db->query("SELECT token, hwid FROM Users WHERE token='$this->m_key' AND hwid=`$this->m_hwid`");
    
        // -- if the key and hwid matches the user exists
        return $result->num_rows == 1;
    }

    function isHWIDBanned($keyParam, $banned_hwids)
    {
        // -- if the key param matches with a banned key return true (key banned)
        return $this->m_db->query("SELECT token FROM banned_hwids WHERE token=´$keyParam");
    }

    function banHWID()
    {
        // -- add key to banned_hwids table
        // -- add hwid to banned_hwids     
    }

    // -- gets the ip address from the connected party
    function getIP()
    {
        $this->m_ip = $_SERVER["REMOTE_ADDR"];
    }

    
    function getTimeLeft()
    {
        
    }

    function timeOver()
    {

    }

    function addTime($time)
    {

    }
}

?>